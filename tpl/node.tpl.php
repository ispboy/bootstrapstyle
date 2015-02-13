<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">

<?php print $picture ?>

<?php if (!$page): ?>
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

  <div class="meta muted mb2 clear-block">
  <?php if ($submitted): ?>
    <span class="submitted"><?php print $submitted ?></span>
  <?php endif; ?>
  </div>

  <div class="content clear-block">
    <?php print $content ?>
  </div>
  
  <?php if ($zrssfeed): ?>
    <div class="zrssfeed">
      <?php print $zrssfeed; ?>
    </div>
  <?php endif; ?>
  
  <?php if ($wookmark): ?>
  	<div id="wookmark-container" class="rt mb2">
		  <?php print $wookmark; ?>
    </div>
  <?php endif; ?>

	<?php if ($terms): ?>
    <small class="terms muted pull-right"><i class="icon-tags"></i> <?php print $terms ?></small>
  <?php endif;?>
  
  <span class="xlarge"><?php print $links; ?></span>
  <?php
    // If enabled, show new comment form if it's not already being displayed.
/*    $reply = arg(0) == 'comment' && arg(1) == 'reply';
    if (user_access('post comments') && node_comment_mode($node->nid) == COMMENT_NODE_READ_WRITE && (variable_get('comment_form_location_' . $node->type, COMMENT_FORM_SEPARATE_PAGE) == COMMENT_FORM_SEPARATE_PAGE) && !$reply) {
      $output = comment_form_box(array('nid' => $node->nid), t('Post new comment'));
      echo $output;
    }*/
  ?>
  
</div>