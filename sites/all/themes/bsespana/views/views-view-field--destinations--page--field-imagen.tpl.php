<?php
global $language;
if ($row->node_language == 'und')
	$dfx_lang =$language->language;
else $dfx_lang = $row->node_language;
?>
<a href="<?php print file_create_url($dfx_lang . '/' . drupal_get_path_alias('node/' . $row->nid, $dfx_lang));?>" class="work-image">
	<img src="<?php print image_style_url('3-columnas', $row->field_field_imagen[0]['rendered']['#item']['uri']);?>" alt="<?php print $row->field_field_imagen[0]['rendered']['#item']['title'];?>" title="<?php print $row->field_field_imagen[0]['rendered']['#item']['title'];?>" />
	<div class="link-overlay icon-search"></div>
</a>
