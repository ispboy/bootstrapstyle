<div class="comment media<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status ?> clear-block">

  <div class="pull-left"><?php print $picture ?></div>

	<div class="media-body">

  	<div class="pull-right"><?php print $links ?></div>

    <h5 class="submitted media-heading muted">
			<?php print $submitted ?>
			<?php if ($comment->new): ?>
        <span class="badge badge-important"><?php print $new ?></span>
      <?php endif; ?>
    </h5>


    <div class="content">
      <?php print $content ?>
      <?php if ($signature): ?>
      <div class="user-signature clear-block">
        <?php print $signature ?>
      </div>
      <?php endif; ?>
    </div>
  
  </div><!-- //.section-->
</div>