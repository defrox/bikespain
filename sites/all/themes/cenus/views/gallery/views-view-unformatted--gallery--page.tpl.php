       	<ul class="pf_gallery_3 gallery">
<?php foreach ($rows as $id => $row): ?>
	<?php $n++;?>
	<?php if ($n % 3 == 0) : ?>
        	  <li class="nomargin">
	<?php else : ?>
        	  <li>
	<?php endif;?>
	<?php print $row; ?>
              </li>
<?php endforeach; ?>
       	  </ul>
