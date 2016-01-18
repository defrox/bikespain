<?php
function opinno_admin_form($form, &$form_state, $op = NULL) {
  $form = array();
  $form['opinno_bikespain'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
	'#title' => t('Opinno Settings'),
  );
  $form['opinno_bikespain']['company_address'] = array(
      '#type'  => 'textfield',
	  '#title' => t('Company Address'),  
	  '#description' => t('The address shown on the page\'s footer.'),
	  '#default_value' => variable_get('company_address',''),
  );
  $form['opinno_bikespain']['company_phone'] = array(
      '#type'  => 'textfield',
	  '#title' => t('Company Phone'),  
	  '#description' => t('The phone shown on the page\'s footer.'),
	  '#default_value' => variable_get('company_phone',''),
  );
  $form['opinno_bikespain']['company_email'] = array(
      '#type'  => 'textfield',
	  '#title' => t('Company Email'),  
	  '#description' => t('The email address shown on the page\'s footer.'),
	  '#default_value' => variable_get('company_email',''),
  );
  $form['opinno_bikespain']['europe_context'] = array(
      '#type'  => 'textfield',
	  '#title' => t('Europe Context'),  
	  '#description' => t('The url\'s path for Europe\'s context.'),
	  '#default_value' => variable_get('europe_context','europa'),
  );
  $form['opinno_bikespain']['spain_context'] = array(
      '#type'  => 'textfield',
	  '#title' => t('Spain Context'),  
	  '#description' => t('The url\'s path for Spain\'s context.'),
	  '#default_value' => variable_get('spain_context','spain'),
  );
  $form['opinno_bikespain']['spain_webform_es'] = array(
      '#type'  => 'textfield',
	  '#title' => t('SPAIN: Book Now Webform ID ES'),  
	  '#description' => t('The node\'s ID for Spain\'s book now webform in Spanish.'),
	  '#default_value' => variable_get('spain_webform_es','38'),
  );
  $form['opinno_bikespain']['spain_webform_en'] = array(
      '#type'  => 'textfield',
	  '#title' => t('SPAIN: Book Now Webform ID EN'),  
	  '#description' => t('The node\'s ID for Spain\'s book now webform in English.'),
	  '#default_value' => variable_get('spain_webform_en','1649'),
  );
  $form['opinno_bikespain']['europe_webform_es'] = array(
      '#type'  => 'textfield',
	  '#title' => t('EUROPE: Book Now Webform ID ES'),  
	  '#description' => t('The node\'s ID for Europe\'s book now webform in Spanish.'),
	  '#default_value' => variable_get('europe_webform_es','350'),
  );
  return system_settings_form($form);
}
//node

//Función que intercambia caracteres raros por normales
/*$vocalti= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","'",'"',"´");
$vocales= array ("a","e","i","o","u","A","E","I","O","U"," "," "," ");
//str_replace($vocalti, $vocales,$variable);*/

