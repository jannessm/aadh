var scrolled = 0;
var header_offset = 40;
var search_timer = null;

jQuery(document).ready(function(){
	var pos_x = jQuery('.current_page_item').position().left;
	var pos_y = jQuery('.current_page_item').position().top;
	//nur bei oberster nav animieren
	if(pos_y==header_offset)
		jQuery('#location').css({'left': pos_x, 'top': pos_y}).animate({'opacity': 1});

	//put collapse menu to whole page height
	var total_height = jQuery(document).height();
	jQuery('#collapse-menu-wrapper').css({'height' : total_height});

	//breakpoint for news
	if(jQuery(window).width() < 820)
		jQuery('.second-right').insertBefore('.first-left');

	//bind anonymise
	anonymise_form(jQuery('#psfield_249_3_1').is(':checked'));
	jQuery('#psfield_249_2_1').change(function(){
		anonymise_form(jQuery(this).is(':checked'));
	});

	init_search();
});

jQuery(window).on('resize', function(){
	var pos_x = jQuery('.current_page_item').position().left;
	var pos_y = jQuery('.current_page_item').position().top;

	//breakpoint for news
	if(jQuery(window).width() < 820)
		jQuery('.second-right').insertBefore('.first-left');
	else{
		jQuery('.first-left').insertBefore('.second-right');
	}

	//nur bei oberster nav animieren
	if(pos_y == header_offset)
		jQuery('#location').css({'left': pos_x, 'top': pos_y});
});

var state_collapse = 0;
function toggle_collapse(){
	if(state_collapse==0){
		jQuery('#collapse-menu-wrapper').animate({'right' : '-2px', 'opacity' : '1'});
		jQuery('#header-collapse-icon').addClass('close');
		state_collapse = 1;
	}else{
		jQuery('#collapse-menu-wrapper').animate({'right' : '-320px'});
		jQuery('#header-collapse-icon').removeClass('close');
		state_collapse = 0;
	}
}

var state_map = 0;
function toggle_map(){
	if(state_map==0){
		jQuery('iframe').css({'display': 'block'});
		state_map = 1;
	}else{
		jQuery('iframe').css({'display': 'none'});
		state_map = 0;
	}
}


function scrollDown(sel){
	target = jQuery(sel);
	scrollTopVal = target.offset().top - 100;

	jQuery('html, body').animate({
      scrollTop: scrollTopVal
    }, 500);
    return false;
}


function anonymise_form(disabled){
	jQuery('input[name="Name"]').attr("disabled", disabled);
	jQuery('#psfield_249_3').attr("disabled", disabled);
}