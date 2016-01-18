<?php
global $language;

function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = $v['node'][$subkey];
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}

function maquetaCajaRelacionados(&$out, &$tiposProductos, &$prod, &$i, &$titulo, $tipo=0){
	/* 0=> Normal, 1=> Ofertas especiales, 2=> Sugerencias */
	$out .= ($i==1)? '<div class="resultados_titulo_general"><h3 class="lined">'.$titulo.'</h3></div><div class="resultados_productox resultados_producto_primer_resultadox col-1-3 row-item">' : '<div class="resultados_productox col-1-3 row-item">' ;

	$url = url('node/'. $prod->nid);

	global $language;
	$field_img = GetValueByLangOrUnd($prod, $language, 'field_imagen', 'uri');
	$texto_special_offer = GetValueByLangOrUnd($prod, $language, 'field_special_offer_text');
	$special_offer = GetValueByLangOrUnd($prod, $language, 'field_special_offer');
	if($special_offer == 0 || $tipo != 1){
		$texto_special_offer = '';
	}
	$image = theme('image_style',array('style_name' => '3-columnas', 'path' => $field_img)); // esta si usa image-styles
	if($tipo == 1){
		$out .= '<a class="resultados_imagenx work-image" href="'.$url.'">' . $image . '<div class="texto_oferta_especial_sobre_imagen specialoffer">'.$texto_special_offer.'</div><div class="link-overlay icon-search"></div></a>';
	}else{
		$out .= '<a class="resultados_imagenx work-image caja_altura_180" href="'.$url.'">' . $image . '<div class="link-overlay icon-search"></div></a>';
	}
	$out .= '<a class="resultados_titulo" href="'.$url.'">'.$prod->title.'</a>';
	
	$field_tipo = GetValueByLangOrUnd($prod, $language, 'field_tipo_de_producto', 'tid');
	$texto_promocional = GetValueByLangOrUnd($prod, $language, 'field_texto_promocional');
	$tipo = (isset($field_tipo))? $field_tipo : 0;
	$tipo_producto = (($tipo==0) || !array_key_exists($tipo, $tiposProductos) ? '' : $tiposProductos[$tipo]);
	$tipo_producto = str_replace('-', '', $tipo_producto);
	//selección de ruta según tipo viaje
	if (0==(strcmp ($tipo_producto, 'Autoguiados'))){
		$tipo_ruta_producto = "trip/autoguiados";
	}
	elseif (0==(strcmp ($tipo_producto, 'España Auténtica'))){
		$tipo_ruta_producto = "trip/españa-auténtica";
	}
	elseif(0==(strcmp ($tipo_producto, 'España de Lujo'))){
		$tipo_ruta_producto = "trip/españa-lujo";
	}
	elseif(0==(strcmp ($tipo_producto, 'La Vuelta'))){
		$tipo_ruta_producto = "trip/vuelta";
	}
	elseif(0==(strcmp ($tipo_producto, 'Ciclismo de Carretera'))){
		$tipo_ruta_producto = "trip/ciclismo-carretera";
	}
	elseif(0==(strcmp ($tipo_producto, 'Ebike'))){
		$tipo_ruta_producto = "trip/e-bike";
	}
	$out .= '<a class="resultados_tipo_contenido" href="'.$tipo_ruta_producto.'">'. $tipo_producto .'</a>';
	$out .= '<div class="resultados_tipo_contenido">'. $texto_promocional .'</div>';
	
	$out .= '</div>';
	$i .= ($i==3)? 1 : $i+1;
}
function _taxonomy_options($machine_name, $default=''){
		$vocabulary = taxonomy_vocabulary_machine_name_load($machine_name);
		$tree = taxonomy_get_tree($vocabulary->vid);
		if(!empty($default))
			$options[0] = $default;
		else
			$options = array();
		foreach($tree as $item){
			$options[$item->tid] = str_repeat('-', $item->depth) . $item->name;
		}
		return $options;
	}
