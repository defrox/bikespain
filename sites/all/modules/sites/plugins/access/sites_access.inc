<?php

/**
 * @file
 * Plugin to provide access control based upon sites.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('Current site'),
  'description' => t('Control access by Site.'),
  'callback' => 'sites_ctools_access_check',
  'default' => array('sids' => array()),
  'settings form' => 'sites_ctools_access_settings',
  'settings form submit' => 'sites_ctools_access_settings_submit',
  'summary' => 'sites_ctools_access_summary',
);

/**
 * Settings form for the 'by Site' access plugin
 */
function sites_ctools_access_settings($form, &$form_state, $conf) {

  $sites = SiteController::getSites();
  $options = array();
  foreach($sites as $site) {
    $options[$site->sid] = $site->name;
  }

  $form['settings']['sids'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Site'),
    '#default_value' => $conf['sids'],
    '#options' => $options,
    '#description' => t('Only on the checked sites will be granted access.'),
  );
  return $form;
}

/**
 * Compress the sites allowed to the minimum.
 */
function sites_ctools_access_settings_submit($form, &$form_state) {
  $form_state['values']['settings']['sids'] = array_keys(array_filter($form_state['values']['settings']['sids']));
}

/**
 * Check for access.
 */
function sites_ctools_access_check($conf, $context) {
  $current_site = SiteController::getCurrentSite();
  return empty($conf['sids']) ||
    (!empty($current_site) && in_array($current_site->sid, $conf['sids']));
}

function sites_ctools_access_summary($conf, $context) {
  if (!isset($conf['sids'])) {
    $conf['sids'] = array();
  }
  $sites = SiteController::getSites();

  $names = array();
  foreach (array_filter($conf['sids']) as $sid) {
    $names[] = check_plain($sites[$sid]->name);
  }

  if (empty($names)) {
    return t('Page/variant can be visible on any site');
  }
  return t('Current site is one of "@sites"', array('@sites' => implode(', ', $names)));
}
