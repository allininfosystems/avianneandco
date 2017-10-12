jQuery(function(){
	jQuery('#main-nav-button').live("click", function(e){
		if(jQuery(this).hasClass('inactive')) {
			jQuery(this).siblings('div').hide().end().parent('div').addClass('active').end().removeClass('inactive').addClass('active');
			jQuery('.nav').show();
		} else {
			jQuery(this).removeClass('active').addClass('inactive').parent('div').removeClass('active').end().siblings('div').show();
			jQuery('.nav').hide().find('.child-item').hide().end().find('.parent-item').removeClass('active').addClass('inactive');
		}
	});
	jQuery('.mobile-drop-down .parent-item').live("click", function(e){
		if(jQuery(this).hasClass('inactive')) {
			jQuery(this).removeClass('inactive').addClass('active').next('li').show();
		} else {
			jQuery(this).next('li').hide().end().removeClass('active').addClass('inactive');
		}
		e.preventDefault();
	});
});