
function scroll_to_class(element_class, removed_height) {
	var scroll_to = $(element_class).offset().top - removed_height;
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 0);
	}
}

function bar_progress(progress_line_object, direction) {
	var number_of_steps = progress_line_object.data('number-of-steps');
	var now_value = progress_line_object.data('now-value');
	var new_value = 0;
	if(direction == 'right') {
		new_value = now_value + ( 100 / number_of_steps );
	}
	else if(direction == 'left') {
		new_value = now_value - ( 100 / number_of_steps );
	}
	progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

jQuery(document).ready(function() {
    /*
        Fullscreen background
    */
	var root = document.documentElement;
    var background_image = getComputedStyle(root).getPropertyValue('--background-image');

	if(background_image !== "no") {
		$.backstretch(background_image);
	}
	
	$('[data-toggle="tooltip"]').tooltip(); 

    $('#top-navbar-1').on('shown.bs.collapse', function(){
    	$.backstretch("resize");
    });
    $('#top-navbar-1').on('hidden.bs.collapse', function(){
    	$.backstretch("resize");
    });
    
    /*
        Form
    */
    $('.f1 fieldset:first').fadeIn('slow');
    
    $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    
    // next step
    $('.f1 .btn-next').on('click', function() {
    	var parent_fieldset = $(this).parents('fieldset');
    	var next_step = true;
    	// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
		
		var password = "";

    	// fields validation
    	parent_fieldset.find('input[type="text"], input[type="password"], textarea').each(function() {
    		if( $(this).val() == "" ) {
    			$(this).addClass('input-error');
    			next_step = false;
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
		});
		
		parent_fieldset.find('input[name="f1-mobile-number"], input[name="f1-home-number"], input[name="f1-work-number"]').each(function() {
			var val = $(this).val();

			if (val !== null) {
				var matches = val.match(/\d/g);

				if (matches !== null) {
					var len = matches.length;

					if( (len < 10) || (len > 15)) {
						$(this).addClass('input-error');
						next_step = false;
					}
					else {
						$(this).removeClass('input-error');
					}
				}
				else {
					$(this).addClass('input-error');
					next_step = false;
				}
			}
		});
		
		parent_fieldset.find('input[name="f1-email"]').each(function() {
			var val = $(this).val();

			if (val !== null) {
				var atposition = val.indexOf("@");  
				var dotposition = val.lastIndexOf(".");  

				if (atposition<1 || dotposition<atposition+2 || dotposition+2>=val.length) {  
					$(this).addClass('input-error');
					next_step = false;
				}
				else {
					$(this).removeClass('input-error');
				}
			}
		});

		parent_fieldset.find('input[name="f1-password"], input[name="f1-repeat-password"]').each(function() {
			var val = $(this).val();

			if (val !== null) {
				if ($(this).prop('name') === 'f1-password') {
					password = val;
				}

				var result = zxcvbn(val);

				if (result.score !== 4) {
					$(this).addClass('input-error');
					next_step = false;
				}
				else {
					$(this).removeClass('input-error');
				}

				if ($(this).prop('name') === 'f1-repeat-password') {
					if (password !== val) {
						$(this).addClass('input-error');
						next_step = false;
					}
					else {
						$(this).removeClass('input-error');
					}
				}
			}
		});
    	// fields validation
    	
    	if( next_step ) {
    		parent_fieldset.fadeOut(400, function() {
    			// change icons
    			current_active_step.removeClass('active').addClass('activated').next().addClass('active');
    			// progress bar
    			bar_progress(progress_line, 'right');
    			// show next step
	    		$(this).next().fadeIn();
	    		// scroll window to beginning of the form
    			scroll_to_class( $('.f1'), 20 );
	    	});
    	}
    	
    });
    
    // previous step
    $('.f1 .btn-previous').on('click', function() {
    	// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	
    	$(this).parents('fieldset').fadeOut(400, function() {
    		// change icons
    		current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
    		// progress bar
    		bar_progress(progress_line, 'left');
    		// show previous step
    		$(this).prev().fadeIn();
    		// scroll window to beginning of the form
			scroll_to_class( $('.f1'), 20 );
    	});
    });
    
    // submit
    $('.f1').on('submit', function(e) {
    	
    	// fields validation
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function() {
    		if( $(this).val() == "" ) {
    			e.preventDefault();
    			$(this).addClass('input-error');
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
		});
		
		$(this).find('input[name="payment-phone"]').each(function() {
			var val = $(this).val();

			if (val !== null) {
				var matches = val.match(/\d/g);

				if (matches !== null) {
					var len = matches.length;

					if( (len < 10) || (len > 15)) {
						$(this).addClass('input-error');
						e.preventDefault();
					}
					else {
						$(this).removeClass('input-error');
					}
				}
				else {
					$(this).addClass('input-error');
					e.preventDefault();
				}
			}
		});
		
		$(this).find('input[name="payment-email"]').each(function() {
			var val = $(this).val();

			if (val !== null) {
				var atposition = val.indexOf("@");  
				var dotposition = val.lastIndexOf(".");  

				if (atposition<1 || dotposition<atposition+2 || dotposition+2>=val.length) {  
					$(this).addClass('input-error');
					e.preventDefault();
				}
				else {
					$(this).removeClass('input-error');
				}
			}
		});
    	// fields validation
    });
});
