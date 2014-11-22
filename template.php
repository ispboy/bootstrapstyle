<?php
/**
*	bootstrapstyle
**/

function bootstrapstyle_preprocess_page(&$variables) {
//	dpr(array_keys($variables));

	//decide the main span, left=3, right=4, main=12-left-right;
	$i = 12;
	if (!empty($variables['left'])) {
		$i -= 3;
	}
	if (!empty($variables['right'])) {
		$i -= 3;
	}
	$variables['span_main'] = 'span'.strval($i);
	// if (!empty($variables['left'])) $variables['span_main'] .= ' offset1';


  $variables['user_nav_links'] = _bootstrapstyle_user_nav_links();
	
	_bootstrapstyle_icon_menu($variables['primary_links'], False);
	
}

/**
 * Process variables for node.tpl.php
 *
 * Most themes utilize their own copy of node.tpl.php. The default is located
 * inside "modules/node/node.tpl.php". Look in there for the full list of
 * variables.
 *
 * The $variables array contains the following arguments:
 * - $node
 * - $teaser
 * - $page
 *
 * @see node.tpl.php
 */
function bootstrapstyle_preprocess_node(&$vars) {
  $node = $vars['node'];
  $links = $node->links;
  if ($links) {
    foreach ($links as $key => $link) {
      _bootstrapstyle_icon_link($links[$key], $key);
    }
    $vars['links'] = !empty($links) ? theme('links', $links, array('class' => 'links inline')) : '';
  }

	//teaser template
  if ($vars['teaser']) {
    $vars['template_files'][] = 'node-teaser';
    $vars['template_files'][] = 'node-teaser-'. $vars['type'];
  }
	
	//wookmark
	$items = array();
	if ($vars['page'] && $node->field_image && $node->field_show_images && $node->field_show_images[0]['value'] == 1) {
		foreach($node->field_image as $image) {
			$items[] = $image['view'];
		}
		if ($items) {
			drupal_add_js(drupal_get_path('theme', 'bootstrapstyle'). '/lib/jquery.wookmark.min.js');
			drupal_add_js('
				$(window).load(function(){
					$("#wookmark li").wookmark({
						autoResize:true, itemWidth:265, container:$("#wookmark-container"),
						offset:2, outerOffset:3
					});
				});
			', 'inline');
			$attributes = array('id' => 'wookmark');
			$vars['wookmark'] = theme('item_list', $items, NULL, 'ul', $attributes);
		}
	}
}

/**
* Implementation of theme_filter_tips_more_info().
* Used here to hide the "More information about formatting options" link.
*/
function bootstrapstyle_filter_tips_more_info() {
  return '';
}

/*
* Override filter.module's theme_filter_tips() function to disable tips display.
*/
function bootstrapstyle_filter_tips($tips, $long = FALSE, $extra = '') {
  return '';
}

/**
* Implementation of hook_theme.
*
* Register custom theme functions.
*/
function bootstrapstyle_theme() {
  return array(
    // The form ID.
    'user_login_block' => array(
			'template' => 'user-login-block',
			'path' => drupal_get_path('theme', 'bootstrapstyle') .'/tpl',
      // Forms always take the form argument.
      'arguments' => array('form' => NULL),
    ),
  );
}


/*
*	Breadcrumb
*/
function bootstrapstyle_breadcrumb($breadcrumb) {
	$i = count($breadcrumb);
  if ($i > 1) {
    return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
  }
}

/*
*	form buttons
*/
function bootstrapstyle_button($element) {
  // Make sure not to overwrite classes.
  if (isset($element['#attributes']['class'])) {
    $element['#attributes']['class'] = 'btn form-' . $element['#button_type'] . ' ' . $element['#attributes']['class'];
  }
  else {
    $element['#attributes']['class'] = 'btn form-' . $element['#button_type'];
  }

  return '<input type="submit" ' . (empty($element['#name']) ? '' : 'name="' . $element['#name'] . '" ') . 'id="' . $element['#id'] . '" value="' . check_plain($element['#value']) . '" ' . drupal_attributes($element['#attributes']) . " />\n";
}

function bootstrapstyle_submit($element) {
	if(strpos($element['#id'], 'edit-submit') !== FALSE) {
		if (isset($element['#attributes']['class'])) {
			$element['#attributes']['class'] = 'btn-primary ' . $element['#attributes']['class'];
		}
		else {
			$element['#attributes']['class'] = 'btn-primary';
		}
	}
	return theme('button', $element);
}


/*
*	Local tasks tabs
*/

function bootstrapstyle_menu_local_tasks() {
  $output = '';

  if ($primary = menu_primary_local_tasks()) {
    $output .= "<ul class=\"nav nav-tabs\">\n" . $primary . "</ul>\n";
  }
  if ($secondary = menu_secondary_local_tasks()) {
    $output .= "<ul class=\"nav nav-pills\">\n" . $secondary . "</ul>\n";
  }

  return $output;
}

/*
*	<table class="table...
*/

function bootstrapstyle_table($header, $rows, $attributes = array(), $caption = NULL) {
  // Add sticky headers, if applicable.
  if (count($header)) {
    drupal_add_js('misc/tableheader.js');
    // Add 'sticky-enabled' class to the table to identify it for JS.
    // This is needed to target tables constructed by this function.
    $attributes['class'] = empty($attributes['class']) ? 'sticky-enabled' : ($attributes['class'] . ' sticky-enabled');
  }

  $attributes['class'] = empty($attributes['class']) ? 'table table-striped' : ($attributes['class'] . ' table table-striped');

	$output = '<table' . drupal_attributes($attributes) . ">\n";

  if (isset($caption)) {
    $output .= '<caption>' . $caption . "</caption>\n";
  }

  // Format the table header:
  if (count($header)) {
    $ts = tablesort_init($header);
    // HTML requires that the thead tag has tr tags in it followed by tbody
    // tags. Using ternary operator to check and see if we have any rows.
    $output .= (count($rows) ? ' <thead><tr>' : ' <tr>');
    foreach ($header as $cell) {
      $cell = tablesort_header($cell, $header, $ts);
      $output .= _theme_table_cell($cell, TRUE);
    }
    // Using ternary operator to close the tags based on whether or not there are rows
    $output .= (count($rows) ? " </tr></thead>\n" : "</tr>\n");
  }
  else {
    $ts = array();
  }

  // Format the table rows:
  if (count($rows)) {
    $output .= "<tbody>\n";
    $flip = array(
      'even' => 'odd',
      'odd' => 'even',
    );
    $class = 'even';
    foreach ($rows as $number => $row) {
      $attributes = array();

      // Check if we're dealing with a simple or complex row
      if (isset($row['data'])) {
        foreach ($row as $key => $value) {
          if ($key == 'data') {
            $cells = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $cells = $row;
      }
      if (count($cells)) {
        // Add odd/even class
        $class = $flip[$class];
        if (isset($attributes['class'])) {
          $attributes['class'] .= ' ' . $class;
        }
        else {
          $attributes['class'] = $class;
        }

        // Build row
        $output .= ' <tr' . drupal_attributes($attributes) . '>';
        $i = 0;
        foreach ($cells as $cell) {
          $cell = tablesort_cell($cell, $header, $ts, $i++);
          $output .= _theme_table_cell($cell);
        }
        $output .= " </tr>\n";
      }
    }
    $output .= "</tbody>\n";
  }

  $output .= "</table>\n";
  return $output;
}

/*
*	Search form
*	根据form id 给form加上attributes
*	from: http://drupal.org/node/45295
*/ 
function bootstrapstyle_form($element) {
	
	if ($element['#id']==='search-theme-form') {
		$element['#attributes'] = array('class'=>'navbar-search');
	}
	
	
  // Anonymous div to satisfy XHTML compliance.
  $action = $element['#action'] ? 'action="' . check_url($element['#action']) . '" ' : '';
  return '<form ' . $action . ' accept-charset="UTF-8" method="' . $element['#method'] . '" id="' . $element['#id'] . '"' . drupal_attributes($element['#attributes']) . ">\n" . $element['#children'] . "\n</form>\n";
}

function bootstrapstyle_preprocess_search_theme_form(&$vars, $hook) {
  // Remove the "Search this site" label from the form.
  $vars['form']['search_theme_form']['#title'] = t('');
 
  //set 输入框 attributes
	$vars['form']['search_theme_form']['#attributes']['class'] = 'search-query';
	$vars['form']['search_theme_form']['#attributes']['placeholder'] = t('Search');

	// Set a default value for text inside the search box field.
//  $vars['form']['search_theme_form']['#value'] = t('Search this Site');
//dpm($vars['form']['search_theme_form']); 
  // Change the text on the submit button
  //$vars['form']['submit']['#value'] = t('Go');

  // Rebuild the rendered version (search form only, rest remains unchanged)
  unset($vars['form']['search_theme_form']['#printed']);
  $vars['search']['search_theme_form'] = drupal_render($vars['form']['search_theme_form']);

  // Rebuild the rendered version (submit button, rest remains unchanged)
//  unset($vars['form']['submit']['#printed']);
//  $vars['search']['submit'] = drupal_render($vars['form']['submit']);
  unset($vars['search']['submit']);

  // Collect all form elements to make it easier to print the whole form.
  $vars['search_form'] = implode($vars['search']);
}

/**
* Process variables for forums.tpl.php
*
*	alter $links to bootstrap style
*
*/
function bootstrapstyle_preprocess_forums(&$vars) {
	$links = $vars['links'];

	foreach ($links as $key=>$val) {
		switch($key) {
			case 'login':
				break;
			case 'disallowed':
				$links[$key]['attributes'] = array('class'=>'label label-info');
				break;
			default:
				$links[$key]['attributes'] = array('class'=>'btn btn-primary');
				$links[$key]['title'] = '<i class="icon-pencil icon-white"></i> '.$links[$key]['title'].' »';
				$links[$key]['html'] = TRUE;
		}
	}
	$vars['links'] = $links ;	
}

/**
 * Returns HTML for a query pager.
 *
 * Menu callbacks that display paged query results should call theme('pager') to
 * retrieve a pager control so that users can view other results.
 * Format a list of nearby pages with additional query results.
 *
 * @param $tags
 *   An array of labels for the controls in the pager.
 * @param $limit
 *   The number of query results to display per page.
 * @param $element
 *   An optional integer to distinguish between multiple pagers on one page.
 * @param $parameters
 *   An associative array of query string parameters to append to the pager links.
 * @param $quantity
 *   The number of pages in the list.
 * @return
 *   An HTML string that generates the query pager.
 *
 * @ingroup themeable
 */
function bootstrapstyle_pager($tags = array(), $limit = 10, $element = 0, $parameters = array(), $quantity = 9) {
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', (isset($tags[0]) ? $tags[0] : t('«')), $limit, $element, $parameters);
  $li_previous = theme('pager_previous', (isset($tags[1]) ? $tags[1] : t('‹')), $limit, $element, 1, $parameters);
  $li_next = theme('pager_next', (isset($tags[3]) ? $tags[3] : t('›')), $limit, $element, 1, $parameters);
  $li_last = theme('pager_last', (isset($tags[4]) ? $tags[4] : t('»')), $limit, $element, $parameters);

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => 'pager-first',
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => 'pager-previous',
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => 'pager-ellipsis',
          'data' => '<span>…</span>',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => 'pager-item',
            'data' => theme('pager_previous', $i, $limit, $element, ($pager_current - $i), $parameters),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => 'active',
            'data' => '<span>'. $i. '</span>',
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => 'pager-item',
            'data' => theme('pager_next', $i, $limit, $element, ($i - $pager_current), $parameters),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => 'pager-ellipsis',
          'data' => '<span>…</span>',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => 'pager-next',
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => 'pager-last',
        'data' => $li_last,
      );
    }
    return _bootstrapstyle_pager($items, NULL, 'ul');
  }
}

