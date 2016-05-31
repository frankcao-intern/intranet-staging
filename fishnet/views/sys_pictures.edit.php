<?php
/**
 * Created by: cravelo
 * Date: 9/13/11
 * Time: 9:41 AM
 */
 ?>

<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>

<?php $this->load->view('page_parts/image_stack', array('width' => 760, 'height' => 471)); ?>
