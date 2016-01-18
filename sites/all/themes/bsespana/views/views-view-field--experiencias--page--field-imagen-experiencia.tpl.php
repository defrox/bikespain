<?php 
global $language, $contexto;

$color = (!empty($row->field_field_color)) ? ($row->field_field_color[0]['raw']['rgb']) : ('#D7D7D7'); 
if (array_key_exists(0,$row->field_field_contexto1)) $contexto = $row->field_field_contexto1[0]['raw']['value'].'/';
else $contexto = variable_get('spain_context','spain').'/';
if (strpos(drupal_get_path_alias('node/' . $row->nid, $row->_field_data['nid']['entity']->language), $contexto) !== false) $my_url = $language->language . '/' . drupal_get_path_alias('node/' . $row->nid, $row->_field_data['nid']['entity']->language);
else $my_url = $language->language . '/' . $contexto . drupal_get_path_alias('node/' . $row->nid, $row->_field_data['nid']['entity']->language);
?>
<a class="experiencia-link" href="<?php print file_create_url($my_url);?>?iframe=true&width=100%&height=100%" rel="prettyPhoto[iframes]" node="<?php print $row->nid;?>">
<div class='title_mansory'>
  <?php if (!empty($row->field_field_imagen_experiencia)) {?>
  <img class="image-simple" typeof="foaf:Image" src="<?php print image_style_url('300x300', $row->field_field_imagen_experiencia[0]['rendered']['#item']['uri']);?>" width="300px" height="300px" />
  <div class='description_title_mansory'>
    <p class='description_content_mansory'><?php print $row->node_title ; ?></p>
  </div>
  <?php } else {?>
  <img class="image-effect" typeof="foaf:Image" src="../../sites/all/themes/boson/img/opinno/highlight.png" style="background-color:<?php print $color ?>" width="300px" height="300px" />
  <div class='description_title_mansory_no_image'>
    <p class='description_content_mansory'><?php print $row->node_title ; ?></p>
  </div>
  <?php } ?>
</div>
</a> 