function _bootstrapstyle_pager($items = array(), $title = NULL, $type = 'ul', $attributes = NULL) {
  $output = '<div class="pagination">';
  if (isset($title)) {
    $output .= '<h3>' . $title . '</h3>';
  }

  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    foreach ($items as $i => $item) {
      $attributes = array();
      $children = array();
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        $data .= theme_item_list($children, NULL, $type, $attributes); // Render nested list
      }
      if ($i == 0) {
        $attributes['class'] = empty($attributes['class']) ? 'first' : ($attributes['class'] . ' first');
      }
      if ($i == $num_items - 1) {
        $attributes['class'] = empty($attributes['class']) ? 'last' : ($attributes['class'] . ' last');
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}

function bootstrapstyle_node_submitted($node) {
  $output = '作者：'. theme('username', $node);
  $output .= ' <i class="icon-time ml1"></i> '. format_date($node->created, 'small');
  return $output;
}

/**
*	comment
**/

function bootstrapstyle_comment_submitted($comment) {
  return t('!username <span class="created ml1"><i class="icon-time"></i> @datetime</span>', array(
    '!username' => theme('username', $comment),
    '@datetime' => format_date($comment->timestamp, 'custom', 'm-d H:i'),
  ));
}

/*
FORM OVERRIDES
*/

function bootstrapstyle_preprocess_user_login_block(&$variables) {
	$variables['form']['name']['#attributes']['placeholder'] = $variables['form']['name']['#title'];
	$variables['form']['pass']['#attributes']['placeholder'] = $variables['form']['pass']['#title'];
	// Remove the "Username" & "Password" labels from the form.
	unset($variables['form']['name']['#title']);
	unset($variables['form']['pass']['#title']);
	// Add some classes
	// $variables['form']['name']['#attributes']['class'] = 'input-block-level';
	// $variables['form']['pass']['#attributes']['class'] = 'input-block-level';
	$variables['form']['submit']['#attributes']['class'] = 'btn btn-primary';
	
	$links = '&nbsp;&nbsp;';
	if (variable_get('user_register', 1)) {
	$links .= l(t('Register'), 'user/register', array('attributes' => array('title' => t('Create a new user account.'))));
	$links .= ' | ';
	}
	$links .= l('忘记密码?', 'user/password', array('attributes' => array('title' => t('Request new password via e-mail.'))));
	
	$variables['form']['links'] = array('#value' => $links);
	
	$variables['rendered'] = drupal_render($variables['form']);
}

/**
 * Create and image tag for an imagecache derivative
 *
 * @param $presetname
 *   String with the name of the preset used to generate the derivative image.
 * @param $path
 *   String path to the original image you wish to create a derivative image
 *   tag for.
 * @param $alt
 *   Optional string with alternate text for the img element.
 * @param $title
 *   Optional string with title for the img element.
 * @param $attributes
 *   Optional drupal_attributes() array. If $attributes is an array then the
 *   default imagecache classes will not be set automatically, you must do this
 *   manually.
 * @param $getsize
 *   If set to TRUE, the image's dimension are fetched and added as width/height
 *   attributes.
 * @param $absolute
 *   A Boolean indicating that the URL should be absolute. Defaults to TRUE.
 * @return
 *   HTML img element string.
 */
function bootstrapstyle_imagecache($presetname, $path, $alt = '', $title = '', $attributes = NULL, $getsize = TRUE, $absolute = TRUE) {
  // Check is_null() so people can intentionally pass an empty array of
  // to override the defaults completely.
  if (is_null($attributes)) {
    $attributes = array('class' => 'imagecache imagecache-'. $presetname);
		  if (substr($presetname, -7) == '_circle') { //if suffix is _circle
				$attributes['class'] .= ' img-circle';
			}
		  if (substr($presetname, -8) == '_rounded') { //if suffix is _rounded
				$attributes['class'] .= ' img-rounded';
			}
  }
	
  $ours = array(
    'src' => imagecache_create_url($presetname, $path, FALSE, $absolute),
    'alt' => $alt,
    'title' => $title,
  );
  if ($getsize && ($image = image_get_info(imagecache_create_path($presetname, $path)))) {
    $ours += array('width' => $image['width'], 'height' => $image['height']);
  }

  return '<img' . drupal_attributes($ours + $attributes) . '/>';
}



function bootstrapstyle_status_messages($display = NULL) {
	$dict = array(
		'status' => 'alert-success',
		'warning' => 'alert-info',
		'error' => 'alert-error',
	);
  $output = '';
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"alert ". $dict[$type]. "\">\n";
		$output .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }
  return $output;
}

