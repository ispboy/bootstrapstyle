<!DOCTYPE html>
<html lang="en">
<head>
<?php print $head; ?>
<title><?php print $head_title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<?php print $styles; ?>
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

<!--[if lt IE 7]>
  <link href="<?php print $base_path.$directory; ?>/lib/ie6.min.css" rel="stylesheet">
<![endif]-->  

</head>

<body class="<?php print $body_classes; ?>">

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
			<?php if (!empty($site_name)): ?>
	      <a id="site-name" class="brand" href="<?php print $front_page ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
      <?php endif; ?>
      <div class="nav-collapse collapse">
        <?php print theme('links', $primary_links, array('class' => 'nav primary-links')); ?>
				<?php if (!empty($search_box)): ?>
          <div id="search-box"><?php print $search_box; ?></div>
        <?php endif; ?>

        <?php print $user_nav_links; ?>

      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div id="container" class="container">

  <?php if (!empty($hero)): ?>
    <div id="hero-region" class="hero-unit hidden-phone">
      <?php print $hero; ?>
    </div> <!-- /hero-region -->
  <?php endif; ?>

	<div class="row">
  <?php if (!empty($left)): ?>
    <div id="sidebar-left" class="column sidebar span3 hidden-phone">
      <?php print $left; ?>
    </div> <!-- /sidebar-left -->
  <?php endif; ?>

  <div id="main" class="column <?php print $span_main; ?>"><div id="main-squeeze">
    <?php if (!empty($breadcrumb)): ?><div id="breadcrumb"><?php print $breadcrumb; ?></div><?php endif; ?>
    <?php if (!empty($mission)): ?><div id="mission" class="alert alert-info"><?php print $mission; ?></div><?php endif; ?>

		<?php if (!empty($hightlighted)): ?>
      <div id="hightlighted-region" class="row-fluid hidden-phone">
        <?php print $hightlighted; ?>
      </div> <!-- /hightlighted-region -->
    <?php endif; ?>
  
    <div id="content">
      <?php if (!empty($tabs)): ?><div class="tabs"><?php print $tabs; ?></div><?php endif; ?>
      <?php if (!empty($title)): ?><h1 class="page-header" id="page-title"><?php print $title; ?></h1><?php endif; ?>
      <?php if (!empty($messages)): print $messages; endif; ?>
      <?php if (!empty($help)): print $help; endif; ?>
      <div id="content-content" class="clear-block">
        <?php print $content; ?>
      </div> <!-- /content-content -->
      <?php print $feed_icons; ?>
    </div> <!-- /content -->

  </div></div> <!-- /main-squeeze /main -->

  <?php if (!empty($right)): ?>
    <div id="sidebar-right" class="column sidebar span3">
      <?php print $right; ?>
    </div> <!-- /sidebar-right -->
  <?php endif; ?>

	</div> <!-- /.row -->
</div> <!-- /container -->
    
<footer class="footer">
  <div class="container">
    <hr />
    <?php print $footer_message; ?>
    <?php if (!empty($footer)): print $footer; endif; ?>
  </div> <!-- /container -->
</footer>

<?php print $scripts; ?>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

  <?php print $closure; ?>
</body>
</html>
