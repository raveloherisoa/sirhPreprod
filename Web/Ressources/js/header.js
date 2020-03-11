$(document).ready(function(){
	functionFiltre();
	keepHeight();
	var divAlert = document.getElementById("alert");
	if (typeof(divAlert) != "undefined" && divAlert != null) {
		setTimeout(function(){
			$('#alert').hide();
		}, 2000);
	} 

	$(".customPagination").click(function(){
		keepHeight();
	});
});
$(function () { 
	var validationSelection = $('.form-group:visible').find('input,select,textarea');
	$(validationSelection).not("[type=submit]").jqBootstrapValidation(); 
});

function keepHeight() {
	var fenetre = $(window).height(),
		html			=	$('body').height(),
		headerHeight	=	$('nav').height(),
		footerHeight	=	$('footer').height();

	if (fenetre > html) {
		$('.page-section').css('min-height', fenetre - (headerHeight + footerHeight) );
	}
}