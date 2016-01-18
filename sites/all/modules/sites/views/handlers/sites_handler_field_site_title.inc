<?php

class sites_handler_field_site_title extends views_handler_field {

  function query() {
    $this->query->ensure_table('site_node');
    $this->field_alias = 'sites_title';
    $join = new views_join;
    $join->construct('sites', 'site_node', 'sid', 'sid');
    $this->query->add_table('sites', NULL, $join);
    $this->query->add_field('sites', 'title');
  }

  function render($values) {
    $title = $this->get_value($values);
    return $title;
  }
}