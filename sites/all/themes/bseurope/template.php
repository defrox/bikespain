<?php
// Set some variables
$contexto = variable_get('europe_context','europa');

function bseurope_breadcrumb($variables) {

   $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {

    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

	$breadcrumb[] = drupal_get_title();
    $output .= '<ul class="crumbs"><li>' . t('You are here:') . '</li> '  . implode('  >  ', $breadcrumb) . '</ul>';
    return $output;
  }
}

function bseurope_preprocess_html(&$vars) {
	
  $bgklasa = theme_get_setting('theme_bg_pattern');
  $vars['classes_array'][] = drupal_html_class($bgklasa);
  drupal_add_css(path_to_theme() . '/css/main.css');
  //Add PinIt JS
  drupal_add_js('http://assets.pinterest.com/js/pinit.js');  
  // The Color Palette.
  $file = theme_get_setting('theme_color_palette');
  drupal_add_css(path_to_theme() . '/css/color-scheme/' . $file . '.css');
    
  // Add context to main menu links
  if (array_key_exists('superfish_3', $vars['page']['main_menu'])) {
	  //$vars['page']['main_menu']['superfish_3']["#markup"] = str_replace('/experiencias','/'.variable_get('europe_context','europa').'/experiencias', $vars['page']['main_menu']['superfish_3']["#markup"]);  
	  //$vars['page']['main_menu']['superfish_3']["#markup"] = str_replace('/experiences','/'.variable_get('europe_context','europa').'/highlights', $vars['page']['main_menu']['superfish_3']["#markup"]);  
	  //$vars['page']['main_menu']['superfish_3']["#markup"] = str_replace('/highlights','/'.variable_get('europe_context','europa').'/highlights', $vars['page']['main_menu']['superfish_3']["#markup"]);  
	  //$vars['page']['main_menu']['superfish_3']["#markup"] = str_replace('/contact','/'.variable_get('europe_context','europa').'/contact', $vars['page']['main_menu']['superfish_3']["#markup"]);  
  }
  if (array_key_exists('4465', $vars['page']['footer_thirdcolumn']['menu_menu-otros']))
  	$vars['page']['footer_thirdcolumn']['menu_menu-otros']['4465']["#href"] = str_replace('/ofertas-viajes','/'.variable_get('europe_context','europa').'/ofertas-viajes', $vars['page']['footer_thirdcolumn']['menu_menu-otros']['4465']["#href"]);  
}

function bseurope_process_html(&$vars) {
	global $is_https, $base_url;
	// Add context to main menu links
	//$vars['page'] = str_replace('/experiencias"','/'.variable_get('europe_context','europa').'/experiencias"', $vars['page']);  
	//$vars['page'] = str_replace('/experiences"','/'.variable_get('europe_context','europa').'/highlights"', $vars['page']);  
	//$vars['page'] = str_replace('/highlights"','/'.variable_get('europe_context','europa').'/highlights"', $vars['page']);  
	//$vars['page'] = str_replace('/highlights?','/'.variable_get('europe_context','europa').'/highlights?', $vars['page']);  
	$vars['page'] = str_replace('/ofertas-viajes"','/'.variable_get('europe_context','europa').'/ofertas-viajes"', $vars['page']);  
	$vars['page'] = str_replace('/lopd"','lopd"', $vars['page']);  
	$vars['page'] = str_replace('/lopd-europa"','lopd-europa"', $vars['page']);  
	//$vars['page'] = str_replace('/contact"','/'.variable_get('europe_context','europa').'/contact"', $vars['page']);  
	//Fix context on forms
	$vars['page'] = str_replace('/europa/europa/','/'.variable_get('europe_context','europa').'/', $vars['page']);  
	$vars['page'] = str_replace('/europe/europe/','/'.variable_get('europe_context','europa').'/', $vars['page']);
	//
	//Sustitución htmlentities
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
	if ($is_https) $vars['page'] = str_replace('http://'.$base_url,'https://'.$base_url, $vars['page']);
}

function bseurope_preprocess_page(&$vars) { 
	$vars['front_page'] = $vars['front_page'] . '/' . variable_get('europe_context','europa');	
}

function bseurope_menu_tree__menu_otros($variables){
    return "<ul class=\"b-list just-links m-dark\">\n" . $variables['tree'] ."</ul>\n";
}

function bseurope_menu_tree__menu_info($variables){
    return "<ul class=\"b-list just-links m-dark\">\n" . $variables['tree'] ."</ul>\n";
}
function bseurope_menu_tree__menu_info_footer_europa($variables){
    return "<ul class=\"b-list just-links m-dark\">\n" . $variables['tree'] ."</ul>\n";
}

function bseurope_form_alter(&$form, &$form_state, $form_id){
        //if it is the exposed filter view
        if (($form_id == 'views_exposed_form') && 
        //for the view we care about
        ($form_state['view']->name == 'experiencias' || $form_state['view']->name == 'highlights')
        //and the view display we care about
        //&& ($form_state['view']->current_display == 'page' || $form_state['view']->current_display == 'page_3')
		){
            //get the childern of the term
            if ($child_terms = taxonomy_get_children(38)){
                //only care about the tids
                $child_terms_tids = $child_terms_infos = array();
                foreach ($child_terms as $child){
                    $child_terms_tids[] = $child->tid;
                }
                foreach ($form["#info"] as $info){
					//var_dump($info);
                    $child_terms_infos[] = $info["value"];
                }

                //for the exposed filter, alter the available options
                if (isset($form['field_lugar_tid_i18n']['#options'])):
					foreach ($form['field_lugar_tid_i18n']['#options'] as $tid => $term){
						//is is a child term?
						if (in_array($tid,$child_terms_tids)){
							//leave it
						}else{
							//remove it
							unset($form['field_lugar_tid_i18n']['#options'][$tid]);
						}
					}
				endif;
                //for the exposed filter, alter the available options
                if (isset($form['field_lugar1_tid_i18n']['#options'])):
					foreach ($form['field_lugar1_tid_i18n']['#options'] as $tid => $term){
						//is is a child term?
						if (in_array($tid,$child_terms_tids)){
							//leave it
						}else{
							//remove it
							unset($form['field_lugar1_tid_i18n']['#options'][$tid]);
						}
					}
				endif;
            }
        }
    }
