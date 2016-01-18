<?php 
//$block = module_invoke('views', 'block_view', 'destinos-block_1');
//print render($block['content']);

$tid = arg(2);
$term = taxonomy_term_load($tid);
$my_arg = $term->name;
$block = views_embed_view('tiposproducto', 'block', $my_arg);

if (!empty($block)) {
	print '<h3 class="lined" style="margin-top: 12px">'.t('Viajes por tipo: ').$my_arg.'</h3>';
	print $block;
}
else {
	print "No se encontraron resultados";
}

 ?>