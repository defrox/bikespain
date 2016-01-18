<?php
// functions here
function opinno_bikespain_form_contact_site_form_alter(&$form, &$form_state, $form_id) {

// Define the order of the top level elements on the form (include those from contact_site_form().

$content_map = '<div id="bikespain_map"><iframe width="300" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.es/maps?f=q&amp;source=s_q&amp;hl=es&amp;geocode=&amp;q=Plaza+de+la+Villa+1,+28005+Madrid&amp;aq=&amp;sll=40.525282,-3.81603&amp;sspn=2.538584,5.410767&amp;ie=UTF8&amp;hq=&amp;hnear=Plaza+de+la+Villa,+1,+28005+Madrid&amp;ll=40.41541,-3.710201&amp;spn=0.009933,0.021136&amp;t=m&amp;z=14&amp;output=embed"></iframe><br /><small><a href="https://maps.google.es/maps?f=q&amp;source=embed&amp;hl=es&amp;geocode=&amp;q=Plaza+de+la+Villa+1,+28005+Madrid&amp;aq=&amp;sll=40.525282,-3.81603&amp;sspn=2.538584,5.410767&amp;ie=UTF8&amp;hq=&amp;hnear=Plaza+de+la+Villa,+1,+28005+Madrid&amp;ll=40.41541,-3.710201&amp;spn=0.009933,0.021136&amp;t=m&amp;z=14" style="color:#0000FF;text-align:left">'.t('Enlarge Map').'</a></small></div>';

$form['map'] = array(
  '#prefix' => $content_map, // Add markup before form item
);

$order = array('name', 'mail', 'subject', 'map' ,'cid', 'message', 'copy', 'actions');

// Order the elements by changing their #weight property.
foreach($order as $key => $field) {
$form[$field]['#weight'] = $key;
}

}

//function opinno_bikespain_url_inbound_alter(&$path, $original_path, $path_language) { //the var modified and returned is path
	//$contexts = context_active_contexts();
	/*
	dsm($contexts);
	dsm($path);
	dsm($original_path);
	dsm($path_language);	
	dsm(opinno_bikespain_get_current_location_contexts());
	*/
//}

//function opinno_bikespain_url_outbound_alter(&$path, &$options, $original_path) { //the var modified and returned is path
//	  $contexts = opinno_bikespain_get_current_location_contexts();	  
//	  global $language;
//	  //print_r($options);//die();
//	  if ($contexts && is_array($contexts) && count($contexts) > 0) {
//		  //print_r($contexts[0]);
//		  //$path_alias = drupal_lookup_path('alias',$path, $language->language); 
//		  $path_alias = drupal_get_path_alias($path, $language->language);
//		  //$path = $contexts[0] . '/' . $path_alias;
//	  }
//	  //dsm($contexts);
//	  //dsm($options);
//	  //dsm($original_path);
//	  //dsm($options);	
//	  //dsm(opinno_bikespain_get_current_location_contexts());
//}

function opinno_bikespain_get_current_location_contexts() {
  $contexts = array();
  if (function_exists('context_active_contexts') ) $contexts = context_active_contexts();
  //if (function_exists('context_get') ) $contexts = context_get();

  $locations = array();
  foreach ($contexts as $context) {
    $locations[] = $context->name;
    //$locations[$context->name] = $context;
  }

  return $locations;
}

if (!function_exists('purl_item_edit_validate')) {
	function purl_item_edit_validate($form, &$form_state) {
	  $form_state['values']['options']['purl'] = array();
	  if (!empty($form_state['values']['purl']['modifier'])) {
		list($provider, $id) = explode(':', $form_state['values']['purl']['modifier']);
		$form_state['values']['options']['purl']['provider'] = $provider;
		$form_state['values']['options']['purl']['id'] = $id;
	  }
	  if (!empty($form_state['values']['purl']['disabled'])) {
		$form_state['values']['options']['purl']['disabled'] = 1;
	  }
	  if (empty($form_state['values']['options']['purl'])) {
		unset($form_state['values']['options']['purl']);
	  }
	}

}

if (!function_exists('purl_item_edit_submit')) {
	function purl_item_edit_submit($form, &$form_state) {
	  menu_cache_clear($form_state['values']['menu_name']);
	}
}
