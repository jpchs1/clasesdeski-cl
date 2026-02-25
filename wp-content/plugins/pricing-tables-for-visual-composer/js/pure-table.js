jQuery(document).ready(function($) {
	var columns_value = $('.pricing-plans .pricing-grids').data('cols');
	$('.pricing-plans .pricing-grids').find('.cols-class').addClass(columns_value); 
});