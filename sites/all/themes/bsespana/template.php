<?php
// Set some variables
$contexto = variable_get('spain_context','spain');

function bsespana_breadcrumb($variables) {

   $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {

    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

	$breadcrumb[] = drupal_get_title();
    $output .= '<ul class="crumbs"><li>' . t('You are here:') . '</li> '  . implode('  >  ', $breadcrumb) . '</ul>';
    return $output;
  }
}


function bsespana_preprocess_html(&$vars) {
	
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
	  //$vars['page']['main_menu']['superfish_3']["#markup"] = str_replace('/experiencias','/'.variable_get('spain_context','spain').'/experiencias', $vars['page']['main_menu']['superfish_3']["#markup"]);  
	  //$vars['page']['main_menu']['superfish_3']["#markup"] = str_replace('/experiences','/'.variable_get('spain_context','spain').'/experiences', $vars['page']['main_menu']['superfish_3']["#markup"]);  
	  //$vars['page']['main_menu']['superfish_3']["#markup"] = str_replace('/contact','/'.variable_get('spain_context','spain').'/contact', $vars['page']['main_menu']['superfish_3']["#markup"]);  
  }
  if (@array_key_exists('4765', $vars['page']['footer_thirdcolumn']['menu_menu-otros-espa-a']))
  	$vars['page']['footer_thirdcolumn']['menu_menu-otros-espa-a']['4765']["#href"] = str_replace('/ofertas','/'.variable_get('spain_context','spain').'/ofertas', $vars['page']['footer_thirdcolumn']['menu_menu-otros-espa-a']['4765']["#href"]);  
  if (@array_key_exists('4766', $vars['page']['footer_thirdcolumn']['menu_menu-otros-espa-a']))
  	$vars['page']['footer_thirdcolumn']['menu_menu-otros-espa-a']['4766']["#href"] = str_replace('/offers','/'.variable_get('spain_context','spain').'/offers', $vars['page']['footer_thirdcolumn']['menu_menu-otros-espa-a']['4766']["#href"]);  
}

function bsespana_process_html(&$vars) {
	global $is_https;
  // Add context to main menu links
	//$vars['page'] = str_replace('/experiencias"','/'.variable_get('spain_context','spain').'/experiencias"', $vars['page']);  
	//$vars['page'] = str_replace('/experiences"','/'.variable_get('spain_context','spain').'/experiences"', $vars['page']);  
	//$vars['page'] = str_replace('/highlights"','/'.variable_get('spain_context','spain').'/highlights"', $vars['page']);  
	//$vars['page'] = str_replace('/highlights?','/'.variable_get('spain_context','spain').'/highlights?', $vars['page']);  
	//$vars['page'] = str_replace('/ofertas"','/'.variable_get('spain_context','spain').'/ofertas"', $vars['page']);  
	//$vars['page'] = str_replace('/offers"','/'.variable_get('spain_context','spain').'/offers"', $vars['page']);  
	$vars['page'] = str_replace('/lopd"','lopd"', $vars['page']);  
	$vars['page'] = str_replace('/lopd-europa"','lopd-europa"', $vars['page']);  
	//$vars['page'] = str_replace('/contact"','/'.variable_get('spain_context','spain').'/contact"', $vars['page']);  
	//Fix context on forms
	$vars['page'] = str_replace('/espana/espana/"','/'.variable_get('spain_context','spain').'/"', $vars['page']);  
	$vars['page'] = str_replace('/spain/spain/"','/'.variable_get('spain_context','spain').'/"', $vars['page']);
	//
	//Sustitución de htmlentities
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

function bsespana_preprocess_page(&$vars) { 
	$vars['front_page'] = $vars['front_page'] . '/' . variable_get('spain_context','spain');
/*	if (isset($vars['page']['#type'])) { 
		echo $vars['node']['#type'];
		$vars['theme_hook_suggestions'][] = 'page__' . $vars['page']['#type']; 
	} 
*/
}

/**
* Implements hook_preprocess_node().
*/
function bsespana_preprocess_node(&$vars) {
 
  // Add css class "node--NODETYPE--VIEWMODE" to nodes
  //$vars['classes_array'][] = 'node--' . $vars['type'] . '--' . $vars['view_mode'];
 
  // Make "node--NODETYPE--VIEWMODE.tpl.php" templates available for nodes
  //$vars['theme_hook_suggestions'][] = 'node__' . $vars['type'] . '__' . $vars['view_mode'];

}

function bsespana_menu_tree__menu_otros($variables){
    return "<ul class=\"b-list just-links m-dark\">\n" . $variables['tree'] ."</ul>\n";
}

function bsespana_menu_tree__menu_info($variables){
	//var_dump($variables);
    return "<ul class=\"b-list just-links m-dark\">\n" . $variables['tree'] ."</ul>\n";
}

function bsespana_menu_tree__menu_otros_espa_a($variables){
    return "<ul class=\"b-list just-links m-dark\">\n" . $variables['tree'] ."</ul>\n";
}

function bsespana_menu_tree__men_principal_espa_a($variables){
	if ( $variables['element']['#href'] == 'highlights' || $variables['element']['#href'] == 'experiences' || $variables['element']['#href'] == 'experiencias' ) 
		return '<li' . drupal_attributes($variables['element']['#attributes']) . '>' . l($variables['element']['#title'], variable_get('spain_context','spain') . '/' . drupal_get_path_alias( $variables['element']['#href'])) . "</li>\n";	
	else
		return '<li' . drupal_attributes($variables['element']['#attributes']) . '>' . l($variables['element']['#title'], drupal_get_path_alias( $variables['element']['#href'])) . "</li>\n";	
}

function bsespana_menu_link_alter(&$item) {
	//var_dump($item);
}

function bsespana_menu_link(array $variables) {
	if ( $variables['element']['#href'] == 'highlights' || $variables['element']['#href'] == 'experiences' || $variables['element']['#href'] == 'experiencias' ) 
		return '<li' . drupal_attributes($variables['element']['#attributes']) . '>' . l($variables['element']['#title'], variable_get('spain_context','spain') . '/' . drupal_get_path_alias( $variables['element']['#href'])) . "</li>\n";	
	else
		return '<li' . drupal_attributes($variables['element']['#attributes']) . '>' . l($variables['element']['#title'], drupal_get_path_alias( $variables['element']['#href'])) . "</li>\n";	
}

function bsespana_form_alter(&$form, &$form_state, $form_id){
        //if it is the exposed filter view
        if (($form_id == 'views_exposed_form') && 
        //for the view we care about
        ($form_state['view']->name == 'experiencias' || $form_state['view']->name == 'highlights')
        //and the view display we care about
        //&& ($form_state['view']->current_display == 'page' || $form_state['view']->current_display == 'page_3')
		){
            //get the childern of the term
            if ($child_terms = taxonomy_get_children(35)){
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
            }
        }
    }
