<?php
 
/*
	Theme Name: iCompany
	Theme URI: 
	Description: iCompany multipurpose Drupal theme
	Version: 1.0
	Author: Worthapost
	Author URI: http://www.worthapost.com
*/
	
// load css files
drupal_add_css(drupal_get_path('theme', 'icompany') .'/css/bootstrap.css',  array(  'group' => 'CSS_THEME', 'weight' => 93));
drupal_add_css(drupal_get_path('theme', 'icompany') .'/style.css',  array(  'group' => 'CSS_THEME', 'weight' => 96));
//drupal_add_css(drupal_get_path('theme', 'icompany') .'/css/bootstrap-responsive.css',  array(  'group' => 'CSS_THEME', 'weight' => 99));



// load js files v@1.8.1 jquery
// drupal_add_js(drupal_get_path('theme', 'icompany') . '/js/jquery.min.js', array('type' => 'file', 'scope' => 'header', 'group' => 'JS_LIBRARY', 'weight' => 0));

// Misc
drupal_add_js(drupal_get_path('theme', 'icompany') . '/js/bootstrap.js', array('type' => 'file', 'scope' => 'footer', 'group' => 'JS_LIBRARY', 'weight' => 20));
drupal_add_js(drupal_get_path('theme', 'icompany') . '/js/tinynav.js', array('type' => 'file', 'scope' => 'header', 'group' => 'JS_LIBRARY', 'weight' => 40));


// superfish
drupal_add_js(drupal_get_path('theme', 'icompany') . '/js/hoverIntent.js', array('type' => 'file', 'scope' => 'header', 'group' => 'JS_LIBRARY', 'weight' => 25));
drupal_add_js(drupal_get_path('theme', 'icompany') . '/js/superfish.js', array('type' => 'file', 'scope' => 'header', 'group' => 'JS_LIBRARY', 'weight' => 30));
drupal_add_js(drupal_get_path('theme', 'icompany') . '/js/supersubs.js', array('type' => 'file', 'scope' => 'header', 'group' => 'JS_LIBRARY', 'weight' => 35));
drupal_add_css(drupal_get_path('theme', 'icompany') .'/css/superfish.css',  array(  'group' => 'CSS_THEME', 'weight' => 93));

// Google fonts
// Prepare Google font css 
function get_google_css_string(){
	if(theme_get_setting('google_enable') == 1) {
		$google_heading_font = theme_get_setting('heading_fonts_google');
		$google_menu_font = theme_get_setting('menu_fonts_google');
		$google_site_font = theme_get_setting('site_fonts_google');	
		$google_slider_font = theme_get_setting('slider_fonts_google');	
		$google_quote_font = theme_get_setting('quote_fonts_google');					
	    				
	    $google_heading_font = str_replace(' ', '+' , $google_heading_font);			
	    $google_menu_font = str_replace(' ', '+' , $google_menu_font);
	    $google_site_font = str_replace(' ', '+' , $google_site_font);
		$google_slider_font = str_replace(' ', '+' , $google_slider_font);
		$google_quote_font = str_replace(' ', '+' , $google_quote_font);
		
	    $google_heading_font =	$google_heading_font . ':100,200,300,400,500,600,700,subset=latin:n,i,b,bi|';	
	    $google_menu_font = $google_menu_font . ':100,200,300,400,500,600,700,subset=latin:n,i,b,bi|';
	    $google_site_font = 	$google_site_font . ':100,200,300,400,500,600,700,subset=latin:n,i,b,bi|';
	    $google_slider_font = 	$google_slider_font . ':100,200,300,400,500,600,700,subset=latin:n,i,b,bi|';
		
		
		$google_css_string = 'http://fonts.googleapis.com/css?family=' . $google_heading_font . $google_menu_font . $google_site_font . $google_slider_font . $google_quote_font;
	}
	else{
		return;
	}
	
	return $google_css_string;
}


