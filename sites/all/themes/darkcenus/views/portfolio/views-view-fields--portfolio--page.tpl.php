                  <li>
               <div class="pic_hover">
<div class="rounded"></div>
                  <a class="image" href="<?php print file_create_url($row->_field_data['nid']['entity']->field_home_popup_pic['und'][0]['uri']);?>" rel="prettyPhoto[gallery]" >
                                  <span class="rollover"></span><img src="<?php print image_style_url('portfolio', $row->_field_data['nid']['entity']->field_home_pic['und'][0]['uri']);?>" alt=""/></a>
                </div>
                   <div class="plus"></div>
                <div class="hide_content">
                  <div class="inner"><?php print $fields['body']->content; ?>
		<?php if ($fields['field_link_url']->content) : ?>
                    <a href="<?php print $fields['field_link_url']->content; ?>" target="_blank">View Website</a><!-- website link -->
		<?php endif;?>
                    <div class="clear"></div><!-- clear div -->
                  </div><!-- close inner -->
                    <div class="min"></div><!-- minimize button -->
                </div>
              </li>

