<?php

/**
 * @file
 * Features API implementation for Sites module
 */

/**
 * Implements hook_features_export_options().
 */
function sites_features_export_options() {
  $options = array();
  foreach (SiteController::getSites() as $site) {
    $options[$site->machine_name] = $site->title;
  }
  return $options;
}

/**
 * Implements hook_features_export().
 */
function sites_features_export($data, &$export, $module_name) {
  $export['dependencies']['sites'] = 'sites';
  $export['dependencies']['purl'] = 'purl';

  foreach ($data as $component) {
    $export['features']['sites'][$component] = $component;
  }

  return array();
}

/**
 * Implements hook_features_export_render().
 */
function sites_features_export_render($module_name, $data, $export = NULL) {
  $code = array();
  $code[] = '$sites = array();';
  foreach ($data as $name) {
    $site = SiteController::getSiteByName($name);
    unset($site->sid);
    $code[] = "  \$sites['{$name}'] = " . features_var_export($site) .";";
  }
  $code[] = "return \$sites;";
  $code = implode("\n", $code);
  return array('sites_defaults' => $code);
}

/**
 * Implements hook_features_rebuild().
 */
function sites_features_rebuild($module_name) {
  $sites = module_invoke($module_name, 'sites_defaults');
  if (!empty($sites)) {
    foreach ($sites as $site) {
      $name = db_select('sites')->
        fields('sites', array('machine_name'))->
        condition('machine_name', $site->machine_name)->
        execute()->fetchField();
      if (empty($name)) {
        drupal_write_record('sites', $site);
      }
      else {
        db_update('sites')->
          fields((array)$site)->
          condition('machine_name', $site->machine_name)->
          execute();
      }
    }
  }
}

/**
 * Implements hook_features_revert().
 */
function sites_features_revert($module_name) {
  $sites = module_invoke($module_name, 'sites_defaults');
  if (!empty($sites)) {
    foreach ($sites as $site) {
      SiteController::deleteSiteByName($site);
    }
  }
}