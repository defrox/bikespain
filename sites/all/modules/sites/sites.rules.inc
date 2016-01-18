<?php

/**
 * Implements hook_rules_condition_info().
 */
function sites_rules_condition_info() {
  $conditions = array();
  $conditions['sites_rules_condition_current_site'] = array(
    'label' => t('Site name'),
    'parameter' => array(
      'sites' => array(
        'type' => 'token',
        'label' => t('Select site'),
        'options list' => 'sites_rules_sites_options',
        'restriction' => 'input',
      ),
    ),
    'group' => t('Sites'),
  );
  return $conditions;
}

/**
 * Callback function for sites_rules_condition_info().
 */
function sites_rules_condition_current_site($sites, $settings) {
  $site = SiteController::getCurrentSite();
  return ($site->sid === $sites);
}

/**
 * Callback function for returning all configured sites.
 */
function sites_rules_sites_options() {
  $options = array();
  $sites = SiteController::getSites();
  foreach($sites as $site) {
    $options[$site->sid] = $site->name;
  }
  return $options;
}

/**
 * @return mixed
 * Callback for themes select in sites_rules_action_info().
 */
function sites_rules_themes_select() {
  foreach (list_themes() as $name => $theme) {
    if ($theme->status) {
      $active_themes[$name] = $name;
    }
  }
  return $active_themes;
}

/**
 * Implements hook_rules_action_info().
 */
function sites_rules_action_info() {

  $actions = array();
  $actions['sites_rules_action_add_site'] = array(
    'label' => t('Create Site'),
    'parameter' => array(
      'machine_name' => array(
        'type' => 'text',
        'label' => t('Site\'s machine name.'),
        ),
      'title' => array(
        'type' => 'text',
        'label' => t('Site title (used in administrative interface only)'),
      ),
      'front_page' => array(
        'type' => 'text',
        'label' => t('Front page'),
        'optional' => TRUE,
      ),
      'site_name' => array(
        'type' => 'text',
        'label' => t('Site name'),
        'optional' => TRUE,
      ),
      'secondary_menu' => array(
        'type' => 'text',
        'label' => t('Secondary menu'),
        'optional' => TRUE,
      ),
      'theme' => array(
        'type' => 'text',
        'options list' => 'sites_rules_themes_select',
        'label' => t('Theme'),
        'description' => t('Choose the theme for your new site.'),
      ),
      'purl_prefix' => array(
        'type' => 'text',
        'label' => t('Path'),
        'description' => t('The path of your new site. Example: enter "/test" and url of your site will be [default_site_url]/test'),
      ),
      'main_menu' => array(
        'type' => 'boolean',
        'label' => t('Main menu'),
      ),
    ),
    'group' => t('Sites'),
  );
  return $actions;
}

/**
 * Callback function for sites_rules_action_info().
 */
function sites_rules_action_add_site($title, $front_page, $site_name, $secondary_menu, $theme, $purl_prefix, $main_menu) {
  $site = new Site();

  $site->title = $title;
  if (function_exists('pathauto_cleanstring')) {
    $machine_name = pathauto_cleanstring($site->title);
  }
  else {
    $machine_name = strtolower($site->title);
    $machine_name = preg_replace('/[^a-zA-Z0-9\/]+/', '_', $machine_name);
  }
  $exists = db_select('sites', 's')
    ->fields('s', array('machine_name'))
    ->condition('s.machine_name', $machine_name)
    ->execute()->fetchField();
  if(!empty($exists)){
    $machine_name = $machine_name . '_1';
  }

  $site->machine_name = $machine_name;
  $site->front_page = $front_page;
  $site->name = $site_name;
  $site->menu_main_menu = $main_menu;
  $site->menu_secondary_menu = $secondary_menu;
  $site->theme = $theme;
  $site->purl_prefix = $purl_prefix;

  // Save the site object.
  $site->save();
}
