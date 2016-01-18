<li>
	<div class="col-1"><?php print $fields['field_blog_picture']->content; ?></div>
	<?php $pecah = explode(" ",$fields['created']->content);?>
	<div class="col-2">
		<div class="header">
			<span class="day"><?php print $pecah[0];?></span>
                      <span class="month-year"><?php print $pecah[1];?></span>
                      <span class="comment-count"><?php print $fields['comment_count']->content; ?></span>
			<div class="clear"></div>
                    </div>
		<h5><?php print $fields['title']->content; ?></h5>
		<?php print $fields['body']->content;?>
	</div>
	<div class="line"></div>
</li>