function bootstrapstyle_preprocess_user_profile(&$variables) {
	$profile = $variables['profile'];
	if (array_key_exists('user_picture', $profile)) {
		$variables['user_picture'] = $profile['user_picture'];
		unset($profile['user_picture']);
	  $variables['user_profile'] = implode($profile);	
	}
}

/* ------------ custom function -----------*/

/**
 *  user nav links
 */
function _bootstrapstyle_user_nav_links() {
  global $user;
  $output = '';
  $links = array();
  if ($user->uid) {
    $output = '<ul class="nav pull-right"><li class="divider-vertical"></li>
<li class="dropdown"><a href="/user/'. $user->uid. '" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> '. 
      $user->name. ' <b class="caret"></b></a>';
		$links = menu_navigation_links('navigation');
//		_bootstrapstyle_icon_menu($links);	//convert rel to icon
    $output .= theme('links', $links, array('class'=>'dropdown-menu'));
    $output .= '</li></ul>';
  } else {
//    $label = t('Not logged in');
//    $links['login'] = array(
//      'title' => t('Log in'),
//      'href' => '',
//      'external' => TRUE,
//      'fragment' => 'block-user-0',
//      'attributes' => array(
//          'data-toggle'=> 'modal',
//        ),
//    );
//    if (variable_get('user_register', 1)) {
//      $links['register'] = array(
//        'title' => t('Create new account'),
//        'href' => 'user/register',
//      );
//    }
//    $links['password'] = array(
//      'title' => t('Forget password?'),
//      'href' => 'user/password',
//    );
    // $output = l('<i class="icon-user"></i> '. t('Log in'), 'user/login', array('html' => TRUE));
    $links = array();
    $links['login-link'] = array(
      'title' => '<i class="icon-user"></i> '. t('Log in'),
      'href' => 'user/login',
      'html' => TRUE,
    );
    if (variable_get('user_register', 1)) {
      $links['register-link'] = array(
        'title' => t('Register'),
        'href' => 'user/register',
      );
    }
    $attributes = array('class' => 'nav pull-right');
    $output = theme('links', $links, $attributes);
  }

  
  return $output;
}

