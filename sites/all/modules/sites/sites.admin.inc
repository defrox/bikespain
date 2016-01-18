<?php

/**
 * @file
 * Admin pages for Sites module.
 */

/**
 * Provide variables to theme_sites_list_sites().
 */
function sites_preprocess_sites_list_sites(&$vars) {
  $sites = $vars['sites'] = _SiteController::getSites();
  if (!empty($sites)) {
    $vars['header'] = array(t('Site title'), t('Path'), t('Operations'));
    $rows = array();
    foreach ($sites as $sid => $site) {
      $links = array(
        'edit' => array(
          'title' => t('edit'),
          'href' => 'admin/structure/sites/edit/' . $site->sid,
        ),
        'delete' => array(
          'title' => t('delete'),
          'href' => 'admin/structure/sites/delete/' . $site->sid,
        ),
      );
      $operations = theme('links', array('links' => $links, 'attributes' => array('class' => 'links inline')));
      $rows[] = array($site->title, $site->purl_prefix, $operations);
    }
    $vars['rows'] = $rows;
  }
}

/**
 * Page callback: sites admin overview page.
 */
function sites_page_admin_overview() {
  return SiteController::pageAdminOverview();
}

/**
 * Theme a Sites admin landing page.
 */
function theme_sites_list_sites($vars) {
  $output = '';
  if (!empty($vars['sites'])) {
    $output .= theme('table', array('header' => $vars['header'], 'rows' => $vars['rows']));
  }
  else {
    $output .= t('You currently have no sites.');
  }
  return $output;
}

/**
 * Add/edit sites form.
 */
function sites_site_form($form, &$form_state, $site_id = NULL) {
  $site = NULL;
  if (!$site_id) {
    $site = new Site();
  }
  else {
    $site = SiteController::getSite($site_id);
  }

  // Get a list of active themes.
  $active_themes = array();
  foreach (list_themes() as $name => $theme) {
    if ($theme->status) {
      $active_themes[$name] = $name;
    }
  }

  $form = array();

  $form['title'] = array(
    '#title' => t('Site title (used in administrative interface only)'),
    '#type' => 'textfield',
    '#default_value' => $site->title,
  );

  $form['machine_name'] = array(
    '#title' => t('Machine name for site title'),
    '#type' => 'machine_name',
    '#default_value' => $site->machine_name,
    '#machine_name' => array(
      'source' => array('title'),
      'exists' => 'sites_machine_name_exists',
    ),
  );

  $form['front_page'] = array(
    '#title' => t('Front page'),
    '#description' => t('Leave blank to use sitewide frontpage'),
    '#type' => 'textfield',
    '#default_value' => $site->front_page,
  );

  $form['site_name'] = array(
    '#title' => t('Site name'),
    '#description' => t('Used by some themes.  Will be visible to users.'),
    '#type' => 'textfield',
    '#default_value' => $site->name,
  );

  $form['secondary_menu'] = array(
    '#title' => t('Secondary menu'),
    '#description' => t('Unique menu name of the menu to insert into $secondary_menu (leave blank to use default Secondary menu)'),
    '#type' => 'textfield',
    '#default_value' => $site->menu_secondary_menu,
  );

  $form['theme'] = array(
    '#type' => 'select',
    '#title' => t('Theme'),
    '#description' => t('Select an active theme for this site.'),
    '#options' => $active_themes,
    '#default_value' => $site->theme,
  );

  // This subsite does not have a menu yet. We allow the user to automatically
  // create one.
  if (empty($site->menu_main_menu)) {
    $form['main_menu'] = array(
      '#title' => t('Main menu'),
      '#description' => t('Unique menu name of the menu to insert into $main_menu (leave blank to use default Main menu)'),
      '#type' => 'checkbox',
      '#default_value' => 1,
    );
  }
  else {
    $form['main_menu'] = array(
      '#title' => t('Main menu'),
      '#description' => t('Unique menu name of the menu to insert into $main_menu (leave blank to use default Main menu)'),
      '#type' => 'textfield',
      '#default_value' => $site->menu_main_menu,
    );
  }
  // Add context prefix form
  $form['purl'] = purl_form('sites', $site_id, $site->purl_prefix);

  $form['site_id'] = array(
    '#type' => 'hidden',
    '#value' => $site_id,
  );

  $form['actions']['#type'] = 'actions';
  $form['actions']['submit'] = array(
    '#value' => t('Save'),
    '#type' => 'submit',
  );
  if ($site_id) {
    $form['actions']['delete'] = array(
      '#type' => 'link',
      '#href' => 'admin/structure/sites/delete/' . $site_id,
      '#title' => t('Delete'),
      '#attributes' => array(
        'class' => array('button'),
      ),
    );
  }
  $form['actions']['cancel'] = array(
    '#type' => 'link',
    '#href' => 'admin/structure/sites',
    '#title' => t('Cancel'),
    '#attributes' => array(
      'class' => array('button'),
    ),
  );

  return $form;
}

