<?php


class sites_access_plugin extends views_plugin_access {

  function access($account) {
    return sites_view_access(array_filter($this->options['site']));
  }

  function get_access_callback() {
    return array('sites_view_access', array(array_filter($this->options['site'])));
  }

  function summary_title() {
    $count = count($this->options['site']);
    if ($count < 1) {
      return t('No site(s) selected');
    }
    elseif ($count > 1) {
      return t('Multiple sites');
    }
    else {
      $sid = reset($this->options['site']);
      $site = SiteController::getSite($sid);
      return check_plain($site->name);
    }
  }

  function option_definition() {
    $options = parent::option_definition();
    $options['site'] = array('default' => '');
    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    $sites = SiteController::getSites();
    $options = array();
    foreach($sites as $site) {
      $options[$site->sid] = $site->name;
    }

    $form['site'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Site'),
      '#default_value' => $this->options['site'],
      '#options' => $options,
      '#description' => t('Select the site for which to display this view'),
    );
  }

  function options_validate(&$form, &$form_state) {
    if (!array_filter($form_state['values']['access_options']['site'])) {
      form_error($form['site'], t('You must select at least one site if type is "by site"'));
    }
  }

  function options_submit(&$form, &$form_state) {
    $form_state['values']['access_options']['site'] = array_filter($form_state['values']['access_options']['site']);
  }
}
