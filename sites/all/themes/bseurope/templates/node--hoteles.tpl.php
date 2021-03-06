<?php
global $language;
/**
 * Load a map from multiple node ids with the appropriate markers
 * 
 * @param array $nids The node ids of the nodes you want to get displayed on the map.
 * @param array $getlocations_extra_settings Some extra settings you want to override the default values with.
 * @see https:// drupal.org/node/2082137
 * @todo Patch for getlocations_setlocations() to accept settings which override the defaults, or figure out an alternative solution.
 */
function getlocations_nodemap_multiple($typemarker, $title, $nid, $locations = array(), &$getlocations_extra_settings = array()) {

	$latlons = array();
	$minmaxes = array('minlat' => 0, 'minlon' => 0, 'maxlat' => 0, 'maxlon' => 0);
	$count_nids = 0;
	// override default settings
	$getlocations_settings = array_merge(getlocations_defaults(), $getlocations_extra_settings);
	$marker = $getlocations_settings['node_map_marker'];
	$typemarkers = getlocations_get_markertypes('node');

    if (isset($typemarkers[$typemarker]) && $typemarkers[$typemarker]) {
      $marker = $typemarkers[$typemarker];
    }
	
    if (count($locations)) {
		// we should loop over them and dump bummers with no lat/lon
		foreach ($locations AS $key => $location) {
			$latlon = getlocations_latlon_check($location['latitude'] . ',' . $location['longitude']);
			if ($latlon) {
				$minmaxes = getlocations_do_minmaxes($count_nids, $location, $minmaxes);

				if (!isset($location['key'])) {
					$location['key'] = '';
				}else {
					if ($location['key'] == 'nid') {
						$location['lid'] = $nid;
					}elseif ($location['key'] == 'vid') {
						// not vid?
						$location['lid'] = $nid;
					}
				}
				
				// term markers
				$marker = getlocations_get_term_marker($nid, $marker);
				
				// per location marker
				if (isset($location['marker']) && ! empty($location['marker'])) {
					$marker = $location['marker'];
				}
				
				$count_nids++;
				
				$name = htmlspecialchars_decode($location['name'] ? strip_tags($location['name']) : strip_tags($title), ENT_QUOTES);
				$latlons[] = array($location['latitude'], $location['longitude'], $location['lid'], $name, $marker, $location['key']);
				$getlocations_extra_settings['latlong'] = $location['latitude'].','.$location['longitude'];
			}
		}
	}
	
	if ($count_nids < 2 ) {
		$minmaxes = NULL;
	}
	
	$getlocations_extra_settings['getlocations_js_weight'] += 10;
	$mapid = getlocations_setup_map($getlocations_extra_settings);
	
	getlocations_js_settings_do($getlocations_extra_settings, $latlons, $minmaxes, $mapid, FALSE, $getlocations_extra_settings['extcontrol']);
	// get the html for the map and whatever else, buttons etc
	$map = theme('getlocations_show', array(
		'width' => $getlocations_extra_settings['width'] ,
		'height' => $getlocations_extra_settings['height'] ,
		'defaults' => $getlocations_extra_settings,
		'mapid' => $mapid,
		'type' => '',
		'node' => '',
		'weight' => $getlocations_extra_settings['getlocations_js_weight'])
	);
	//return getlocations_setlocations($latlons, $minmaxes);
	//die(print( $mapid));
	return $map;
}

$getlocations_extra_settings = getlocations_defaults();
$getlocations_extra_settings['fullscreen'] = 0;
$getlocations_extra_settings['streetview_show'] = 0;
$getlocations_extra_settings['maptype'] = 'Map';
$getlocations_extra_settings['width'] = '300px';
$getlocations_extra_settings['height'] = '220px';
$getlocations_extra_settings['zoom'] = 14;
$getlocations_extra_settings['visual_refresh'] = 0;

$map = getlocations_nodemap_multiple($node->type, $node->title, $node->nid, $node->locations, $getlocations_extra_settings);
var_dump($getlocations_extra_settings);


print '<div class="row">';
//Columna izquierda 2/3
print '<div class="row-item col-2_3">';
	if (!empty($node->body)) {
		print '<div>'.$node->body['und'][0]['value'].'</div>';
	}	
print '</div>';


//Columna derecha 1/3
print '<div class="row-item col-1_3">';

if (!empty($node->field_estrellas)) {
	$stars = $node->field_estrellas['und'][0]['value'];
	print '<h4 class="lined">'.t('Category').'</h4>';
	if ($stars == 0) {
		print t('Not qualified');
	}
	print '<center><div class="five-rate" style="width: 100%; height: 30px;">';
	for ($i=1; $i <= 5; $i++) { 
		if ($i > $stars) {
			print '<div class="star" style="background: url(/sites/all/modules/fivestar/widgets/bikespain/star.gif) no-repeat 0 -4px; width: 21px; height:30px; float:left"></div>';
		}
		else{
			print '<div class="star" style="background: url(/sites/all/modules/fivestar/widgets/bikespain/star.gif) no-repeat 0 -52px; width: 21px; height:30px; float:left"></div>';
		}
	}
	print '</div></center>';

}


if (!empty($node->field_hoteles_imagen)) {
	print '<div class="hoteles-image">';
	print '<h4 class="lined">'.t('Images').'</h4>';
		$url_img = file_create_url($node->field_hoteles_imagen['und'][0]['uri']);
print '<img src="'.$url_img.'" style="width: 300px"/>';
print '</div>';
}
if (!empty($node->field_hoteles_p_gina_web)) {
	print '<div>';
	print '<center><a target="_blank" href="'.$node->field_hoteles_p_gina_web['und'][0]['value'].'">'.$node->field_hoteles_p_gina_web['und'][0]['value'].'</a></center>';
	print '</div>';
}

if (!empty($node->field_lugar)) {
	print '<div class="lined" style="margin-top: 8px;"><b>'.t('Destination: ').'</b>';
	print $node->field_lugar['und'][0]['taxonomy_term']->name.'</div>';
}

if (!empty($node->field_estilo)) {
	print '<div>';
	print '<b>'.t('Style: ').'</b>'.$node->field_estilo['und'][0]['value'];
	print '</div>';
}

if (!empty($node->field_atmosfera)) {
	print '<div>';
	print '<b>'.t('Atmosphere: ').'</b>'.$node->field_atmosfera['und'][0]['value'];
	print '</div>';
}

if (!empty($node->field_escenario)) {
	print '<div>';
	print '<b>'.t('Scenario: ').'</b>'.$node->field_escenario['und'][0]['value'];
	print '</div>';
}

if (!empty($node->locations)){
	print '<h4 class="lined" style="margin-top: 8px;">'.t('Location').'</h4>';
	print '<div id="mapRefrescar">'.$node->locations[0]['street'].$map.'</div>';
}
print '</div>';
print '</div>';
