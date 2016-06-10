<?php

/**
 * TPV Admin Global Config form
 */

function opinno_tpv_admin()
{

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

function opinno_tpv_templates()
{

    $form = array();

    $form['title1'] = array(
        '#type' => 'markup',
        '#markup' => '<h2>' . t('Payment Order Template') . '</h2>',
        '#tree' => true,
    );


    $languages = locale_language_list('name');

    foreach ($languages as $lang_code => $lang_name) {
        $field_name = 'opinno_tpv_email_' . $lang_code;
        $field_search = variable_get($field_name, array('value' => '', 'format' => NULL));

        $form[$field_name] = array(
            '#type' => 'text_format',
            '#title' => t('Plantilla de email en ' . $lang_name),
            '#default_value' => $field_search['value'],
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
        $field_search = variable_get($field_name, array('value' => '', 'format' => NULL));

        $form[$field_name] = array(
            '#type' => 'text_format',
            '#title' => t('Plantilla de email en ' . $lang_name),
            '#default_value' => $field_search['value'],
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
      $field_search = variable_get($field_name, array('value' => '', 'format' => NULL));

      $form[$field_name] = array(
        '#type' => 'text_format',
        '#title' => t('Plantilla de email en ' . $lang_name),
        '#default_value' => $field_search['value'],
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

function opinno_tpv_eliminar_orden_de_pago($index)
{
    var_dump($index);
    $querydelete = db_delete('ordenes_de_pago')->condition('uid', $index);
    $resultdelete = $querydelete->execute();
    if ($resultdelete) {
        drupal_set_message(t('Se ha eliminado el registro correctamente'), 'status');
    } else {
        drupal_set_message(t('No se ha podido eliminar el registro'), 'error');
    }
    return $resultdelete;
}

/**
 * Payment order admin form, edit/create payment order
 *
 * @param $form : id of the form
 * @param $form_state : data of the form
 */

function opinno_tpv_ordenes_de_pago($form, $form_state)
{
    $command = $form_state['build_info']['args'][0];

    if ($form_state['input']['field_search'] != '' && $form_state['input']['op'] == 'Aplicar') {
        $field_search = $form_state['input']['field_search'];
    } elseif ($_GET && array_key_exists('field_search', $_GET) && $form_state['input']['op'] === null)  {
        $field_search = $_GET['field_search'] != '' ? $_GET['field_search'] : false;
    } else {
        $field_search = false;
    }

    if ($form_state['input']['value_string'] != '' && $form_state['input']['op'] == 'Aplicar') {
        $value_string = $form_state['input']['value_string'];
    } elseif ($_GET && array_key_exists('value_string', $_GET) && $form_state['input']['op'] === null)  {
        $value_string = $_GET['value_string'] != '' ? $_GET['value_string'] : false;
    } else {
        $value_string = false;
    }

    if ($form_state['input']['rows_per_page'] != '' && $form_state['input']['op'] == 'Aplicar') {
        $rows_per_page = $form_state['input']['rows_per_page'];
    } elseif ($_GET && array_key_exists('rows_per_page', $_GET) && $form_state['input']['op'] === null)  {
        $rows_per_page = $_GET['rows_per_page'] != '' ? $_GET['rows_per_page'] : 10;
    } else {
        $rows_per_page = 10;
    }

    $page = $_GET['page'] != '' ? $_GET['page'] != '' : 0;

    $parameters = array('field_search' => $field_search, 'value_string' => $value_string, 'rows_per_page' => $rows_per_page);
    $query_string = "?page=$page&field_search=$field_search&value_string=$value_string&rows_per_page=$rows_per_page";

    $header = array(
        array('data' => t('Nombre'), 'field' => 'name'),
        array('data' => t('Apellidos'), 'field' => 'last_name'),
        array('data' => t('Email'), 'field' => 'mail'),
        array('data' => t('Ref Viaje'), 'field' => 'ref_viaje'),
        array('data' => t('Fecha'), 'field' => 'fecha'),
        array('data' => t('Factura Proforma'), 'field' => 'factura_proforma'),
        array('data' => t('Estado'), 'field' => 'estado'),
        array('data' => t('Respuesta TPV'), 'field' => 'respuesta_tpv'),
        array('data' => t('Email Enviado'), 'field' => 'email_enviado'),
        array('data' => t('Fecha Email Enviado'), 'field' => 'fecha_email_enviado'),
        array('data' => t('Cantidad'), 'field' => 'cantidad'),
        array('data' => t('Concepto'), 'field' => 'concepto'),
        array('data' => t('Operaciones')),
    );

    $fields = array (
        'name' => 'Nombre',
        'last_name' => 'Apellidos',
        'mail' => 'Email',
        'ref_viaje' => 'Ref Viaje',
        'fecha' => 'Fecha',
        'factura_proforma' => 'Fra Proforma',
        'estado' => 'Estado',
        'respuesta_tpv' => 'Respuesta TPV',
        'email_enviado' => 'Email Enviado',
        'fecha_email_enviado' => 'Fecha Email Enviado',
        'cantidad' => 'Cantidad',
        'concepto' => 'Concepto'
    );

    $rows_per_page_values = array (
        10 => 10,
        25 => 25,
        50 => 50,
        100 => 100,
        99999 => 'Todos'
    );

    // Check if there is sorting request
    if (isset($_GET['sort']) && isset($_GET['order'])) {
        // Sort it Ascending or Descending?
        if ($_GET['sort'] == 'asc')
            $sort = 'ASC';
        else
            $sort = 'DESC';
        // Which column will be sorted
        switch ($_GET['order']) {
            case 'Name':
                $order = 'name';
                break;
            case 'Apellidos':
                $order = 'last_name';
                break;
            case 'Email':
                $order = 'mail';
                break;
            case 'Ref Viaje':
                $order = 'ref_viaje';
                break;
            case 'Fecha':
                $order = 'fecha';
                break;
            case 'Factura Proforma':
                $order = 'factura_proforma';
                break;
            case 'Estado':
                $order = 'estado';
                break;
            case 'Respuesta TPV':
                $order = 'respuesta_tpv';
                break;
            case 'Email Enviado':
                $order = 'email_enviado';
                break;
            case 'Fecha Email Enviado':
                $order = 'fecha_email_enviado';
                break;
            case 'Cantidad':
                $order = 'cantidad';
                break;
            case 'Concepto':
                $order = 'concepto';
                break;
            default:
                $order = 'uid';
        }
    } else {
        // Default sort
        $sort = 'DESC';
        $order = 'uid';
    }
    $query = db_select('ordenes_de_pago', 'odp')
        ->fields('odp', array('uid', 'name', 'last_name', 'mail', 'ref_viaje', 'fecha', 'factura_proforma', 'estado', 'respuesta_tpv', 'editable', 'plantilla_email', 'email_enviado', 'fecha_email_enviado', 'cantidad', 'concepto'))
        ->orderBy($order, $sort);
    if ($field_search && $value_string) {
        $query = $query->condition('odp.'.$field_search, '%' . db_like($value_string) . '%', 'LIKE');
    }
    $query = $query->extend('TableSort');
    $query = $query->extend('PagerDefault')->limit($rows_per_page);
    $results = $query->execute();
    $data = $results->fetchAll();
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

    foreach ($data as $row) {
        $fecha_email = ($row->email_enviado) ? date("d-m-Y", $row->fecha_email_enviado) : '';

        $rows[] = array(
            $row->name,
            $row->last_name,
            $row->mail,
            $row->ref_viaje,
            date("d-m-Y", $row->fecha),
            $row->factura_proforma,
            $pay_status[$row->estado],
            $row->respuesta_tpv,
            $email_status[$row->email_enviado],
            $fecha_email,
            $row->cantidad,
            $row->concepto,
            (l(t('edit'), "admin/config/system/opinno_tpv/orden/$row->uid/$query_string") . ' | ' . l(t('delete'), "admin/config/system/opinno_tpv/ordenes_de_pago/delete/$row->uid/$query_string")),
        );
    }

    $form['crear'] = array(
        '#prefix' => '<div><ul class="action-links"><li>',
        '#suffix' => '</li></ul></div>',
        '#type' => 'link',
        '#title' => t('Agregar nueva Orden de Pago'),
        '#href' => 'admin/config/system/opinno_tpv/orden'.$query_string,
    );

    $form['filters']['rows_per_page'] = array(
        '#type' => 'select',
        '#title' => t('Resultados por página '),
        '#default_value' => $rows_per_page,
        '#options' => $rows_per_page_values,
        '#prefix' => '<div style="float:left; margin-right: 20px;">',
        '#suffix' => '</div>',
    );

    $form['filters']['field_search'] = array(
        '#type' => 'select',
        '#title' => t('Filtrar por '),
        '#default_value' => $field_search,
        '#options' => $fields,
        '#prefix' => '<div style="float:left; margin-right: 20px;">',
        '#suffix' => '</div>',
    );

    $form['filters']['value_string'] = array(
        '#type' => 'textfield',
        '#title' => t(' que contenga '),
        '#default_value' => $value_string,
        '#prefix' => '<div style="float:left; margin-right: 20px;">',
        '#suffix' => '</div>',
    );

    $form['filters']['submit'] = array(
        '#type' => 'button',
        '#value' => t('Apply'),
        '#prefix' => '<div style="float:left; padding-top: 20px;">',
    );

    $form['filters']['reset'] = array(
        '#type' => 'submit',
        '#value' => t('Reset'),
        '#suffix' => '</div>',
    );

    @$form['table'] = array(
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => t('No hay ordenes de pago'),
    );

    $form['pager'] = array('#markup' => theme('pager', array('parameters' => $parameters)));

    if ($command == 'delete') {
        $form2 = array();
        return confirm_form($form2,
            t('Seguro que quieres eliminar esta orden de pago?'),
            "admin/config/system/opinno_tpv/ordenes_de_pago$query_string",
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

function opinno_tpv_ordenes_de_pago_submit($form, &$form_state)
{
    global $base_url;

    $command = $form_state["build_info"]["args"][0];
    $index = $form_state["build_info"]["args"][1];
    $url = parse_url($base_url . $_GET['q'], PHP_URL_QUERY);
    parse_str($url, $url);

    switch ($command) {
        case 'delete':
            opinno_tpv_eliminar_orden_de_pago($index);
            break;
    }
    drupal_goto("admin/config/system/opinno_tpv/ordenes_de_pago", array('query' => $url));
}

