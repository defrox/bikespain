<!DOCTYPE html>

<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>
<head>
<?php print $head; ?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php
$term = taxonomy_term_load(arg(2));
$titolo = $term->metatags['und']['title']['value'];
if ($term->metatags['und']['title']['value'] =="") {?><title><?php print $term->name; ?></title><?php }
else if ($term->metatags['und']['title']['value'] =="[term:name] | [site:name]") {?><title><?php print $term->name; ?></title><?php }
else {?><title><?php print $titolo; ?></title><?php }
?>
<?php print $styles; ?>
<?php print $scripts; ?>
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!-- END: js -->
</head>
<body class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>