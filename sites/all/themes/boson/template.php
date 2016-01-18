<?php
// Set some variables
$contexto = false;

function boson_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {

    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

	$breadcrumb[] = drupal_get_title();
    $output .= '<ul class="crumbs"><li>' . t('You are here:') . '</li> '  . implode('  >  ', $breadcrumb) . '</ul>';
    return $output;
  }
}

function boson_preprocess_html(&$vars) {
  $bgklasa = theme_get_setting('theme_bg_pattern');
  $vars['classes_array'][] = drupal_html_class($bgklasa);
  drupal_add_css(path_to_theme() . '/css/main.css');
  //Add PinIt JS
  drupal_add_js('http://assets.pinterest.com/js/pinit.js');  
  // The Color Palette.
  $file = theme_get_setting('theme_color_palette');
  drupal_add_css(path_to_theme() . '/css/color-scheme/' . $file . '.css');
}

function boson_process_html(&$vars) {
	global $is_https;
  // Add context to main menu links
  $vars['page'] = str_replace('&amp;','&', $vars['page']);
  $vars['page'] = str_replace('&aacute;','á', $vars['page']);
  $vars['page'] = str_replace('&eacute;','é', $vars['page']);
  $vars['page'] = str_replace('&iacute;','í', $vars['page']);
  $vars['page'] = str_replace('&oacute;','ó', $vars['page']);
  $vars['page'] = str_replace('&uacute;','ú', $vars['page']);
  $vars['page'] = str_replace('&Aacute;','Á', $vars['page']);
  $vars['page'] = str_replace('&Eacute;','É', $vars['page']);
  $vars['page'] = str_replace('&Iacute;','Í', $vars['page']);
  $vars['page'] = str_replace('&Oacute;','Ó', $vars['page']);
  $vars['page'] = str_replace('&Uacute;','Ú', $vars['page']);
  $vars['page'] = str_replace('&acute;','´', $vars['page']);
  $vars['page'] = str_replace('&copy;','©', $vars['page']);
  $vars['page'] = str_replace('&ldquo;','"', $vars['page']);
  $vars['page'] = str_replace('&rdquo;','"', $vars['page']);
  $vars['page'] = str_replace('&euro;','€', $vars['page']);
  $vars['page'] = str_replace('&Ntilde;','Ñ', $vars['page']);
  $vars['page'] = str_replace('&ntilde;','ñ', $vars['page']);
  $vars['page'] = str_replace('&rsquo;',"'", $vars['page']);
	if ($is_https) $vars['page'] = str_replace('http://','https://', $vars['page']);
}
