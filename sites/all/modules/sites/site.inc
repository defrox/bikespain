<?php

/**
 * @file
 * site class.
 */

/**
 * Essentially this is a PURL record with some extra data attached.
 */
class Site {
  public $sid;
  public $purl_prefix;
  public $title;
  public $machine_name;
  public $name;
  public $front_page;
  public $menu_main_menu;
  public $menu_secondary_menu;
  public $theme;

  /**
   * Constructor of class Site().
   */
  function __construct($title = '', $purl_prefix = '', $name = '', $front_page = '', $mpl = '', $msl = '', $theme = '', $sid = NULL) {
    $this->title = $title;
    $this->purl_prefix = $purl_prefix;
    $this->name = $name;
    $this->front_page = $front_page;
    $this->menu_main_menu = $mpl;
    $this->menu_secondary_menu = $msl;
    $this->theme = $theme;
    $this->sid = $sid;
  }

  /**
   * Function for saving or updating information about sites.
   */
  function save() {
    if (empty($this->sid)) {
      drupal_write_record('sites', $this);
    }
    else {
      drupal_write_record('sites', $this, 'sid');
    }

    $modifier = array(
      'provider' => 'sites',
      'id' => $this->sid,
      'value' => $this->purl_prefix,
    );

    purl_save($modifier);
  }
}
