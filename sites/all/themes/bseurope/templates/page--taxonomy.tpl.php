<?php
$search_box = $page['search_box'];
global $base_url;

//Load taxonomy term and vocabulary
$tid = arg(2);
$term = i18n_taxonomy_localize_terms(taxonomy_term_load($tid));
$my_arg = $term->name;
?>
<div class="main
<?php

print $term->vocabulary_machine_name;

if (theme_get_setting('boson_boxed') == TRUE) {
    print ' boxed';
  }
  else {
   print ' boxed-none';
  }
?> 
 ">

	<!-- TOP BAR 
	============================================= -->
	<div class="b-top-bar">
		<div class="layout">
			<!-- Some text -->
			<div class="wrap-left">
            <?php if ($page['top_left']): ?><?php print render($page['top_left']); ?><?php endif; ?>
				
			</div>
			<div class="wrap-right">
            <?php if ($page['top_right']): ?><?php print render($page['top_right']); ?><?php endif; ?>
				
			</div>
		</div>
	</div>
	<!-- END TOP BAR 
	============================================= -->

	<!-- HEADER 
	============================================= -->
		<div class="header">
		<div class="layout clearfix">
			<div class="mob-layout wrap-left">
            
            <div class="brand">
				<!-- Logo -->
                <?php if ($logo): ?>
               <div class="logo-img">
               <a href="<?php print $front_page; ?>"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" /></a>
               </div>
               <?php endif; ?>

               </div><!-- brand -->
                 <?php print render($page['main_menu']); ?>
				
			</div>
			<!-- Search Form -->
							<?php if($search_box) { ?>
								<div class="b-search-form">
                                
                                <div class="input-wrap">
					
					<?php print render($search_box); ?>
				</div>
									
								</div>
							<?php } ?>
                            
 
			<!-- End Search Form -->

		</div>
	
		
	</div>
	<!-- END HEADER 
	============================================= -->

	<!--  SLIDER
	============================================= -->
 <div class="slider clearfix">
 <?php if ($page['slider']): ?><?php print render($page   ['slider']); ?><?php endif; ?>
	</div>
	<!-- END SLIDER
	============================================= -->

	<!-- FEATURED CONTENT
	============================================= -->
    
   
        <?php if ($page['featured']): ?>
         <div class="content-featured">
		 <div class="layout">
		<?php print render($page['featured']); ?>
					
		 </div>
	     </div>
	
		<?php endif; ?> 

	<!-- END FEATURED CONTENT
	============================================= -->
     


    
     <?php if (!drupal_is_front_page()): ?>
    
    <!-- TITLE BAR
	============================================= -->
	<div class="b-titlebar">
		<div class="layout">
			<!-- Bread Crumbs -->
			<ul class="crumbs">

				
                <?php print $breadcrumb; ?>
				
			</ul>
			<!-- Title -->
			<h1 class="tb1"><?php print $title; ?></h1>
		</div>
	</div>
	<!-- END TITLE BAR -->
 <?php endif; ?>

<?php if ($page['top_content']): ?>
         
