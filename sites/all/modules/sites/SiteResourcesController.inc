<?php

/**
 * @file
 * Helper class to handle site resources such as a menu or a context.
 */

class SiteResourcesController {

  /**
   * Deletes resources for a given site.
   *
   * @param Site $site.
   *   The site object to delete resources for.
   */
  public static function deleteResources($site) {
    // Delete the context.
    if (module_exists('context')) {
      $context = context_load($site->name);
      if ($context && $context->tag == 'sites') {
        context_delete($context);
      }
    }

    // Delete the menu.
    $menu = menu_load($site->menu_main_menu);
    if ($menu) {
      menu_delete($menu);
    }
    // Delete the alias.
    $path = path_load($site->purl_prefix);
    if (! empty($path)) {
      path_delete($path);
    }
  }

  /**
   * Creates a context for a site.
   */
  public static function createContext($site) {
    if (! module_exists('context')) {
      return;
    }
    ctools_include('cleanstring');
    $context_name = ctools_cleanstring($site->name, array('separator' => '_', 'lower case' => TRUE));
    // Prepare a context object.
    $context = self::contextObjectPrepare();
    $context->name = $site->name;
    $context->description = "Sites context for site $site->name";
    $context->conditions = array(
      'site' => array(
        'values' => array(
          $site->sid => $site->sid,
        ),
      ),
    );
    context_save($context);
  }

  /**
   * Creates a menu for a site.
   */
  public static function createMenu($site, $menu_type = 'main_menu') {
    // Create the menu.
    $menu = array();
    $menu['title'] = $site->name;
    $menu['description'] = '';
    $menu['menu_name'] = str_replace(' ', '-', drupal_strtolower($site->name));
    menu_save($menu);
    // Save the site.
    $site->menu_main_menu = $menu['menu_name'];
    $site->save();
    // After the menu is created, we need to make this menu available for all
    // content types so we add the menu to the menu options content type
    // variable.
    $node_types = node_type_get_types();
    if (!empty($node_types)) {
      foreach ($node_types as $node_type) {
        $menus = variable_get('menu_options_' . $node_type->type, array('main-menu' => 'main-menu'));
        $menu_options = array();
        if (!empty($menus)) {
          foreach ($menus as $menu_name) {
            $menu_options[$menu_name] = $menu_name;
          }
          $menu_options[$menu['menu_name']] = $menu['menu_name'];
        }
        if (!empty($menu_options)) {
          variable_set('menu_options_' . $node_type->type, $menu_options);
        }
      }
    }
  }

  /**
   * Update paths when a site's purl prefix has changed.
   *
   * @param int $site_id
   *   Site ID.
   * @param string $original_path
   *   The original path, before the site's purl prefix was changed.
   * @param string $new_path
   *   The new path, after the site's purl prefix was changed.
   */
  public static function updatePaths($site_id, $original_path, $new_path) {
    // Get all nodes linked to this subsite.
    $result = db_select('site_node', 'n')->fields('n', array('nid'))->condition('sid', $site_id)->execute();
    $nodes = array();
    while ($node = $result->fetchObject()) {
      $nodes[] = 'node/' . $node->nid;
    }
    // We use this query for performance reasons. Db_query() is used because the
    // replace function can not be called with db_update.
    db_query("UPDATE url_alias SET alias = REPLACE(alias, :prev, :new) WHERE source IN (:nodes)", array(':prev' => $original_path . '/', ':new' => $new_path . '/', ':nodes' => $nodes));
  }

  /**
   * Prepare a context object.
   */
  public static function contextObjectPrepare() {
    $context = new stdClass;
    $context->disabled = FALSE;
    $context->api_version = 3;
    $context->tag = 'sites';
    $context->reactions = array();
    $context->condition_mode = 0;
    return $context;
  }

}
