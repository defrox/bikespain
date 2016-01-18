<?php

/**
 * @file
 * Core functions for Sites module.
 */

/**
 * Invoked by PURL.
 */
function sites_set_current_site($sid) {
  // Activate the Context module context condition
  if (module_exists('context') && $plugin = context_get_plugin('condition', 'site')) {
    $plugin->execute($sid);
  }

  // Make the sid and full site object easily available to other functionality
  $site = SiteController::getSite($sid);
  SiteController::setCurrentSite($site);

  // Handle per-site front page overrides
  if (!empty($site->front_page)) {
    if ($_GET['q'] == variable_get('site_frontpage', 'node')) {
      $_GET['q'] = $site->front_page; // This will require module weights to be set appropriately
      // Alternate method: drupal_goto($site->front_page);
    }
  }
}

/**
 * Clear all records in site_node for a given nid, then re-add the
 * current set of site/node relationships.
 *
 * @param int $nid
 *   Unique node id.
 * @param array $sids
 *   Array of unique site ids.
 *
 * @return void.
 */
function _sites_attach_node_to_sites($nid, $sids) {
  db_delete('site_node')->condition('nid', $nid)->execute();

  foreach ($sids as $sid) {
    db_insert('site_node')
      ->fields(array(
        'nid' => $nid,
        'sid' => $sid,
      ))
      ->execute();
  }
}

/**
 * Get the unique name of the menu to be used in place of the menu specified
 * in the $menu_name argument (right now, can only be main_menu or
 * secondary_menu).
 *
 * @param $menu_name
 *   Either 'main_menu' or 'secondary_menu'.
 *
 * @return bool
 *   If the requested menu is set, its name is returned.  Otherwise, FALSE.
 */
function sites_get_current_menu_source($menu_name) {
  $current_site = SiteController::getCurrentSite();
  if (!empty($current_site->{'menu_' . $menu_name})) {
    return $current_site->{'menu_' . $menu_name};
  }
  return FALSE;
}
