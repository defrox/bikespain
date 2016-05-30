<?php

/**
 * TPV Admin Global Config form
 */

function opinno_tpv_admin() {

  $form = array();

  $form['opinno_tpv_titular'] = array(
    '#type' => 'textfield',
    '#title' => t('Nombre del titular'),
    '#default_value' => variable_get('opinno_tpv_titular', ''),
    '#description' => t("Nombre y apellidos del titular."),
    '#size' => 60,
    '#maxlength' => 60,
    '#required' => TRUE,
  );

  $form['opinno_tpv_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Nombre del comercio'),
    '#default_value' => variable_get('opinno_tpv_name', ''),
    '#description' => t("Introduzca el nombre del comercio."),
    '#size' => 25,
    '#maxlength' => 25,
    '#required' => TRUE,
  );

  $form['opinno_tpv_fuc_number'] = array(
    '#type' => 'textfield',
    '#title' => t('Número de comercio FUC'),
    '#default_value' => variable_get('opinno_tpv_fuc_number', ''),
    '#description' => t("Introduzca el número de comercio FUC"),
    '#size' => 9,
    '#maxlength' => 9,
    '#required' => TRUE,
  );

  $form['opinno_tpv_clave'] = array(
    '#type' => 'textfield',
    '#title' => t('Clave privada'),
    '#default_value' => variable_get('opinno_tpv_clave', ''),
    '#description' => t("Introduzca su clave privada"),
    '#required' => TRUE,
  );

  $form['opinno_tpv_term_number'] = array(
    '#type' => 'textfield',
    '#title' => t('Número de terminal'),
    '#size' => 3,
    '#maxlength' => 3,
    '#default_value' => variable_get('opinno_tpv_term_number', ''),
    '#description' => t("Introduzca el número de terminal."),
    '#required' => TRUE,
  );

  $form['opinno_tpv_url'] = array(
    '#type' => 'textfield',
    '#title' => t('URL de notificación online TPV'),
    '#default_value' => variable_get('opinno_tpv_url', ''),
    '#description' => t("Introduzca la URL del TPV."),
    '#maxlength' => 250,
    '#required' => TRUE,
  );
  /*
  $form['opinno_tpv_percent'] = array(
    '#type' => 'textfield',
    '#title' => t('Porcentaje mínimo'),
    '#maxlength' => 3,
    '#size' => 3,
    '#default_value' => variable_get('opinno_tpv_percent', 5),
    '#description' => t("Porcentaje mínimo a pagar."),
    '#required' => TRUE,
  );
  */
  $form['opinno_tpv_mode'] = array(
    '#type' => 'select',
    '#title' => t('URL del TPV'),
    '#options' => array(
      0 => 'Puebas',
      1 => 'Producción',
    ),
    '#default_value' => variable_get('opinno_tpv_mode', 0),
    '#description' => t('Seleccione entre Prueba y Producción'),
  );

  $form['opinno_tpv_email_from'] = array(
    '#type' => 'textfield',
    '#title' => t('Email Administrador'),
    '#default_value' => variable_get('opinno_tpv_email_from', 'info@bikespain.com'),
    '#description' => t("Email desde el que se envía la orden de pago"),
    '#required' => TRUE,
  );


  return system_settings_form($form);
}

/**
 * TPV Admin email Templates form
 */

function opinno_tpv_templates() {

  $form = array();

  $form['title1'] = array(
    '#type' => 'markup',
    '#markup' => '<h2>' . t('Payment Order Template') . '</h2>',
    '#tree' => true,
  );


  $languages = locale_language_list('name');

  foreach ($languages as $lang_code => $lang_name) {
    $field_name = 'opinno_tpv_email_' . $lang_code;
    $field_value = variable_get($field_name, array('value' => '', 'format' => NULL));

    $form[$field_name] = array(
      '#type' => 'text_format',
      '#title' => t('Plantilla de email en ' . $lang_name),
      '#default_value' => $field_value['value'],
      '#description' => t('Plantilla del email por defecto para idioma ' . $lang_name),
    );
  }

  $form['title2'] = array(
    '#type' => 'markup',
    '#markup' => '<h2>' . t('Payment Success Template') . '</h2>',
    '#tree' => true,
  );

  foreach ($languages as $lang_code => $lang_name) {
    $field_name = 'opinno_tpv_email_success_' . $lang_code;
    $field_value = variable_get($field_name, array('value' => '', 'format' => NULL));

    $form[$field_name] = array(
      '#type' => 'text_format',
      '#title' => t('Plantilla de email en ' . $lang_name),
      '#default_value' => $field_value['value'],
      '#description' => t('Plantilla del email por defecto para idioma ' . $lang_name),
    );
  }
  /*
  $form['title3'] = array(
    '#type' => 'markup',
    '#markup' => '<h2>' . t('Payment error Template') . '</h2>',
    '#tree' => true,
  );

  foreach ($languages as $lang_code => $lang_name) {
    $field_name = 'opinno_tpv_email_error_' . $lang_code;
    $field_value = variable_get($field_name, array('value' => '', 'format' => NULL));

    $form[$field_name] = array(
      '#type' => 'text_format',
      '#title' => t('Plantilla de email en ' . $lang_name),
      '#default_value' => $field_value['value'],
      '#description' => t('Plantilla del email por defecto para idioma ' . $lang_name),
    );

  }
  */
  return system_settings_form($form);
}