function alimentador_access() {

	//Cambio opcional de codificacion
	/*$previo = mb_internal_encoding();
	mb_internal_encoding ('UTF-8');*/
	
	/*Borrar nodos generados en anteriores importaciones*/
	/*$fd = fopen ("/srv/bindings/5f19528d3ffd445eb4075e19f63c4045/code/sites/all/modules/opinno_bikespain/ProductosServicios.csv", "r+");
	while (NULL !=( $datos = fgetcsv ($fd, '"', ';'))){
		
		$query = new EntityFieldQuery();
		$result = $query -> entityCondition('entity_type', 'node')
			->fieldCondition('field_id_producto','value', htmlentities($datos[0], ENT_QUOTES, "UTF-8"), '=')//meter nombre maquina de campo 
			->execute();
			
		if (empty($result['node'])){
			print 'No se encuentra nodo';
		}	
		if (!empty($result['node'])) {
			$nodes = entity_load('node', array_keys($result['node']));
			foreach ($nodes as $node) {
				node_delete ($node->nid);
			}
		}
	}
	fclose($fd);
	print'<pre>Fin de eliminacion de nodos</pre>';*/

	//Fichero .csv de definicion de productos
	/*$fd = fopen ("/srv/bindings/5f19528d3ffd445eb4075e19f63c4045/code/sites/all/modules/opinno_bikespain/ProductosServicios.csv", "r+");
	while (NULL !=( $datos = fgetcsv ($fd, '"', ';'))){
			
		//Quitar comillas recordando el string
		//$datos[3] = mb_substr($datos[3], 1, (strlen($datos[3])-1));
					
		//Comienza la generacion de nodos nuevos identificables con el id_producto
		//Añadimos las propiedades del nodo
		$newNode = new stdclass ();
		$newNode->type = 'productos';
		node_object_prepare($newNode);
		$newNode->title = $datos[1];
		$newNode->uid = 1;
		$newNode->created = strtotime("now");
		$newNode->changed = strtotime("now");
		$newNode->status = 1;
		$newNode->comment = 1;
		$newNode->promote = 0;
		$newNode->moderate = 0;
		$newNode->sticky = 0;
		$newNode->language = LANGUAGE_NONE; //facil identificacion para eliminar las pruebas
		$newNode->context = 'espana';
		node_save($newNode);
		
		//variable que almacena el idioma de los campos ckk
		$language = LANGUAGE_NONE;

		// add CCK field data
		$newNode->field_id_producto[$language][0]['value'] = htmlentities($datos[0], ENT_QUOTES, "UTF-8");
		$newNode->field_texto_promocional[$language][0]['value'] = htmlentities($datos[3], ENT_QUOTES, "UTF-8");
		$newNode->field_overview[$language][0]['value'] = htmlentities($datos[4], ENT_QUOTES, "UTF-8").htmlentities($datos[23], ENT_QUOTES, "UTF-8");
		$newNode->field_precio[$language][0]['value'] = htmlentities($datos[17], ENT_QUOTES, "UTF-8");
		$newNode->field_n_mero_de_noches[$language][0]['value'] = htmlentities($datos[34], ENT_QUOTES, "UTF-8");
		$newNode->field_n_mero_de_d_as[$language][0]['value'] = htmlentities($datos[34], ENT_QUOTES, "UTF-8");
		node_save($newNode);
	}	
		fclose ($fd);
		print '<pre>Fin de nuevos nodos</pre>';*/
	
	//Necesario para el cambio de codificacion opcional
	//mb_internal_encoding ($previo);
	
	//Fichero .csv de itinerarios
	/*$fd = fopen ("/srv/bindings/5f19528d3ffd445eb4075e19f63c4045/code/sites/all/modules/opinno_bikespain/ProductosServiciosItinerarios.csv", "r+");
	while (NULL !=( $itinerarios = fgetcsv ($fd, '"', ';'))){
		
		//Localizamos nodo donde vamos a insertar el campo field collection a traves del campo field_id_producto
		
		$query = new EntityFieldQuery();
		$result = $query -> entityCondition('entity_type', 'node')
			->fieldCondition('field_id_producto','value', htmlentities($itinerarios[0], ENT_QUOTES, "UTF-8"), '=')//meter nombre maquina de campo 
			->execute();
			
		if (empty($result['node'])){
			print 'No se encuentra nodo';
		}	
		if (!empty($result['node'])) {
			$nodes = entity_load('node', array_keys($result['node']));
			foreach ($nodes as $node) {
				
				//Carga de campos field_collection
				$field_collection_item = entity_create('field_collection_item', array('field_name' => 'field_info_rutas'));
				$field_collection_item->setHostEntity('node', $node);
				$field_collection_item->field_titulo_ruta[und][0][value] = htmlentities($itinerarios[3], ENT_QUOTES, "UTF-8");
				$field_collection_item->field_descripci_n_corta_ruta[und][0][value] = htmlentities($itinerarios[4], ENT_QUOTES, "UTF-8");
				$field_collection_item->field_descripci_n_larga_ruta[und][0][value] = htmlentities($itinerarios[5], ENT_QUOTES, "UTF-8");
				$field_collection_item->save();
			}
		}
	}
	
	fclose ($fd);
	print '<pre>Fin de itinerarios</pre>';*/
	
	//Añadir comentarios
	//Abrimos el fichero que contiene los comentarios
	/*$fd = fopen ("/srv/bindings/5f19528d3ffd445eb4075e19f63c4045/code/sites/all/modules/opinno_bikespain/ProductosServiciosOpiniones.csv", "r+");
    while (NULL != ($opiniones = fgetcsv ($fd, '"', ';'))){ //mientras no llegue al final del fichero sigue iterando
	
		$query = new EntityFieldQuery();
		$result = $query -> entityCondition('entity_type', 'node')
			->fieldCondition('field_id_producto','value', htmlentities($opiniones[0], ENT_QUOTES, "UTF-8"), '=')//meter nombre maquina de campo 
			->execute();
				
		if (empty($result['node'])){
			print 'No se encuentra nodo';
		}
			
		if (!empty($result['node'])) {
			$nodes = entity_load('node', array_keys($result['node']));
			foreach ($nodes as $node) {
				$comment = (object) array(
					'nid' => $node->nid,
					'cid' => 0,
					'pid' => 0,
					'uid' => 1,
					'mail' => '',
					'is_anonymous' => 0,
					'homepage' => '',
					'status' => COMMENT_PUBLISHED,
					'subject' => htmlentities(substr($opiniones[3], 0, 49), ENT_QUOTES, "UTF-8"),
					'language' => LANGUAGE_NONE,
					'comment_body' => array(
							'und' => array(
											0 => array (
												'value' => htmlentities($opiniones[5], ENT_QUOTES, "UTF-8"),
												'format' => 'filtered_html'
														)
											)
							),
				);
				comment_submit($comment);
				comment_save($comment);
				print'<pre>';
				print_r ($comment);
				print'</pre>';
			}
		}
	}	
	fclose ($fd);*/
	
	/*Insertar datos en campos +info*/
	/*$fd = fopen ("/srv/bindings/5f19528d3ffd445eb4075e19f63c4045/code/sites/all/modules/opinno_bikespain/ProductosServiciosDetalles.csv", "r+");
	while (NULL !=( $datos = fgetcsv ($fd, '"', ';'))){
		
		$query = new EntityFieldQuery();
		$result = $query -> entityCondition('entity_type', 'node')
			->fieldCondition('field_id_producto','value', htmlentities($datos[0], ENT_QUOTES, "UTF-8"), '=')//meter nombre maquina de campo 
			->execute();
			
		if (empty($result['node'])){
			print 'No se encuentra nodo';
		}	
		if (!empty($result['node'])) {
			$nodes = entity_load('node', array_keys($result['node']));
			foreach ($nodes as $node) {
				if ($datos[1] == 1) {
					$node->field_que_esta_incluido['und'][0]['value'] = htmlentities($datos[3], ENT_QUOTES, "UTF-8").htmlentities($datos[4], ENT_QUOTES, "UTF-8");
				}
				if ($datos[1] == 2) {
					$node->field_no_incluido['und'][0]['value'] = htmlentities($datos[3], ENT_QUOTES, "UTF-8").htmlentities($datos[4], ENT_QUOTES, "UTF-8");
				}
				if ($datos[1] == 3) {
					$node->field_opciones['und'][0]['value'] = $node->field_opciones['und'][0]['value'].htmlentities($datos[3], ENT_QUOTES, "UTF-8").htmlentities($datos[4], ENT_QUOTES, "UTF-8");
				}
				if ($datos[1] == 4) {
					$node->field_nuestras_bicicletas['und'][0]['value'] = htmlentities($datos[3], ENT_QUOTES, "UTF-8").htmlentities($datos[4], ENT_QUOTES, "UTF-8");
				}
				if ($datos[1] == 5) {
					$node->field_destacados['und'][0]['value'] = htmlentities($datos[3], ENT_QUOTES, "UTF-8").htmlentities($datos[4], ENT_QUOTES, "UTF-8");
				}
				if ($datos[1] == 6) {
					$node->field_opciones['und'][0]['value'] = $node->field_opciones['und'][0]['value'].htmlentities($datos[3], ENT_QUOTES, "UTF-8").htmlentities($datos[4], ENT_QUOTES, "UTF-8");
				}
				if ($datos[1] == 7) {
					$node->field_opciones['und'][0]['value'] = $node->field_opciones['und'][0]['value'].htmlentities($datos[3], ENT_QUOTES, "UTF-8").htmlentities($datos[4], ENT_QUOTES, "UTF-8");
				}
				node_save($node);
				print'<pre>';
				print_r ($node);
				print'</pre>';
			}
		}
	}
	fclose($fd);*/	
	print '<pre>Migracion de bbdd finalizada</pre>';
}