function getContentFromBBDD2($trip, $month, $limit=0){
	switch($trip){
		case 3:
			$tabla = 'productos_premium';
			$trip = 0; //No tiene campo tipo de producto
			$campoLugar = 'field_premium_lugar';
		break;
		default:
			$tabla = 'productos';
			$campoLugar = 'field_lugar';
		break;
	}
	//var_dump(OpinnoBikespainHelper::getCurrentLang());
	$dfx_lang = OpinnoBikespainHelper::getCurrentLang() == 'es' ? 'en' : 'es';
	$language = OpinnoBikespainHelper::getCurrentLang();	
	$query = new EntityFieldQuery();
	$query->entityCondition('entity_type', 'node')
		->entityCondition('bundle', 'hoteles')
		->propertyCondition('status', 1)
		->propertyCondition('language', $dfx_lang)->addTag('efq_debug')->range(0, 50);
	if($trip>0)
		$query->fieldCondition('field_tipo_de_producto', 'tid', array($trip));
	
	$result = $query->execute();
	$res = array();
	if(isset($result['node'])){
		$news_items_nids = array_keys($result['node']);
		$news_items = entity_load('node', $news_items_nids);
		if($month>0)
			foreach($news_items as $producto){
				$field_dates = GetListByLangOrUnd($producto, $language, 'field_dates');
				foreach($field_dates as $value){
					if(fechaDisponible($value, $month)){
						$res[] = $producto;
						break;
					}
				}
			}
		else
			$res = $news_items;
	}
	return $res;
}
function GetValueByLangOrUnd($node, $language, $var, $value='value'){
	$valor = '';
	if(is_object($node) && property_exists($node, $var)){
		$a = $node->$var;
		if(!empty($a)){
			if(is_object($language) && array_key_exists($language->language, $a) && array_key_exists(0, $a[$language->language]) && array_key_exists($value, $a[$language->language][0]))
				$valor = $a[$language->language][0][$value];
			if(empty($valor) && array_key_exists('und', $a) && array_key_exists(0, $a['und']) && array_key_exists($value, $a['und'][0])){
				$valor = $a['und'][0][$value];
			}
			if(empty($valor)){
				$valor = @$a[0][$value];
			}
		}
	}
	return $valor;
}
function GetListByLangOrUnd($node, $language, $var){
	$valor = '';
	if(is_object($node) && property_exists($node, $var)){
		$a = $node->$var;
		if(!empty($a)){
			if(is_object($language) && array_key_exists($language->language, $a))
				$valor = $a[$language->language];
			if(empty($valor) && array_key_exists('und', $a)){
				$valor = $a['und'];
			}
			if(empty($valor))
				$valor = $a;
		}
	}
	return $valor;
}
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
$getlocations_extra_settings['width'] = '740px';
$getlocations_extra_settings['height'] = '500px';
$getlocations_extra_settings['zoom'] = 14;
$getlocations_extra_settings['visual_refresh'] = 0;

$map = getlocations_nodemap_multiple($node->type, $node->title, $node->nid, $node->locations, $getlocations_extra_settings);

$cuerpo = GetValueByLangOrUnd($node, $language, 'field_overview');
//$highlights = GetValueByLangOrUnd($node, $language, 'field_highlights');

$hoteles = GetListByLangOrUnd($node, $language, 'field_hoteles');
$restaurantes = GetListByLangOrUnd($node, $language, 'field_restaurantes');
$slide = GetListByLangOrUnd($node, $language, 'field_slider');

$precio = GetValueByLangOrUnd($node, $language, 'field_precio');
$precio_oferta = GetValueByLangOrUnd($node, $language, 'field_precio_oferta');
$incluido = GetValueByLangOrUnd($node, $language, 'field_que_esta_incluido');
$noincluido = GetValueByLangOrUnd($node, $language, 'field_no_incluido');
$opciones = GetValueByLangOrUnd($node, $language, 'field_opciones');
$nuestrasbicicletas = GetValueByLangOrUnd($node, $language, 'field_nuestras_bicicletas');
$destacados = GetValueByLangOrUnd($node, $language, 'field_destacados');
$info_rutas = GetValueByLangOrUnd($node, $language, 'field_info_rutas');

