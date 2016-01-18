<?php 
//$block = module_invoke('views', 'block_view', 'destinos-block_1');
//print render($block['content']);

//Block Productos (Destinos)
$tid = arg(2);
$term = taxonomy_term_load($tid);
$my_arg = $term->name;
/*$block = views_embed_view('destinos', 'block_1', $my_arg);
print '<h3 class="lined" style="margin-top: 12px">'.t('Viajes por ').$my_arg.'</h3>';
if (!empty($block)) {
	print $block;
}
else {
	print t('No se encontraron viajes en ').$my_arg;
}*/

//Block Restaurantes (Destinos)
//$block_restaurantes = views_embed_view('restaurantes', 'block', $my_arg);
//print '<h3 class="lined" style="margin-top: 12px">'.t('Restaurantes por ').$my_arg.'</h3>';
//if (!empty($block_restaurantes)) {
//	print $block_restaurantes;
//}
//else {
//	print t('No se encontraron restaurantes en ').$my_arg;
//}

//Block Hoteles (Destinos)
//$block_hoteles = views_embed_view('hotels', 'block', $my_arg);
//print '<h3 class="lined" style="margin-top: 12px">'.t('Hoteles por ').$my_arg.'</h3>';
//if (!empty($block_hoteles)) {
//	print $block_hoteles;
//}
//else {
//	print t('No se encontraron restaurantes en ').$my_arg;
//}

