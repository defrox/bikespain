                <div class="pic_hover">
                  <a class="image" href="<?php print file_create_url($row->_field_data['nid']['entity']->field_popup_gallery['und'][0]['uri']);?>" rel="prettyPhoto[gallery]" >
				  <span class="rollover"></span><?php print $fields['field_photo_gallery']->content;?></a>
                </div>
           	    <h6><?php print $fields['title']->content;?></h6>
       	        <div class="pf-text"><?php print $fields['body']->content;?></div>
                <div class="pf-btn"></div>
           		  <div class="clear"></div>
