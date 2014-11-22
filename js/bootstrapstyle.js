// JavaScript Document

// Using the closure to map jQuery to $.
(function ($) {
	// Store our function as a property of Drupal.behaviors.
	Drupal.behaviors.bootstrapstyle = function (context) {
            //修改node links
            $(".blog_usernames_blog a").html('<i class="icon-user"></i>');
            $(".comment_add a").html('<i class="icon-comment-alt"></i>');
            $(".comment_edit a").html('<i class="icon-edit"></i>');
            $(".comment_reply a").html('<i class="icon-reply"></i>');
            $(".comment_delete a").html('<i class="icon-remove"></i>');
            $(".comment_comments a").html('<i class="icon-comments"></i>');
            $(".node_read_more a").html('<i class="icon-zoom-in"></i>');
            $(".statistics_counter span").wrap("<small></small>");
            
            $(".teaser .content").each(function(i){
                var divH = $(this).height();
                var $p = $("p", $(this)).eq(0);
                while ($p.outerHeight() > divH) {
                    $p.text($p.text().replace(/(\s)*([a-zA-Z0-9]+|\W)(\.\.\.)?$/, "..."));
                };
            });
		// Find all the secure links inside context that do not have our processed
		// class.
/*		$('[data-toggle=collapse]', context).bind("click", function(){
			if ($('.nav-collapse').height() == 0) {
				$('.nav-collapse').css("height", "auto");
				$('.nav-collapse').show();
			} else {
				$('.nav-collapse').height(0);
				$('.nav-collapse').hide();
			}
		});*/
	};

}(jQuery));