//$hoteles = subval_sort($hoteles,'title');

print '<div class="b-tabs">';
    print '<ul class="tabs-nav">';
		//$tabActivaOverview = (empty($info_rutas))? ' class="active"' : '';
		//$tabActivaOverviewC = (empty($info_rutas))? ' active' : '';
		if(!empty($cuerpo)) print '<li class="active"><span>'.t('OVERVIEW').'</span></li>';
		//if(!empty($highlights)) print '<li><span>'.t('Highlights').'</span></li>';
		if(!empty($info_rutas)) print '<li class="maptab"><span>'.t('ITINERARY').'</span></li>';
		//if(!empty($node->locations)) print '<li class="maptab active"><span>'.t('ITINERARY').'</span></li>';
		if(!empty($hoteles)) print '<li><span>'.t('HOTELS').'</span></li>';
		if(!empty($restaurantes)) print '<li><span>'.t('RESTAURANTS').'</span></li>';
		if(!empty($slide)) print '<li><span>'.t('PHOTOS').'</span></li>';
		if(!empty($precio) || !empty($incluido) || !empty($noincluido) || !empty($opciones) || !empty($nuestrasbicicletas) || !empty($destacados)) print '<li><span>'.t('+ INFO').'</span></li>';
	print '</ul>';
	print '<div class="tabs-content">';
		if(!empty($cuerpo)) print '<div class="tab active">'.$cuerpo.'</div>';
		//if(!empty($highlights)) print '<div class="tab">'.$highlights.'</div>';
		/*if(!empty($node->locations)){

			print '<div class="tab active">';
			//print '<div id="mapRefrescar" class="tab active">'.$node->locations[0]['street'].$map.'</div>';
			
			print '<div id="mapRefrescar">'.$node->locations[0]['street'].$map.'</div>';
			
			print '<div class="b-accordion">';
			foreach ($node->locations as $acc) {
				  print '<div class="b-spoiler m-alt">';
				  print '<div class="spoiler-title"><span>'.$acc['name'].'</span></div>';
				  print '<div class="spoiler-content"><p>'.$acc['additional'].'</p></div>';
				  print '</div>';
			} 
			print '</div>';
			print '</div>';

		}*/

		if(!empty($info_rutas)){
			print '<div class="tab">';
			//print '<div id="mapRefrescar" class="tab active">'.$node->locations[0]['street'].$map.'</div>';
			//print '<div id="mapRefrescar">'.$node->locations[0]['street'].$map.'</div>';
				
				if (!empty($node->field_url_mapa))
				{
					//Map by url 
					if (strpos($node->field_url_mapa['und'][0]['value'],'<iframe') === 0){
						print $node->field_url_mapa['und'][0]['value'];
					}
					else
					{
						print '<iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$node->field_url_mapa['und'][0]['value'].'" ></iframe>';
					}
				} else {
					//Image map by url
					$url_img_mapa = file_create_url($node->field_mapa_imagen['und'][0]['uri']);
					//print '<img src="'.$url_img_mapa.'" width="100%" height="300px" />';
					print '<img src="'.$url_img_mapa.'" alt="Mapa" width="100%" />';
				}
				
				$array_rutas = field_view_field('node', $node, 'field_info_rutas');
				
				$i = 0;
				print '<div class="b-accordion">';
				
				foreach ($array_rutas['#object']->field_info_rutas['und'] as $key => $value) {
					$array_interno = $array_rutas[$i]['entity']['field_collection_item'];
					foreach ($array_interno as $int => $value) {
						$titulo = $array_interno[$int]['field_titulo_ruta'][0]['#markup'];
						@$descripcion_corta = $array_interno[$int]['field_descripci_n_corta_ruta'][0]['#markup'];
						@$descripcion_larga = $array_interno[$int]['field_descripci_n_larga_ruta'][0]['#markup'];

						print '<div class="b-spoiler m-alt">';
				  		print '<div class="spoiler-title"><span>'.$titulo.'</span></div>';
					  	print '<div class="spoiler-content"><p style="font-weight:bold;">'.$descripcion_corta.'</p><p>'.$descripcion_larga.'</p>';
					  	print '</div>';
					  	print '</div>';
					}
					$i = $i + 1;
				}
				print '</div>';
			print '</div>';
		}


		if(!empty($hoteles)){
			print '<div class="tab">';
				print '<div class="hoteles">';
					foreach($hoteles as $fi){
						$field_img = GetValueByLangOrUnd($fi['node'], $language, 'field_hoteles_imagen', 'uri');
						$url = url('node/'. $fi['node']->nid);
						$image = theme('image_style',array('style_name' => '3-columnas', 'path' => $field_img)); // esta si usa image-styles
						$urlWeb = GetValueByLangOrUnd($fi['node'], $language, 'field_hoteles_p_gina_web');
						if(strpos($urlWeb, 'http://')===false && strpos($urlWeb, 'https://')===false) $urlWeb='http://'.$urlWeb;
						print '<div class="views-row-odd">
							<div class="post-preview preview-medium just-links m-dark">
								<div class="post-image-wrap">
									<a class="work-image" rel="prettyPhoto[iframes]" href="'.$url.'?iframe=true&width=100%&height=100%">'.$image.'<div class="link-overlay icon-search"></div></a>
									
								</div>
								<div class="textcenter">
								<b class="titulo">'.GetValueByLangOrUnd($fi['node'], $language, 'field_hoteles_nombre').' '.str_repeat('*',GetValueByLangOrUnd($fi['node'], $language, 'field_estrellas')).'</b>
								<div class="post-imagex">' . GetValueByLangOrUnd($fi['node'], $language, 'body') . l(GetValueByLangOrUnd($fi['node'], $language, 'field_hoteles_p_gina_web'),GetValueByLangOrUnd($fi['node'], $language, 'field_hoteles_p_gina_web'), array('attributes' => array('target' => '_blank'))) . '</div>
								</div>
							</div>
						</div>';		
						/*print '<div class="row-item col-1_3 col-1_3_size2">
							<div class="work">
								<div><b><a href="'.$fi['node']->field_hoteles_p_gina_web['und'][0]['value'].'">'.$fi['node']->field_hoteles_nombre['und'][0]['value'].'</a></b></div>
								<a class="work-image" href="#">
									<img alt="" src="http://dev-bike-spain.gotpantheon.com/sites/default/files/P1120967_0.jpg">
								</a>
								<div class="textcenter">'.$fi['node']->body['und'][0]['value'].'</div>
							</div>
						</div>';*/
					}
				print '</div>';
			$opt = explode(',', $getlocations_extra_settings['latlong']);
			print '</div>';
		}
		if(!empty($restaurantes)){
			print '<div class="tab">';
				print '<div>';
					foreach($restaurantes as $fi){
						if(array_key_exists('node', $fi)){
							$url = url('node/'. $fi['node']->nid);		
							$tituloRestau = GetValueByLangOrUnd($fi['node'], $language, 'field_nombre');
							$field_img = GetValueByLangOrUnd($fi['node'], $language, 'field_restaurantes_imagen', 'uri');
							$image = theme('image_style',array('style_name' => '3-columnas', 'path' => $field_img)); // esta si usa image-styles
							$urlWeb = GetValueByLangOrUnd($fi['node'], $language, 'field_p_gina_web');
							if(strpos($urlWeb, 'http://')===false && strpos($urlWeb, 'https://')===false) $urlWeb='http://'.$urlWeb;
							print '<div class="views-row-odd">
								<div class="post-preview preview-medium just-links m-dark">
									<div class="post-image-wrap">
										<a class="work-image" rel="prettyPhoto[iframes]" href="'.$url.'?iframe=true&width=100%&height=100%">'.$image.'<div class="link-overlay icon-search"></div></a>
									</div>
									<div class="textcenter">
									<b>'.$tituloRestau.'</b>
									<div class="post-image">'.GetValueByLangOrUnd($fi['node'], $language, 'field_descripci_n').'</div>
									</div>
								</div>
							</div>';
						}
					}
				print '</div>';
			$opt = explode(',', $getlocations_extra_settings['latlong']);
			print '
			<script>jQuery(document).ready(function() {
				jQuery("ul.tabs-nav > li.maptab").click(function() {
					key_1.setCenter(new google.maps.LatLng("'.$getlocations_extra_settings['latlong'].'"));
					google.maps.event.trigger(key_1, "resize"); 
					//google.maps.event.trigger(getlocations_map_canvas_key_1, "resize");
				});
			});
			jQuery(window).load(function() {
				jQuery("ul.tabs-nav > li:first-child").click();				
			});</script></div>';
		}
		
		
		if(!empty($slide[0]['uri'])){
		
			/*Gallery*/
			print '<div class="tab">';
			//print '<div class="b-gallery">';
			print '<div style="text-align: center; margin: 0 auto">';
			foreach ($slide as $img){
				print '<div class="img-wrap-bis">';
				print '<div class="img-wrap-into">';
				print '<a class="work-image" rel="prettyPhoto[gallery]" href="'.file_create_url($img['uri']).'">';
				$image = theme('image_style',array('style_name' => '175x98', 'path' => $img['uri'])); // esta si usa image-styles
				//print '<img src="'.file_create_url($img['uri']).'" alt>';
				print $image;
				print '<div class="link-overlay icon-search">';
				print '</div>
					</a>
				</div>
				</div>';						
			}
			print '</div>';
			print '</div>';
			//flexslider_add('slide_producto', 'default');
		}
		if(!empty($precio) || !empty($incluido) || !empty($noincluido) || !empty($opciones) || !empty($nuestrasbicicletas) || !empty($destacados)){
			print '<div class="tab">';

				/*if (!empty($precio_oferta)){
					print '<div class="tab-others-campos"><b>'.t('Price').': </b><span class="precio-lined">'.$precio.'€</span><span class="oferta">'.'  '.$precio_oferta.'€</span></div>';
				}
				else{
					print '<div class="tab-others-campos"><b>'.t('Price').': </b>'.$precio.'€</div>';
				}*/

				//if(!empty($precio)) print '<div class="tab-others-campos"><b>'.t('Price').': </b>'.$precio.'€</div>';
				if(!empty($incluido)) print '<div class="tab-others-campos"><b>'.t("What's Included?").': </b>'.$incluido.'</div>';
				if(!empty($noincluido)) print '<div class="tab-others-campos"><b>'.t('Not included').':</b>'.$noincluido.'</div>';
				if(!empty($opciones)) print '<div class="tab-others-campos"><b>'.t('Options').': </b>'.$opciones.'</div>';
				if(!empty($nuestrasbicicletas)) print '<div class="tab-others-campos"><b>'.t('Our bikes').': </b> '.$nuestrasbicicletas.'</div>';
				if(!empty($destacados)) print '<div class="tab-others-campos"><b>'.t('Highlights').': </b>'.$destacados.'</div>';
			print '</div>';
		}
    print '</div>';
