<a href="<?php print file_create_url(drupal_get_path_alias('node/' . $row->nid, $row->node_language));?>?iframe=true&width=100%&height=100%" rel="prettyPhoto[iframes]" class="work-image">
	<img src="<?php print image_style_url('3-columnas', $row->field_field_imagen[0]['rendered']['#item']['uri']);?>" alt="<?php print $row->field_field_imagen[0]['rendered']['#item']['title'];?>" title="<?php print $row->field_field_imagen[0]['rendered']['#item']['title'];?>" />
	<div class="link-overlay icon-search"></div>
</a>