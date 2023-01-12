var scrolled = 0;
var header_offset = 40;
var search_timer = null;

jQuery(document).ready(function(){
	//bind anonymise
	anonymise_form(jQuery('#psfield_249_3_1').is(':checked'));
	jQuery('#psfield_249_2_1').change(function(){
		anonymise_form(jQuery(this).is(':checked'));
	});
});

var state_collapse = 0;
function toggle_burger_collapse(){
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