function get_googlefont_style_code(){

	?>
	
	body.icompany, #wap-menu ul.sf-menu li span.tip a, .ei-title h3, #main_slider .slider-body, #main_slider .slider-link, .btn{
		font-family: <?php print theme_get_setting('site_fonts_google'); ?>, 'Helvetica Neue', Helvetica, Arial, sans-serif;
		font-weight: <?php print theme_get_setting('site_fonts_google_weight'); ?>;
	}
	
	h1, h2, h3, h4, h5, h6, h7, h8, .heading,  .nav-tabs, .pricing-table ul .price, .accordion-heading, .numbered-heading, .heading-list, .uc-price {
		font-family: <?php print theme_get_setting('heading_fonts_google'); ?>, 'Helvetica Neue', Helvetica, Arial, sans-serif;
		font-weight: <?php print theme_get_setting('heading_fonts_google_weight'); ?>;
	}
	
	#wap-menu ul.sf-menu li a, .tinynav{
		font-family: <?php print theme_get_setting('menu_fonts_google'); ?>, 'Helvetica Neue', Helvetica, Arial, sans-serif;
		font-weight: <?php print theme_get_setting('menu_fonts_google_weight'); ?>;
	}

	
	#main_slider{
		font-family: <?php print theme_get_setting('slider_fonts_google'); ?>, 'Helvetica Neue', Helvetica, Arial, sans-serif;
		font-weight: <?php print theme_get_setting('slider_fonts_google_weight'); ?>;
	}
	
	blockquote, .quote{
		font-family: <?php print theme_get_setting('quote_fonts_google'); ?>, Georgia,"Times New Roman",Times,serif;
		font-weight: <?php print theme_get_setting('quote_fonts_google_weight'); ?>;
	}
	
	<?php
}


// user box
function icompany_welcome_user(){
	global $user;
	$usr_path = 'user/'.$user->uid;
	$myAccount = drupal_get_path_alias($usr_path);
	$logout = drupal_get_path_alias('user/logout');
	if($user->uid)
	{
		$myAccount = l(t('My account'), $myAccount, array('title' => t('My account')));
		$signout = l(t('Sign out'), $logout);
		$output = $myAccount . ' | ' . $signout;
		return $output;
	}

	return;
}

