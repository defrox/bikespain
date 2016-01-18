<?php

/**
 * @file
 * Views module integration for Sites module.
 */

/**
 * Implements hook_views_handlers().
 */
function sites_views_handlers() {
  return array(
    'info' => array(
      'path' => drupal_get_path('module', 'sites') . '/plugins',
    ),
    'handlers' => array(
      'sites_handler_filter_site_current' => array(
        'parent' => 'views_handler_filter',
      ),
      'sites_handler_filter_site' => array(
        'parent' => 'views_handler_filter_many_to_one',
      ),
      'sites_handler_field_site_title' => array(
        'parent' => 'views_handler_field',
      ),
    ),
  );
}

/**
 * @todo Please document this function.
 * @see http://drupal.org/node/1354
 */
function sites_views_data() {
  $data = array();
  $data['site_node']['table']['group']  = t('Sites');

  $data['site_node']['table']['join'] = array(
    'site_node' => array(
      'left_field' => 'sid',
      'field' => 'sid',
    ),
    'node' => array(
      'left_field' => 'nid',
      'field' => 'nid',
    ),
    'sites' => array(
      'left_field' => 'sid',
      'field' => 'sid',
    ),
  );

  // sid field
  $data['site_node']['current_site_sid'] = array(
    'title' => t('Current site'),
    'help' => t('Filter node published in current site.'),
    'filter' => array(
      'title' => t('Current site'),
      'handler' => 'sites_handler_filter_site_current',
      'numeric' => TRUE,
      'allow empty' => TRUE,
    ),
  );

  $data['site_node']['sid'] = array(
    'title' => t('Sites'),
    'help' => t('Filter node published on selected sites.'),
    'filter' => array(
      'handler' => 'sites_handler_filter_site',
    ),
  );

  $data['site_node']['site'] = array(
    'title' => t('Site title'),
    'help' => t('Display title of site.'),
    'field' => array(
      'handler' => 'sites_handler_field_site_title',
    ),
  );

  return $data;
}

/**
 * Implements hook_views_plugins().
 */
function sites_views_plugins() {
  $plugins = array(
    'access' => array(
      'sites' => array(
        'title' => t('Sites'),
        'help' => t('Check the sites for which this view will be available.'),
        'uses options' => TRUE,
        'handler' => 'sites_access_plugin',
        'path' => drupal_get_path('module', 'sites') . '/views/plugins',
      ),
    ),
  );

  return $plugins;
}
