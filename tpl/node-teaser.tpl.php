<div id="node-<?php print $node->nid; ?>" class="media teaser<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
  <?php print $picture ?>
  <?php if ($teaser && $node->field_image[0]['view']): ?>
  	<div class="pull-left">
		  <?php print $node->field_image[0]['view']; ?>
    </div>
  <?php endif; ?>

  <div class="media-body">
    <h2 class="media-heading title">
      <a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a>
    </h2>
    <div class="meta muted mb5 clear-block">
      <?php if ($submitted): ?>
        <small class="submitted"><?php print $submitted ?></small>
      <?php endif; ?>
    </div>
    <div class="content"><?php print $content ?></div>
    
    <?php if ($terms): ?>
      <small class="terms muted pull-right"><i class="icon-tags"></i> <?php print $terms ?></small>
    <?php endif;?>
    <?php print $links; ?>  
  </div>
</div>