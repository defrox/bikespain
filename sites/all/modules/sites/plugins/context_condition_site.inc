<?php

/**
 * @file
 * Context module condition plugin.
 */

/**
 * Expose sites as a context condition.
 */
class context_condition_site extends context_condition {
  function condition_values() {
    $options = array();
    $sites = SiteController::getSites();
    foreach ($sites as $site) {
      $options[$site->sid] = $site->title . ' (PURL prefix: ' . $site->purl_prefix . ')';
    }
    return $options;
  }

  function execute($sid) {
    foreach ($this->get_contexts($sid) as $context) {
      $this->condition_met($context, $sid);
    }
  }
}
