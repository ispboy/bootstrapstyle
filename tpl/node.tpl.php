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
  
  <?php if ($wookmark): ?>
  	<div id="wookmark-container" class="rt mb2">
		  <?php print $wookmark; ?>
    </div>
  <?php endif; ?>

	<?php if ($terms): ?>
    <small class="terms muted pull-right"><i class="icon-tags"></i> <?php print $terms ?></small>
  <?php endif;?>
  
  <span class="xlarge"><?php print $links; ?></span>
</div>