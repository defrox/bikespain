<?php
?>

<div id="wrapper">
	<div id="inside">
	<div id="sidebar">
    	<div id="social-icons"><?php print render($page['socialicons']); ?></div><!-- close div social-icons -->        
        
	<div id="smoothmenu" class="mainmenu">
        <?php if (isset($main_menu)) : ?>
			<?php print(render(custom_menu_tree('main-menu')));?>
        <?php /** print theme('links__system_main_menu', array(
          'links' => $main_menu,
          'attributes' => array(
            'id' => 'main-menu-links',
            'class' => array('links', 'clearfix'),
          ),
          'heading' => array(
            'text' => t('Main menu'),
            'level' => 'h2',
            'class' => array('element-invisible'),
          ),
        )); **/ ?>
        <?php endif; ?>
        </div><!-- close div mainmenu -->
        <div class="inner">
        </div>
    </div><!-- close div sidebar -->
    
    <div id="main">
    	<div id="header">
    		<div id="mainlogo"><a href="<?php print $front_page ?>"><img src="<?php print $logo ?>" alt="" /></a></div>        
		<?php print render($page['header']); ?>
        	<div class="clear"></div>
        </div>
        
        <div id="content">        	     
		<?php if ($page['topcontent']): ?><?php print render($page['topcontent']);?><?php endif; ?>
          <?php //print $breadcrumb; ?>
		  
          <?php if ($tabs): ?><div id="tabs-wrapper" class="clearfix"><?php endif; ?>
          <?php print render($title_prefix); ?>
          <?php if ($title && !in_array(@$node->type, array('page','blog'))): ?>
            <h1<?php print $tabs ? ' class="with-tabs"' : '' ?>><?php print $title ?></h1>
          <?php endif; ?>
          <?php print render($title_suffix); ?>
          <?php if ($tabs): ?><?php print render($tabs); ?></div><?php endif; ?>
          <?php print render($tabs2); ?>
          <?php print $messages; ?>
          <?php print render($page['help']); ?>
          <?php if ($action_links && $_GET['q'] != "blog"): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
          <div class="clearfix">
            <?php print render($page['content']); ?>
          </div>
</div><!-- close div content -->
        
        <div class="clear"></div>
        
        <div id="footer">
			<?php print render($page['footer']); ?>
       	  <div class="clear"></div>
        </div>
        
    </div><!-- close div main -->
    <div class="clear"></div>
    </div><!-- close div inside -->  
</div><!-- close div wrapper -->    
