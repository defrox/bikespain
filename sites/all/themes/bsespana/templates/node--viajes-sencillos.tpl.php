<?php
global $language;
//Print image
if (!empty($node->field_imagen_principal)){
$url_img = file_create_url($node->field_imagen_principal['und'][0]['uri']);
print '<center><div class="viajes-sencillos-image"><img src="'.$url_img.'"/></div></center>';
}
//print body
if (!empty($body)){
	print '<div class="viajes-sencillos-body" style="margin-top: 12px;">'.$body[0]['value'].'</div>';
}

?> 