print '</div>';

/*
 *Muestra botones de redes sociales en producto

$data_options = sharethis_get_options_array();
$path = isset($_GET['q']) ? $_GET['q'] : '<front>';
$mPath = url($_GET['q'], array('absolute' => TRUE));
$mTitle = drupal_get_title();
print sharethis_get_button_HTML($data_options, $mPath, $mTitle);
*/
/*
print sharethis_node_view($node, 'producto', $language->language);
print 'prueba de salida';
var_dump ($node);*/
/*

$relacionados = GetListByLangOrUnd($node, $language, 'field_productos_relacionados');
if(!empty($relacionados)){

		$tiposProductos = _taxonomy_options('tipo_producto', t('Search by trip - Any'));
		$out = '<div class="row caja_min_100porc">';
		$i = 1;
		foreach($relacionados as $fi){
			maquetaCajaRelacionados($out, $tiposProductos, $fi['node'], $i, t('Related'), 3);
		}
		print $out;
	print '</div>';
}
*/




?> 
<?php

 
 if(!$title) $title = $content['title_field']['#title'];
 if($teaser){ ?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> <?php print "iteration-$id"; ?>"<?php print $attributes; ?>>
  <?php if ($submitted && $teaser): ?>
  <?php endif; ?>
  <div class="meta">
    <div class="content clearfix row-fluid">
      <div class="row-fluid">
        <div class="product-images pull-left span4">
          <?php /* if($title) {?>
          <h3 class="product-title"><?php print $title; ?></h3>
          <span class="product-title-border"></span>
          <?php } */ ?>
          <?php print render($content['field_fotos']); ?> </div>
        <?php
	      //print_r($content);
	      hide($content['comments']);
	      hide($content['links']);
		  hide($content['field_tabs']);
		  hide($content['body']);
		  hide($content['field_indicaciones']);
		  hide($content['field_descripcion_tecnica']);

		//  hide($content['add_to_cart']);
		//  hide($content['taxonomy_catalog']);
		//  hide($content['sell_price']);
		  ?>
        <div class="product-desc pull-right span8">
          <div class="product-descriptions">
            <?php
			        /*print render($content['field_descripcion_tecnica']);
			 	 	print render($content['field_indicaciones']);
			 	 	print render($content['field_archivos']);
			      	print render($content['body']);*/
			      ?>
            <!-- <div class="cartAndPrice row-fluid">
					  	
					  	<div class="span6">
						  	<?php print render($content['display_price']);	?>
						</div>	
							
						<div class="span6">	
							<?php print render($content['add_to_cart']); ?>
					  	</div>
					  	
				  </div>--> 
          </div>
        </div>
      </div>
      <div class="product-desc"> </div>
      <?php if ($submitted && !$teaser): ?>
      <!-- <div class="submitted"><?php print $submitted; ?> </div> --><!-- Uncomment to show node submitted info on full nodes-->
      <?php endif; ?>
    </div>
    <?php if ($content['field_tags']):  ?>
    <div class="terms"><?php print render($content['field_tags']); ?></div>
    <?php endif;?>
  </div>
  <div class="clearfix">
    <?php  if ($content['links']): ?>
    <div class="links"><?php print render($content['links']) ?></div>
    <?php  endif; ?>
	<?php //print render($content['comments']); ?> </div>
</div>
<?php } else {?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> <?php print "iteration-$id"; ?>"<?php print $attributes; ?>>
  <?php if ($submitted && $teaser): ?>
  <?php endif; ?>
  <div class="meta">
    <div class="content clearfix row-fluid">
      <div class="row-fluid">
        <div class="product-images pull-left span4">
          <?php /* if($title) {?>
          <h3 class="product-title"><?php print $title; ?></h3>
          <span class="product-title-border"></span>
          <?php } */ ?>
          <?php print render($content['field_fotos']) ?> </div>
        <?php
	      
	      hide($content['comments']);
	      hide($content['links']);
		  hide($content['field_tabs']);
		  hide($content['body']);
		  hide($content['field_indicaciones']);
		  hide($content['field_descripcion_tecnica']);
		//  hide($content['add_to_cart']);
		//  hide($content['taxonomy_catalog']);
		  ?>
        <div class="product-desc pull-right span8">
          <div class="product-descriptions">
            <?php
			 	 	  print render($content['field_descripcion_tecnica']);
			 	 	  print render($content['field_indicaciones']);
			 	 	  print render($content['field_archivos']);
			 	 ?>
          </div>
          <!--  <div class="cartAndPrice row-fluid">
			  	
			  	<div class="span6">
				  	<?php print render($content['display_price']);	?>
				</div>	
					
				<div class="span6">	
					<?php print render($content['add_to_cart']); ?>
			  	</div>
			  	
			  </div>--> 
        </div>
      </div>
      <div class="product-desc">
        <?php
		  print "<br>";
	      //print render($content['body']);
	      ?>
      </div>
    </div>
  </div>
  <div class="clearfix">
    <?php  /*if ($content['links']): ?>
    <div class="links"><?php print render($content['links']) ?></div>
    <?php  endif;*/ ?>
    <?php //print render($content['comments']); ?> </div>
</div>
<?php } ?>
