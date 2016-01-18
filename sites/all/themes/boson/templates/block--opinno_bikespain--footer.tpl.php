<?php
global $base_url;
?>
<div id = "menu_footer_wrapper">
	<div id="menu_footer_inner">
		<?php /*
			$block = block_load('menu', 'menu-footer-menu');      
			print drupal_render(_block_get_renderable_array(_block_render_blocks(array($block))));        
		 ?>
		<?/*php 
			$block = block_load('menu', 'menu-footer-menu-second');      
			print drupal_render(_block_get_renderable_array(_block_render_blocks(array($block))));        
		*/ ?>
		<?php /* 
			$block = block_load('block', '4');      
			print drupal_render(_block_get_renderable_array(_block_render_blocks(array($block))));        
		*/ ?>
		<span class="copy"><?php print t('Copyright &copy; @year <a href="@site_url">@site_name</a>', array('@year'=>date('Y'),'@site_url'=>$base_url,'@site_name'=>variable_get('site_name', 'Bike Spain'))); ?> <span class="address"> <i class="icon-map-marker"></i> <?php print variable_get('company_address', 'Plaza de la Villa 1, 28005 Madrid'); ?> <i class="icon-phone"></i> <?php print variable_get('company_phone', '+34 915 590 653'); ?> <i class="icon-envelope"></i> <?php print variable_get('company_email', 'info@bikespain.info'); ?></span></span>
	</div>
</div>