<?php

/**
 * @file
 * General helper class for sites.
 */

class SiteHelper {

  /**
   * Builds the sites $form part.
   *
   * @param stdclass $node
   *   The node being edited/added.
   *
   * @return array
   *   Form element, ready to be used by FAP.
   */
  public static function buildSiteFormElement($node) {
    // Build the sites #options.
    $sites = SiteController::getSites();
    $sites_options = array();
    foreach ($sites as $site) {
      $sites_options[$site->sid] = $site->name;
    }

    // Get the default sites value.
    $default = array();
    if (isset($node->sites)) {
      foreach ($node->sites as $site) {
        $default[$site->sid] = $site->sid;
      }
    }

    // If we're in a subsite and no sites are selected, we check the current
    // subsite by default.
    $site = SiteController::getCurrentSite();
    if (empty($default) && isset($site->sid)) {
      $default[$site->sid] = $site->sid;
    }

    $form_element = array(
      'sites-options' => array(
        '#type' => 'fieldset',
        '#title' => t('Sites'),
        '#collapsible' => TRUE,
        '#collapsed' =>  TRUE,
        '#group' => 'additional_settings',
        'sites' => array(
          '#title' => t('Sites'),
          '#description' => t('Select site to publish this node to. Leaving blank will allow node to be published everywhere.'),
          '#type' => 'checkboxes',
          '#size' => 5,
          '#multiple' => TRUE,
          '#parents' => array('sites'),
          '#default_value' => $default,
          '#options' => $sites_options,
          '#attached' => array(
            'js' => array(drupal_get_path('module', 'sites') . '/sites.node.js'),
          ),
        ),
      ),
    );

    // No sites are added yet.
    if (empty($sites_options)) {
      $form_element['sites-options']['sites']['#type'] = 'item';
      $form_element['sites-options']['sites']['#markup'] = t("You haven't created any sites yet.") . ' ' . l(t('Create a new site.'), 'admin/structure/sites/add');
      $form_element['sites-options']['sites']['#description'] = '';
    }

    return $form_element;
  }

  /**
   * Alters the node add/edit form when in a subsite.
   *
   * @param array $form
   *   The form to be altered.
   */
  public static function alterNodeForm(&$form) {
    $site = SiteController::getCurrentSite();
    // Stop if we could't load a site, the site has no main_menu or the form
    // does not have a menu.
    if ($site === FALSE || empty($site->menu_main_menu) || ! isset($form['menu'])) {
      return;
    }

    $menu_name = $site->menu_main_menu;
    $node_type = $form['type']['#value'];
    // Build available menu items.
    $options = menu_parent_options(menu_get_menus(), $node_type);
    $filtered_options = array();
    foreach ($options as $key => $value) {
      $explode = explode(':', $key);
      // Only allow menu items that belong to this site's main menu.
      if ($explode[0] == $menu_name) {
        $filtered_options[$key] = $value;
      }
    }
    if (!empty($filtered_options)) {
      $form['menu']['link']['parent']['#options'] = $filtered_options;
    }
  }

  /**
   * Validates the add/edit site form.
   */
  public static function validateSiteForm(&$form, &$form_state) {
    // Check that site name is unique.
    if (self::siteNameIsUnique($form_state) === FALSE) {
      form_set_error('site_name', t('Site name should be unique.'));
    }
  }

  /**
   * Checks if a site name is unique.
   *
   * @param array $form_state
   *   Form state.
   *
   * @return bool
   *   True if site name is unique, false otherwise.
   */
  public static function siteNameIsUnique($form_state) {
    // Check that the site name is unique.
    $sites = SiteController::getSites();

    foreach ($sites as $site) {
      if ($site->name == $form_state['values']['site_name'] && $site->sid !== $form_state['values']['site_id']) {
        return FALSE;
      }
    }
    return TRUE;
  }
}
