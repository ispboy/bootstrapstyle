// JavaScript Document

// Using the closure to map jQuery to $.
(function ($) {
	// Store our function as a property of Drupal.behaviors.
	Drupal.behaviors.bootstrapstyle = function (context) {
		//修改node links
/*		$(".blog_usernames_blog a").html('<i class="icon-user"></i>');
		$(".comment_add a").html('<i class="icon-comment-alt"></i>');
		$(".comment_edit a").html('<i class="icon-edit"></i>');
		$(".comment_reply a").html('<i class="icon-reply"></i>');
		$(".comment_delete a").html('<i class="icon-remove"></i>');
		$(".comment_comments a").html('<i class="icon-comments"></i>');
		$(".node_read_more a").html('<i class="icon-zoom-in"></i>');
		$(".statistics_counter span").wrap("<small></small>"); */
		
		$(".teaser .content").each(function(i){
				var divH = $(this).height();
				var $p = $("p", $(this)).eq(0);
				while ($p.outerHeight() > divH) {
						$p.text($p.text().replace(/(\s)*([a-zA-Z0-9]+|\W)(\.\.\.)?$/, "..."));
				};
		});
		
		if (Drupal.settings.zrssfeed) {
			zrssfeed = Drupal.settings.zrssfeed;
			for (nid in zrssfeed) {
				node = zrssfeed[nid];
				option = {
					header: false,
					linktarget: "_blank",
				};
				if (node.teaser) {
					option['content'] = false;
					option['date'] = false;
					option['limit'] = 5;
				}
				
				for (key in node.items) {
					id = '#' + key;
					$(id).rssfeed(node.items[key], option);
				}
			}
		}
	};

}(jQuery));
