<?php $color = (!empty($row->field_field_color)) ? ($row->field_field_color[0]['raw']['rgb']) : ('#D7D7D7'); 
?>

<a class="experiencia-link" href="<?php print file_create_url(drupal_get_path_alias('node/' . $row->nid));?>?iframe=true&width=100%&height=100%" rel="prettyPhoto[iframes]">
    <div class='title_mansory'>  
		<?php if (!empty($row->field_field_imagen_experiencia)) {?>
	<img class="image-simple" typeof="foaf:Image" src="<?php print file_create_url($row->field_field_imagen_experiencia[0]['rendered']['#item']['uri']);?>" width="300px" height="300px" />

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