<div class="content shortcodes">
		 <div class="layout  top-content">
		<?php print render($page['top_content']); ?>
					
		 </div>
	     </div>
	
		<?php endif; ?> 

	<!-- CONTENT -->
	<div class="content shortcodes">
		<div class="layout" style="padding-bottom: 1px;">
	 
      <?php if ($page['sidebar_first']): ?>
             <div class="row">    
				<div class="row-item col-3_4">
    <?php endif; ?>
 <?php if (!$page['sidebar_first']): ?>
       <div class="dmd">
				<div class="dd">
    <?php endif; ?>
         
          
            
        <?php if ($messages): ?>
            <div id="messages">
              <?php print $messages; ?>
            </div>
          <?php endif; ?>

        <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
        <?php print render($page['help']); ?>
        <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
        
        <?php 
        //Muestro la descripción
		print '<div class="col-1_3 taxonomy-field_entradilla">';
        print $term->field_entradilla['und'][0]['value'];
        print '</div>';
		print '<div class="col-2_3 taxonomy-description">';
        print $term->description;
        print '</div>';
        print '<br clear="all" />';
        if ($term->vocabulary_machine_name == 'cycling'){print '<h3 class="lined" style="margin-top: 12px">'.t('Tours in ').' '.$my_arg.'</h3>';}
        if ($term->vocabulary_machine_name == 'trip'){print '<h3 class="lined" style="margin-top: 12px">'.t('Tours by type: ').' '.$my_arg.'</h3>';}
         ?>

        <?php print render($page['content']); ?>

        <?php 
        		
				/*if ($term->vocabulary_machine_name == 'destinos')
				{
					 print '<div class="page-destinos">';
					//Block Restaurantes (Destinos)
					$block_restaurantes = views_embed_view('restaurantes', 'block', $my_arg);
					print '<h3 class="lined" style="margin-top: 12px">'.t('Restaurants in ').' '.$my_arg.'</h3>';
					if (!empty($block_restaurantes)) {
						print $block_restaurantes;
						print '<div class="ver-mas-link"><a href="'.str_replace("destinos","restaurantes",'/'.request_path()).'">'.t('View more').'</a></div>';
					}
					else {
						print t('No se encontraron restaurantes en ').$my_arg;
					}

					//Block Hoteles (Destinos)
					$block_hoteles = views_embed_view('hotels', 'block', $my_arg);
					print '<h3 class="lined" style="margin-top: 12px">'.t('Hotels in ').' '.$my_arg.'</h3>';
					if (!empty($block_hoteles)) {
						print $block_hoteles;
						print '<div class="ver-mas-link1"><a href="'.str_replace("destinos","hoteles",'/'.request_path()).'">'.t('View more').'</a></div>';
						
											}
					else {
						print t('No se encontraron restaurantes en ').$my_arg;
					}
					print '</div>'; 
        		} elseif ($term->vocabulary_machine_name == 'tipo_producto') {
        			$block_tipo = views_embed_view('tiposproducto', 'block', $my_arg);

					if (!empty($block_tipo)) {
						//print '<h3 class="lined" style="margin-top: 12px">'.t('Tours by type: ').' '.$my_arg.'</h3>';
						print $block_tipo;
					}
					else {
						print t("No se encontraron resultados");
					}
        		}*/
         ?>
        </div>       
        <?php if ($page['sidebar_first']): ?>
        <!-- sidebar first -->
        <div class="row-item col-1_4 sidebar">
          <?php print render($page['sidebar_first']); ?>
        </div>
        <!-- // sidebar first -->
      <?php endif; ?>
	</div>
		</div>
	</div>
	<div class="content">
<?php if($page['postscript_first'] || $page['postscript_second'] || $page['postscript_third'] || $page['postscript_fourth'] || $page['bottom'] ) : ?>

<div class="layout">
        
        <?php if ($page['postscript_first']): ?>
           <?php print render($page['postscript_first']); ?>
            <div class="gap" style="height: 10px;"></div>
            <?php endif; ?>
            
           
		<div class="row">

<?php if ($page['postscript_second']): ?>
				<div class="row-item col-1_2"><?php print render($page['postscript_second']); ?></div>
 <?php endif; ?>


<?php if ($page['postscript_third']): ?>
<div class="row-item col-1_2"><?php print render($page['postscript_third']); ?></div>
<?php endif; ?>
</div>


	<?php if ($page['postscript_fourth']): ?>
<div class="gap" style="height: 10px;"></div>
            <?php print render($page['postscript_fourth']); ?>

            <?php endif; ?>
			
			


<?php if ($page['bottom']): ?>
<div class="gap" style="height: 10px;"></div>
            <?php print render($page['bottom']); ?>
            <?php endif; ?>				
            
            
            	
		</div>
  <?php endif; ?>


	</div>
	<!-- END CONTENT 
	============================================= -->

	<!-- FOOTER 
	============================================= -->
	<div class="footer">
		<!-- Widget Area -->
		<div class="b-widgets">
			<div class="layout">
				<div class="row">
					
					<div class="row-item col-1_4">
						
                    <?php if ($page['footer_firstcolumn']): ?>
                    <?php print render($page['footer_firstcolumn']); ?>
                    <?php endif; ?>
					</div>
					
					<div class="row-item col-1_4">
						<?php if ($page['footer_secondcolumn']): ?>
                    <?php print render($page['footer_secondcolumn']); ?>
                    <?php endif; ?>

					</div>
					
					
					<div class="row-item col-1_4">
						  
                    <?php if ($page['footer_thirdcolumn']): ?>
                    <?php print render($page['footer_thirdcolumn']); ?>
                    <?php endif; ?>

					</div>
			
					
					<div class="row-item col-1_4">
						
                    <?php if ($page['footer_fourthcolumn']): ?>
                    <?php print render($page['footer_fourthcolumn']); ?>
                    <?php endif; ?>
					</div>
					
					<div class="row-item col-1_1">
						
                    <?php if ($page['footer-c']): ?>
                    <?php print render($page['footer-c']); ?>
                    <?php endif; ?>
					</div>

				</div>
			</div>
		</div>
	
		<div class="b-copyright">
			<div class="layout">
				<div class="f-a">
            <?php if ($page['footer-a']): ?>
            <?php print render($page['footer-a']); ?>
            <?php endif; ?>
            </div>
             <div class="f-b">
            <?php if ($page['footer-b']): ?>
            <?php print render($page['footer-b']); ?>
            <?php endif; ?>

			</div>
		</div>
	</div>
	<!-- END FOOTER 
	============================================= -->
 
</div>
<!-- END MAIN 
============================================= -->
