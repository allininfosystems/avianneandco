jQuery(document).ready(function() {	
	
	function relyOnForm() {
		var id = '#dialog';
		
		if(jQuery('#q_contactform #name').val()!='Your Name') {
			jQuery('#contactform #name').val(jQuery('#q_contactform #name').val());
		}
		if(jQuery('#q_contactform #email').val()!='Your Email') {
			jQuery('#contactform #email').val(jQuery('#q_contactform #email').val());
		}
		if(jQuery('#q_contactform #phone').val()!='Your Phone Number') {
			jQuery('#contactform #phone').val(jQuery('#q_contactform #phone').val());
		}
		if(jQuery('#q_contactform #content').val()!='Your Message') {
			jQuery('#contactform #content').val(jQuery('#q_contactform #content').val());
		}
		
		var maskHeight = jQuery(document).height();
		var maskWidth = jQuery(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		jQuery('#mask').css({'width':100+'%','height':maskHeight});
		
		//transition effect		
		jQuery('#mask').fadeIn(500);	
		jQuery('#mask').fadeTo("slow",0.5);	
	
		//Get the window height and width
		var winH = jQuery(window).height();
		var winW = jQuery(window).width();
              
		//Set the popup window to center
		jQuery(id).css('top',  20);
		jQuery(id).css('left', winW/2-jQuery(id).width()/2);
		jQuery(id).css('position',  'fixed');
		
		//transition effect
		jQuery(id).fadeIn(500);
		return false;
	}
	
	jQuery("#contact-link").attr('href','#dialog');
	
	//select all the a tag with name equal to modal
	jQuery('a[name=modal], #contact-link').live('click',function(e) {
		//Cancel the link behavior
		e.preventDefault();
		
		//Get the A tag
		var id = jQuery(this).attr('href');		
		var contactType = id.substring(8)
		id = id.substring(0,7);
		
		if (contactType) {			
			contactType = contactType.substring(13);
			jQuery('#contact_type'+contactTypes[contactType]).click();
		}
		//Get the screen height and width
		var maskHeight = jQuery(document).height();
		var maskWidth = jQuery(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		jQuery('#mask').css({'width':100+'%','height':maskHeight});
		
		//transition effect		
		jQuery('#mask').fadeIn(500);	
		jQuery('#mask').fadeTo("slow",0.5);	
	
		//Get the window height and width
		var winH = jQuery(window).height();
		var winW = jQuery(window).width();
              
		//Set the popup window to center
		jQuery(id).css('top',  20);
		jQuery(id).css('left', winW/2-jQuery(id).width()/2);
		jQuery(id).css('position',  'fixed');
		
		//transition effect
		jQuery(id).fadeIn(500); 
	
	});
	
	//footer form addition
	jQuery('#submit-footer-button, #submit-footer-button a, .services-page-contact').click(function(e) {
		relyOnForm();
		e.preventDefault();
	});
	jQuery('#q_contactform').submit(function(e) {
		relyOnForm();
	});
	
	//if close button is clicked
	jQuery('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		jQuery('#contactform #name').val('');
		jQuery('#contactform #email').val('');
		jQuery('#contactform #phone').val('');
		jQuery('#contactform #content').val('');
		jQuery('.response').remove();
		jQuery('#mask').hide();
		jQuery('.window').hide();
	});		
	
	jQuery(document).keyup(function(e) {
		if (e.keyCode == 27) { 
			if(jQuery('.window').is(":visible")) {
				jQuery('#contactform #name').val('');
				jQuery('#contactform #email').val('');
				jQuery('#contactform #phone').val('');
				jQuery('#contactform #content').val('');
				jQuery('.response').remove();
				jQuery('#mask').hide();
				jQuery('.window').hide();
			}
		} 
	});
			
	
});