/**
 * Deletes a row from ordenes_de_pago
 *
 * @param $index : uid of the row to delete
 */

function opinno_tpv_eliminar_orden_de_pago($index){
  $querydelete = db_delete('ordenes_de_pago')->condition('uid', $index);
  $resultdelete = $querydelete->execute();

  if ($resultdelete) {
    drupal_set_message(t('Se ha eliminado el registro correctamente'), 'status');
  } else {
    drupal_set_message(t('No se ha podido eliminar el registro'), 'error');
  }
}

/**
 * Payment order admin form, edit/create payment order
 *
 * @param $form : id of the form
 * @param $form_state : data of the form
 */

function opinno_tpv_ordenes_de_pago($form, $form_state){

  $command = $form_state["build_info"]["args"][0];
  //$index = $form_state["build_info"]["args"][1];

  $query = db_select('ordenes_de_pago', 'odp')
    ->fields('odp', array('uid','name', 'last_name', 'mail', 'ref_viaje', 'fecha', 'factura_proforma', 'estado', 'respuesta_tpv', 'editable', 'plantilla_email', 'email_enviado', 'fecha_email_enviado', 'cantidad', 'concepto'/*, 'notas'*/))
    ->orderBy('uid', 'DESC');
  $query = $query->extend('PagerDefault')->limit(50);
  $results = $query->execute();
  $data = $results->fetchAll();

  //$header = array('Name', 'Last Name', 'Email', 'Ref Viaje', 'Fecha', 'Factura Proforma', 'Estado', 'Respuesta TPV', 'Editable', 'Plantilla Email', 'Email Enviado', 'Fecha Email Enviado', 'Cantidad', 'Operaciones');
  $header = array('Name', 'Last Name', 'Email', 'Ref Viaje', 'Fecha', 'Factura Proforma', 'Estado', 'Respuesta TPV', 'Email Enviado', 'Fecha Email Enviado', 'Cantidad', 'Concepto', /*'Notas',*/ 'Operaciones');

  $pay_status = array(
    0 => t('Pending'),
    1 => t('Payed'),
    2 => t('Payment Error'),
    3 => t('Verification error'),
  );

  $email_status = array(
    0 => t('No'),
    1 => t('Yes'),
  );

  foreach($data as $row) {
    $fecha_email = ($row ->email_enviado)?date("d-m-Y",$row->fecha_email_enviado):'';

    $rows[] = array(
      $row->name,
      $row->last_name,
      $row->mail,
      $row->ref_viaje,
      date("d-m-Y",$row->fecha),
      $row->factura_proforma,
      $pay_status[$row->estado],
      $row->respuesta_tpv,
      $email_status[$row->email_enviado],
      $fecha_email,
      $row->cantidad,
      $row->concepto,
//      $row->notas,
      (l(t('edit'), "admin/config/system/opinno_tpv/orden/$row->uid") . ' | ' .  l(t('delete'), "admin/config/system/opinno_tpv/ordenes_de_pago/delete/$row->uid")),
    );
  }

  $form['crear'] = array(
    '#type' => 'link',
    '#title' => t('Crear'),
    '#href' => 'admin/config/system/opinno_tpv/orden',
  );

  @$form['table'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $rows,
    '#empty' => t('No hay ordenes de pago'),
  );

  $form['pager'] = array('#markup' => theme('pager'));

  if ($command == 'delete') {
    $form2 = array();
    return confirm_form($form2,
      t('Seguro que quieres eliminar esta orden de pago?'),
      "admin/config/system/opinno_tpv/ordenes_de_pago",
      t('This action cannot be undone.'),
      t('Delete'),
      t('Cancel'));
  } else {
    return $form;
  }
}

/**
 * Payment order admin form, hook_submit
 *
 * @param $form : id of the form
 * @param $form_state : data of the form
 */

function opinno_tpv_ordenes_de_pago_submit($form, &$form_state) {
  $command = $form_state["build_info"]["args"][0];
  $index = $form_state["build_info"]["args"][1];
  switch ($command) {
    case 'delete':
      opinno_tpv_eliminar_orden_de_pago($index);
      break;
  }
  drupal_goto("admin/config/system/opinno_tpv/ordenes_de_pago");
}

