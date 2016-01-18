<?php
?>
<li>
	<div class="comment">
	<div class="comment-text">
  		<?php if ($new) : ?>
    			<span class="new"><?php print drupal_ucfirst($new) ?></span>
  		<?php endif; ?>

      		<?php hide($content['links']); print render($content); ?>
	</div>
	<div class="sender">
    		<?php print $submitted ?>
  <?php print render($content['links']) ?>
	</div>
</li>

