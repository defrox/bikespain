<div class="blog-header">
      <?php $pecah = explode(" ",$fields['created']->content);?>
                   	  <span class="day"><?php print $pecah[0];?></span>
                      <span class="month-year"><?php print $pecah[1];?></span>
                      <span class="comment-count"><?php print $fields['comment_count']->content; ?></span>
                        <div class="clear"></div>
                    </div>
               <h5><a href="blog-read.html"><?php print $fields['title']->content; ?></a>
        </h5>
               <!--img src="images/picblog_read.jpg" alt="" class="pic_blog_read" /-->
	<?php print $fields['field_blog_picture']->content; ?>
<?php print $fields['body']->content;?>
