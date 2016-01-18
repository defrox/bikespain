    <?php
      // We hide the comments and links now so that we can render them later.
      //hide($content['comments']);
      //hide($content['links']);
      //print render($content);
	//echo "<pre>".print_r($node,1)."</pre>";
	print views_embed_view('blog_view', 'block_1',$node->nid);
      print render($content['comments']);
    ?>
<div class="clear"></div>
</div>