function user_login_links(){
	global $user;
	$login = drupal_get_path_alias('user');
	$register = drupal_get_path_alias('user/register');
		if(!$user->uid){
			$login = l(t('Login'), $login, array('attributes' => array('rel' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Click here to login')));
			$register = l(t('Register'), $register, array('attributes' => array('rel' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Click here to create an account' )));
			
			$output = $login . ' | ' . $register;
			
			return $output;
		}
			
	return;
}


// Piecemakre content generater and file writer
if(theme_get_setting('slider_type')  == 'piecemaker'){
drupal_add_js(drupal_get_path('theme', 'icompany') . '/sliders/piecemaker/scripts/swfobject/swfobject.js', array('type' => 'file', 'scope' => 'header', 'group' => 'JS_DEFAULT', 'weight' => 25));
}

// Piecemaker slider stuffs
function get_piecemaker_slider_content(){
	
	// get node id from database
	$result = db_query_range('SELECT n.nid FROM {node} n WHERE n.type = :ntype AND n.status = 1 ORDER BY created DESC', 0, 5, array(':ntype' => 'slider'));
	
	$iteration_count = 1;
	$piecemaker_content = array();
	foreach ($result as $record) {
	    $node = node_load($record->nid);	
		
		// Break the loop if there is no post in slider content type
		if(!$node->nid){
			print t("Make at least one published post of Slider content tyle. Go to admin panel and add a slider content. ");
			break;
		}	
		// Title
		$title = $node->title;
		// set a variable to compare node updates
		if($iteration_count == 1){
		$current_var = variable_get('piecemaker_first_node', FALSE);
		if(!$current_var) 
		variable_set('piecemaker_first_node', $title);
		
		$already_set_var = variable_get('piecemaker_first_node', FALSE);
		$fisrt_node_var = $title;
		
		if($already_set_var != $fisrt_node_var){
		// update already set var
		variable_set('piecemaker_first_node', $title);
		}
		}
		
		// Body
		if(array_key_exists('und', $node->body)){
		$body_full = $node->body['und'][0]['safe_value'];
		$body_full = strip_tags($body_full, '<a>');
		$body = substr($body_full,0,180).'...';
		}
		
		
		// Slide Link
		if(array_key_exists('und', $node->field_slide_link)){
		$link = $node->field_slide_link['und'][0]['safe_value'];
		}
		
		// Image path
		$image = $node->field_slider_image['und'][0]['uri'];
		$image_url = image_style_url('slider', $image);	
		
		// Flash path (if flash exists)
		if(array_key_exists('und', $node->field_flash_video)){
			$flash = $node->field_flash_video['und'][0]['uri'];
			$flash_url = file_create_url($flash);
		}

		if(isset($flash_url)){
		$preview_image = base_path() . path_to_theme() . '/sliders/piecemaker/flash-preview.jpg';
			
		$piecemaker_content[] = "<Flash Source=\"$flash_url\" Title=\"$title\"><Image Source=\"$preview_image\" /></Flash>";
		
		//reset variable
		unset($flash_url);
		}
		else{
			
		if(isset($body) && isset($title) && isset($link)){
			$piecemaker_content[] = "<Image Source=\"$image_url\" Title=\"$title\"><Text><p class=\"piecemaker-caption-body\"><h2>$title</h2>$body</p></Text><Hyperlink URL=\"$link\" /></Image>";			
		}

		if(isset($title) && (isset($body) && empty($link))){
			$piecemaker_content[] = "<Image Source=\"$image_url\" Title=\"$title\"><Text><h2>$title</h2><p class=\"piecemaker-caption-body\">$body</p></Text></Image>";			
		}
		
		if(isset($title) && (empty($body) && isset($link))){
			$piecemaker_content[] = "<Image Source=\"$image_url\" Title=\"$title\"><Text><h2>$title</h2></Text><Hyperlink URL=\"$link\" /></Image>";			
		}
		
		if(isset($title) && (empty($body) && empty($link))){
			$piecemaker_content[] = "<Image Source=\"$image_url\" Title=\"$title\"><Text><h2>$title</h2></Text></Image>";			
		}
			
		}
		
		$iteration_count++;
	}
	

	// Open file and write XML stuffs in it	
	$path = $_SERVER['DOCUMENT_ROOT'];
	$path = rtrim($path, "/\\");
	$file = $path . base_path() . path_to_theme() . '/sliders/piecemaker/piecemaker.xml';

	$fp = fopen($file, 'r+');
	$fstring = fread($fp, filesize($file));	
	
	
	// if file is epmty
	if($fstring == 'empty'){
		fclose($fp);
		$fp = fopen($file, 'w+') or die("Can’t open file $file");
		
		$text = '<?xml version="1.0" encoding="utf-8"?><Piecemaker><Contents>';
		//writes first line
		$fout = fwrite($fp, $text);
		
		// writes middle content
		foreach ($piecemaker_content as  $eachContent) {
			$fout = fwrite($fp, $eachContent);
		}
		
		$endtext = '</Contents><Settings ImageWidth="1050" ImageHeight="400" LoaderColor="0x333333" InnerSideColor="0x222222" SideShadowAlpha="0.5" DropShadowAlpha="0.4" DropShadowDistance="25" DropShadowScale=".95" DropShadowBlurX="50" DropShadowBlurY="6" MenuDistanceX="20" MenuDistanceY="50" MenuColor1="0x999999" MenuColor2="0x333333" MenuColor3="0xFFFFFF" ControlSize="100" ControlDistance="20" ControlColor1="0x222222" ControlColor2="0xFFFFFF" ControlAlpha="0.8" ControlAlphaOver="0.95" ControlsX="450" ControlsY="280&#xD;&#xA;" ControlsAlign="center" TooltipHeight="30" TooltipColor="0x222222" TooltipTextY="5" TooltipTextStyle="P-Italic" TooltipTextColor="0xFFFFFF" TooltipMarginLeft="5" TooltipMarginRight="7" TooltipTextSharpness="50" TooltipTextThickness="-100" InfoWidth="400" InfoBackground="0xFFFFFF" InfoBackgroundAlpha="0.95" InfoMargin="15" InfoSharpness="0" InfoThickness="0" Autoplay="10" FieldOfView="45"></Settings><Transitions><Transition Pieces="9" Time="1.2" Transition="easeInOutBack" Delay="0.1" DepthOffset="300" CubeDistance="30"></Transition><Transition Pieces="15" Time="3" Transition="easeInOutElastic" Delay="0.03" DepthOffset="200" CubeDistance="10"></Transition><Transition Pieces="5" Time="1.3" Transition="easeInOutCubic" Delay="0.1" DepthOffset="500" CubeDistance="50"></Transition><Transition Pieces="9" Time="1.25" Transition="easeInOutBack" Delay="0.1" DepthOffset="900" CubeDistance="5"></Transition></Transitions></Piecemaker>		';
		
		// writes end lins
		$fout = fwrite($fp, $endtext);
		
		fclose($fp);
		
	}

	
	
	// checks where there is new content. If there is, update xml file
	if(($fp != 'empty') && ($already_set_var != $fisrt_node_var)){
		
		fclose($fp);
		$fp = fopen($file, 'w+') or die("Can’t open file $file");
		
		$text = '<?xml version="1.0" encoding="utf-8"?><Piecemaker><Contents>';
		
		//writes first line
		$fout = fwrite($fp, $text);
		
		// writes middle content
		foreach ($piecemaker_content as  $eachContent) {
			$fout = fwrite($fp, $eachContent);
		}
		
		$endtext = '</Contents><Settings ImageWidth="1050" ImageHeight="400" LoaderColor="0x333333" InnerSideColor="0x222222" SideShadowAlpha="0.5" DropShadowAlpha="0.4" DropShadowDistance="25" DropShadowScale=".95" DropShadowBlurX="50" DropShadowBlurY="6" MenuDistanceX="20" MenuDistanceY="50" MenuColor1="0x999999" MenuColor2="0x333333" MenuColor3="0xFFFFFF" ControlSize="100" ControlDistance="20" ControlColor1="0x222222" ControlColor2="0xFFFFFF" ControlAlpha="0.8" ControlAlphaOver="0.95" ControlsX="450" ControlsY="280&#xD;&#xA;" ControlsAlign="center" TooltipHeight="30" TooltipColor="0x222222" TooltipTextY="5" TooltipTextStyle="P-Italic" TooltipTextColor="0xFFFFFF" TooltipMarginLeft="5" TooltipMarginRight="7" TooltipTextSharpness="50" TooltipTextThickness="-100" InfoWidth="400" InfoBackground="0xFFFFFF" InfoBackgroundAlpha="0.95" InfoMargin="15" InfoSharpness="0" InfoThickness="0" Autoplay="10" FieldOfView="45"></Settings><Transitions><Transition Pieces="12" Time="2" Transition="easeInOutBack" Delay="0.1" DepthOffset="300" CubeDistance="30"></Transition><Transition Pieces="15" Time="3" Transition="easeInOutElastic" Delay="0.03" DepthOffset="200" CubeDistance="10"></Transition><Transition Pieces="5" Time="2" Transition="easeInOutCubic" Delay="0.1" DepthOffset="500" CubeDistance="50"></Transition><Transition Pieces="9" Time="1.25" Transition="easeInOutBack" Delay="0.1" DepthOffset="900" CubeDistance="5"></Transition></Transitions></Piecemaker>		';

		
		// writes end lins
		$fout = fwrite($fp, $endtext);
		
		fclose($fp);
	}


}

// main menu preprocess
function icompany_menu_link(array $variables) {
	$element = $variables['element'];
	$sub_menu = '';
	
	if ($element['#below']) {
	  $sub_menu = drupal_render($element['#below']);
	}
	  
	if($variables['element']['#original_link']['menu_name'] == 'main-menu' && $variables['element']['#original_link']['depth'] == 1){
			
		if(array_key_exists('attributes', $variables['element']['#original_link']['options']) && array_key_exists('title', $variables['element']['#original_link']['options']['attributes'])){
			 $item_url = drupal_get_path_alias($element['#href']);
			 $output = l($element['#title'], $element['#href'], $element['#localized_options']) . '<span class="tip hidden-phone hidden-tablet"> <a href="'. $item_url . '">' . $variables['element']['#original_link']['options']['attributes']['title'] .'</a></span>';	
		}
		else {
			$output = l($element['#title'], $element['#href'], $element['#localized_options']) . '<span class="tip hidden-phone hidden-tablet"> </span>';
		}
		
	return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n"; 
	}
	  
	$output = l($element['#title'], $element['#href'], $element['#localized_options']);
	return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
 
// menus customization
function icompany_menu_tree__main_menu($variables) {
 	return '<ul id="nav" class="sf-menu">' . $variables['tree'] . '</ul>';
}


// count blocks and return number of blocks
function count_blocks($block1 = NULL, $block2 = NULL, $block3 = NULL, $block4 = NULL, $block5 = NULL,  $block6 = NULL)
{
	$to_count = array();

	if(!empty($block1))
	{
		array_push($to_count, 1);
	}

	if(!empty($block2))
	{
		array_push($to_count, 2);
	}

	if(!empty($block3))
	{
		array_push($to_count, 3);
	}
	
	if(!empty($block4))
	{
		array_push($to_count, 4);
	}
	
	if(!empty($block5))
	{
		array_push($to_count, 5);
	}
	
	if(!empty($block6))
	{
		array_push($to_count, 6);
	}


	$num = count($to_count);
	
	return $num;
}


// count blocks and return span* automatically. 12 grid is divided by number of blocks. 5 blocks will be an error coz 12 cannot be divided by 5. 6 is ok
function get_block_span($block1 = NULL, $block2 = NULL, $block3 = NULL, $block4 = NULL, $block5 = NULL,  $block6 = NULL)
{
	$to_count = array();

	if(!empty($block1))
	{
		array_push($to_count, 1);
	}

	if(!empty($block2))
	{
		array_push($to_count, 2);
	}

	if(!empty($block3))
	{
		array_push($to_count, 3);
	}
	
	if(!empty($block4))
	{
		array_push($to_count, 4);
	}
	
	if(!empty($block5))
	{
		array_push($to_count, 5);
	}
	
	if(!empty($block6))
	{
		array_push($to_count, 6);
	}

	$num = count($to_count);

	if ($num == 5) {
		return ' span2 '; // since 12 grids cannot be divided by 5, we use span2
	}
	
	$span = 12/$num;
	
	return ' span'. $span . ' ';
}

// Returns true if any column block exist else false

function region_blocks_exist($block1 = NULL, $block2 = NULL, $block3 = NULL, $block4 = NULL, $block5 = NULL,  $block6 = NULL){
	
	if(!empty($block1) || !empty($block2) ||  !empty($block3) || !empty($block4) || !empty($block5) || !empty($block6) )	{
		return TRUE;
	}
	
	else{
		return FALSE;
	}
}

/**
 * Convert a hexa decimal color code to its RGB equivalent
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */                                                                                                
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
	

    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

/**
 * Convert a hexa decimal color to lighter or darker color
*/
function colorInterpolate($hex, $percent) {
	// Work out if hash given
	$hash = '';
	if (stristr($hex, '#')) {
		$hex = str_replace('#', '', $hex);
		$hash = '#';
	}
	/// HEX TO RGB
	$rgb = array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
	//// CALCULATE
	for ($i = 0; $i < 3; $i++) {
		// See if brighter or darker
		if ($percent > 0) {
			// Lighter
			$rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
		} else {
			// Darker
			$positivePercent = $percent - ($percent * 2);
			$rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1 - $positivePercent));
		}
		// In case rounding up causes us to go to 256
		if ($rgb[$i] > 255) {
			$rgb[$i] = 255;
		}
	}
	//// RBG to Hex
	$hex = '';
	for ($i = 0; $i < 3; $i++) {
		// Convert the decimal digit to hex
		$hexDigit = dechex($rgb[$i]);
		// Add a leading zero if necessary
		if (strlen($hexDigit) == 1) {
			$hexDigit = "0" . $hexDigit;
		}
		// Append to the hex string
		$hex .= $hexDigit;
	}
	return $hash . $hex;
}


// Custom breadcrumb
function icompany_breadcrumb($breadcrumb) {
	    
    $bc = $breadcrumb['breadcrumb'];
	if (!empty($bc)) {
		return implode(' / ', $bc) ;
	}
}

function breadcrumb_title($br)
{
	if(strlen($br) > 22){
		$new_br = substr($br, 0, 22);
		$new_br2 = str_pad($new_br, 26, ' ...', STR_PAD_RIGHT);
		$new_br3 = strip_tags($new_br2);
		return $new_br3;
	}
	else

	return $br;
}

 
// Preprocess Page
function icompany_preprocess_page(&$variables) {
  if (isset($variables['secondary_menu'])) {
    $variables['secondary_nav'] = theme('links__system_secondary_menu', array(
      'links' => $variables['secondary_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'secondary-menu'),
      ),
      'heading' => array(
        'text' => t('Secondary menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  else {
    $variables['secondary_nav'] = FALSE;
  }
}


// Customized tabs
function icompany_menu_local_tasks(&$variables) {
  $output = '';

  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
    $variables['primary']['#prefix'] .= '<ul class="btn-group tabs">';
    $variables['primary']['#suffix'] = '</ul>';
    $output .= drupal_render($variables['primary']);
  }
  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
    $variables['secondary']['#prefix'] .= '<ul class="btn-group">';
    $variables['secondary']['#suffix'] = '</ul>';
    $output .= drupal_render($variables['secondary']);
  }

  return $output;
}

function icompany_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];

  if (!empty($variables['element']['#active'])) {
    // Add text to indicate active tab for non-visual users.
    $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';

    // If the link does not contain HTML already, check_plain() it now.
    // After we set 'html'=TRUE the link will not be sanitized by l().
    if (empty($link['localized_options']['html'])) {
      $link['title'] = check_plain($link['title']);
    }
    $link['localized_options']['html'] = TRUE;
    $link_text = t('!local-task-title!active', array('!local-task-title' => $link['title'], '!active' => $active));
  }

  return '<li' . (!empty($variables['element']['#active']) ? ' class="active btn"' : ' class="btn"') . '>' . l($link_text, $link['href'], $link['localized_options']) . "</li>\n";
}

// customize node submitted text
function icompany_preprocess_node(&$variables) {
  $print_task = t('Print this page');
  if ($variables['submitted']) {
    $variables['submitted'] = t('<span class="icon-calendar"></span> on !datetime &nbsp; <span class=" icon-user"></span> !username <a class="hidden-phone hidden-tablet node-print pull-right" style="line-height:10px;"  data-original-title="!print_text" data-placement="top" rel="tooltip" href="javascript:window.print()"><span class="icon-print"></span></a>', array('!username' => $variables['name'], '!datetime' => $variables['date'], '!print_text' => $print_task));
  }
  
 
  //customize readmore, comments and comment add    
  if(array_key_exists('content', $variables) && array_key_exists('links', $variables['content']) && array_key_exists('comment', $variables['content']['links']) && array_key_exists('comment-new-comments', $variables['content']['links']['comment']['#links'])){
    $variables['content']['links']['comment']['#links']['comment-new-comments']['attributes']['class'] = array('btn',  'btn-small');
  }

  if(array_key_exists('content', $variables) && array_key_exists('links', $variables['content']) && array_key_exists('comment', $variables['content']['links']) && array_key_exists('comment-comments', $variables['content']['links']['comment']['#links'])){
  	$variables['content']['links']['comment']['#links']['comment-comments']['title'] = '<span class="icon-comment"></span> &nbsp;' . $variables['content']['links']['comment']['#links']['comment-comments']['title'] ;
    $variables['content']['links']['comment']['#links']['comment-comments']['attributes']['class'] = array('btn',  'btn-small');
  }
  
  if(array_key_exists('content', $variables) && array_key_exists('links', $variables['content']) && array_key_exists('comment', $variables['content']['links']) && array_key_exists('comment-add', $variables['content']['links']['comment']['#links'])){
 	$variables['content']['links']['comment']['#links']['comment-add']['html'] = 1;
 	$variables['content']['links']['comment']['#links']['comment-add']['title']  = '<span class="icon-plus"></span> &nbsp;' . $variables['content']['links']['comment']['#links']['comment-add']['title']  ;   
    $variables['content']['links']['comment']['#links']['comment-add']['attributes']['class'] = array('btn', 'btn-small');
  }
  
  if(array_key_exists('content', $variables) && array_key_exists('links', $variables['content']) && array_key_exists('node', $variables['content']['links']) && array_key_exists('node-readmore', $variables['content']['links']['node']['#links'])){
    $variables['content']['links']['node']['#links']['node-readmore']['attributes']['class'] = array('btn', 'btn-theme', 'btn-small');
  }
  
	
}

//shortcodes

function icompany_preprocess_field(&$variables) {
	
	if(array_key_exists('element', $variables) && array_key_exists("#field_name", $variables['element'])){
		if($variables['element']['#field_name'] == 'body'){
			if(array_key_exists('items', $variables)  && array_key_exists('0', $variables['items'])  && array_key_exists('#markup', $variables['items']['0'])){
				
				$string = $variables['items']['0']['#markup'];	
				
				// replace	
				$string = str_replace('[row]', '<div class="row-fluid">', $string);
				$string = str_replace('[/row]', '</div>', $string);
				
				$string = str_replace('[one]', '<div class="span12">', $string);
				$string = str_replace('[/one]', '</div>', $string);
					 
				$string = str_replace('[oneHalf]', '<div class="span6">', $string);
				$string = str_replace('[/oneHalf]', '</div>', $string);
				
				$string = str_replace('[oneThird]', '<div class="span4">', $string);
				$string = str_replace('[/oneThird]', '</div>', $string);
				
				$string = str_replace('[oneFourth]', '<div class="span3">', $string);
				$string = str_replace('[/oneFourth]', '</div>', $string);
				
				$string = str_replace('[oneFifth]', '<div class="span2">', $string);
				$string = str_replace('[/oneFifth]', '</div>', $string);
				
				$string = str_replace('[oneSixth]', '<div class="span2">', $string);
				$string = str_replace('[/oneSixth]', '</div>', $string);
				
				$string = str_replace('[twoThird]', '<div class="span8">', $string);
				$string = str_replace('[/twoThird]', '</div>', $string);
				
				$string = str_replace('[threeFourth]', '<div class="span9">', $string);
				$string = str_replace('[/threeFourth]', '</div>', $string);
				
				$string = str_replace('[fiveSixth]', '<div class="span10">', $string);
				$string = str_replace('[/fiveSixth]', '</div>', $string);
				
				$string = str_replace('[color]', '<span class="color">', $string);
				$string = str_replace('[/color]', '</span>', $string);
				
				$string = str_replace('[h1 bordered]', '<h1 class="bordered">', $string);
				$string = str_replace('[/h1]', '</h1>', $string);
				
				$string = str_replace('[h2 bordered]', '<h2 class="bordered">', $string);
				$string = str_replace('[/h2]', '</h2>', $string);
				
				$string = str_replace('[h3 bordered]', '<h3 class="bordered">', $string);
				$string = str_replace('[/h3]', '</h3>', $string);
				$variables['items']['0']['#markup'] = $string;
			}
		}
	}
	
 
}

// customize comment submitted text
function icompany_preprocess_comment(&$variables) {
    $created = $variables['created'];
    $author = $variables['author'];
    $variables['submitted'] = t("on !date by !author", array('!date' => $created, '!author' => $author));
}


function icompany_preprocess_table(&$variables){
	$variables['attributes']['class'] = array(' table table-striped ');
}

function icompany_preprocess_button(&$variables) {
  $variables['element']['#attributes']['class'][] = ' btn ';
}

function icompany_preprocess_simplenews_block(&$vars){
	$vars['form']['mail']['#title'] = '';
	$vars['form']['mail']['#attributes'] = array('placeholder' => 'E-mail…');
	$vars['form']['mail']['#size'] = 27;
}



//status messages
function icompany_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'), 
    'error' => t('Error message'), 
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
	switch ($type) {
		case 'status':
			$alert_type = '  alert-success alert-block ';
			break;
		case 'warning':
			$alert_type = '  alert-block ';
			break;
		case 'error':
			$alert_type = '  alert-error alert-block ';
			break;
		default:
			$alert_type = '  alert-block ';
			break;
	}
	
    $output .= "<div class=\" alert $alert_type\">\n <button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>\n";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }
  return $output;
}