/**
 * Handles validation for add/edit site form.
 */
function sites_site_form_validate(&$form, &$form_state) {
  SiteHelper::validateSiteForm($form, $form_state);
}

/**
 * Handles submit for adding or editing a site.
 */
function sites_site_form_submit($form, &$form_state) {
  // If a site_id is present in the form_values, we load the existing site.
  // Otherwise an empty site object is created.
  $site_id = $form_state['values']['site_id'];
  $site = empty($site_id) ? new Site() : SiteController::getSite($site_id);

  // Get the site properties from the form_state values.
  // @todo create a helper function for this.
  $site->title = $form_state['values']['title'];
  $site->front_page = $form_state['values']['front_page'];
  $site->machine_name = $form_state['values']['machine_name'];
  $site->name = $form_state['values']['site_name'];
  $site->menu_main_menu = $form_state['values']['main_menu'];
  $site->menu_secondary_menu = $form_state['values']['secondary_menu'];
  $site->theme = $form_state['values']['theme'];
  if (isset($form_state['values']['purl']['value'])) {
    $site->purl_prefix = $form_state['values']['purl']['value'];
  }
  // Save the site object.
  $site->save();
  // Automatically create a menu.
  if (empty($site_id)) {
    // Create a context.
    SiteResourcesController::createContext($site);
    // Create a main menu.
    if ($form_state['values']['main_menu'] === 1) {
      SiteResourcesController::createMenu($site, 'main_menu');
    }
  }
  // Site already exists, check if node paths needs updating.
  else if ($form['purl']['value']['#default_value'] !== $form_state['values']['purl']['value']) {
    SiteResourcesController::updatePaths($site_id, $form['purl']['value']['#default_value'], $form_state['values']['purl']['value']);
  }
  // Redirect to the sites admin overview page.
  $form_state['redirect'] = 'admin/structure/sites';
}

/**
 * Delete sites form.
 */
function sites_site_delete_form($form, $form_state, $site_id) {
  $site = SiteController::getSite($site_id);

  if ($site === FALSE) {
    drupal_goto('admin/structure/sites');
    return;
  }

  $form = array();
  $form['site_id'] = array(
    '#type' => 'value',
    '#value' => $site->sid,
  );
  $form = confirm_form($form, t('Do you want to delete site: @sitename ?', array('@sitename' => $site->title)), 'admin/structure/sites');

  return $form;
}

/**
 * Delete sites form submit handler.
 */
function sites_site_delete_form_submit($form, &$form_state) {
  // Get the site_id.
  $site_id = $form_state['values']['site_id'];
  $site = SiteController::getSite($site_id);
  // Delete the site and all of it's resources.
  $current_site = SiteController::getCurrentSite();
  if ($current_site != NULL) {
    $sid = $current_site->sid;

    // Disable PURL if necessary.
    if ($site_id == $sid) {
      purl_disable('TRUE');
    }
  }
  SiteController::deleteSite($site_id);

  drupal_set_message(t('The site @name has been deleted', array('@name' => $site->name)));
  $form_state['redirect'] = 'admin/structure/sites';
}