function _bootstrapstyle_icon_link(&$link, $key) {
  $title = check_plain($link['title']);
  switch ($key) {
    case 'comment_comments':
//      $link['title'] = '<i class="icon-comment"></i> ' . $title;
//      $link['html'] = TRUE;
      //$link['attributes']['class'] = $link['attributes']['class']?$link['attributes']['class'].' btn':'btn';
      // $link['attributes']['class'] .= ' btn-small';
      break;

    case 'comment_add':
//      $link['title'] = '<i class="icon-edit"></i> ' . $title;
//      $link['html'] = TRUE;
      //$link['attributes']['class'] = $link['attributes']['class']?$link['attributes']['class'].' btn':'btn';
      // $link['attributes']['class'] .= ' btn-small';
      break;

    case 'statistics_counter':
//      $link['title'] = '<i class="icon-globe"></i> ' . $title;
//      $link['html'] = TRUE;
      break;

    case 'node_read_more':
//      $link['title'] = '<i class="icon-chevron-right"></i> ' . $title;
//      $link['html'] = TRUE;
//      $link['attributes']['class'] = $link['attributes']['class']?$link['attributes']['class'].' pull-right':'pull-right';
      break;

/*    case 'flag-bookmarks':
      $link['title'] = '<i class="icon-flag"></i>'.$link['title'];
      $link['html'] = TRUE;
    break;*/

    default:
      // dpm($key);
  }
}

/**
 * _bootstrapstyle_icon_menu(&$links)
 *	配合menu_attributes模块，将rel转换为<i class="icon-*"></i>;
 **/
function _bootstrapstyle_icon_menu(&$links, $inverse = FALSE) {
	foreach ($links as $key => $link) {
		if ($link['attributes']['class']) {
			$icon = $link['attributes']['class'];
			if ($inverse) $icon .= ' icon-white';
			$icon = '<i class="icon-'. $icon. '"></i> ';
			unset($links[$key]['attributes']['class']);
//			$variables['primary_links'][$key]['attributes']['title'] = $variables['primary_links'][$key]['title'];
			$links[$key]['title'] = $icon. $links[$key]['title'];
			$links[$key]['html'] = TRUE;
		}
	}
}