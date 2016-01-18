<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>

<head profile="<?php print $grddl_profile; ?>">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php print $head ?>   
    <title><?php print $head_title ?></title>
    
    <?php print $styles ?>
    
    <!-- GOOGLE FONTS CSS --> 
    <?php if(theme_get_setting('google_enable') == 1){ ?>
    <link href='<?php print get_google_css_string() ?>' rel='stylesheet' type='text/css'/> 
    <?php } ?>
    
    <?php  include_once drupal_get_path('theme', 'icompany') . "/includes/css_include.php"; ?>  

    <!--[if lt IE 7]>
    <![endif]-->
    
    <!--[if lt IE 9]>
    	<style type="text/css">
		#page-wrapper, .sf-shadow ul {
			behavior: url(<?php print base_path() . path_to_theme() ?>/js/PIE.htc);
		}
		</style>
	<![endif]-->
	

    <?php print $scripts ?>
 
    <!-- Slider stuffs -->
    <?php if(theme_get_setting('slider_type')  == 'piecemaker'){ get_piecemaker_slider_content();?>
	<!--Piecemaker-->
	     <script type="text/javascript">
	      var flashvars = {};
	      flashvars.cssSource = "<?php print base_path() . path_to_theme() . '/sliders/piecemaker/piecemaker.css'; ?>";
	      flashvars.xmlSource = "<?php print base_path() . path_to_theme() . '/sliders/piecemaker/piecemaker.xml'; ?>";
			
	      var params = {};
	      params.play = "true";
	      params.menu = "false";
	      params.scale = "showall";
	      params.wmode = "transparent";
	      params.allowfullscreen = "true";
	      params.allowscriptaccess = "always";
	      params.allownetworking = "all";
		  
	      swfobject.embedSWF('<?php print base_path() . path_to_theme() . '/sliders/piecemaker/piecemaker.swf'; ?>', 'piecemaker', '1150', '480', '10', null, flashvars,    
	      params, null);
	    
	      </script>
	<?php } ?>
    
    <?php if(theme_get_setting('slider_type') == 'elastic') { ?>
  	 <!-- Elastic Slider -->
		<noscript>
			<link rel="stylesheet" type="text/css" href="<?php print base_path() .  path_to_theme() ?>/sliders/elastic/css/noscript.css" />
		</noscript>				
				 						
	<?php } ?>
    
    <!-- END//Slider stuffs -->
    
    
    <!--img preloader-->
    <script type="text/javascript">(function ($) { $.fn.preloader=function(options){var defaults={delay:400,preload_parent:"a",check_timer:300,ondone:function(){},oneachload:function(image){},fadein:500};var options=$.extend(defaults,options),root=$(this),images=root.find("img").css({"visibility":"hidden",opacity:0}),timer,counter=0,i=0,checkFlag=[],delaySum=options.delay,init=function(){timer=setInterval(function(){if(counter>=checkFlag.length){clearInterval(timer);options.ondone();return}for(i=0;i<images.length;i++){if(images[i].complete==true){if(checkFlag[i]==false){checkFlag[i]=true;options.oneachload(images[i]);counter++;delaySum=delaySum+options.delay}$(images[i]).css("visibility","visible").delay(delaySum).animate({opacity:1},options.fadein,function(){$(this).parent().removeClass("preloader")})}}},options.check_timer)};images.each(function(){if($(this).parent(options.preload_parent).length==0)$(this).wrap("<a class='preloader' />");else $(this).parent().addClass("preloader");checkFlag[i++]=false});images=$.makeArray(images);var icon=jQuery("<img />",{id:'loadingicon',src:'<?php print base_path() . path_to_theme() ?>/img/89.gif'}).hide().appendTo("body");timer=setInterval(function(){if(icon[0].complete==true){clearInterval(timer);init();icon.remove();return}},100)} })(jQuery);</script>
    
    <!--script executions -->
    <script type="text/javascript">
	//superfish initialize	
	(function ($) {
    	jQuery(function(){
    		jQuery('ul.sf-menu').superfish();
    	});
    
    	//superfish > supersubs
        $(document).ready(function(){ 
            $("ul.sf-menu").supersubs({ 
                minWidth:    12,   // minimum width of sub-menus in em units 
                maxWidth:    27,   // maximum width of sub-menus in em units 
                extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                                   // due to slight rounding differences and font-family 
            }).superfish();  // call supersubs first, then superfish, so that subs are 
                             // not display:none when measuring. Call before initialising 
                             // containing tabs for same reason. 
    	
    	
    		 //image preloader 
    		$(function(){	
    			$(".portfolio-image").preloader();		
    			$(".fancy-preload").preloader();	
    			$(".node .field-type-image").preloader();	
    		});
    	
    	}); 
	})(jQuery);
	
	// Tiny nav
	(function ($) {
    	$(function () {
		    $("#nav").tinyNav();
		  });
	})(jQuery);
	
	//back to top
	(function ($) {
    	$(function() {
    		$(window).scroll(function() {
    			if($(this).scrollTop() != 0) {
    				$('#toTop').fadeIn();	
    			} else {
    				$('#toTop').fadeOut();
    			}
    		});
    	 
    		$('#toTop').click(function() {
    			$('body,html').animate({scrollTop:0},800);
    		});	
    	});
    })(jQuery);
    </script>
    
</head>
<body class="<?php print $classes; ?> icompany"  <?php print $attributes;?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
  
  
<!-- Bootstrap scripts -->
<script type="text/javascript">  
(function ($) {
  $(document).ready(function () {  
    $("[rel=tooltip]").tooltip();  
    $('.carousel').carousel()
    $("a[rel=popover]").popover();
  }); 
   
})(jQuery);
</script>
 
</body>
</html>