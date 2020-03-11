$('#submit').click(function(){
	if ($("#motDePasse").val() != "" && $("#motDePasse").val() != $("#confirmation").val()) {
		$("#match-message").html("<ul><li>Confirmation incorrect *</li><ul>");
		return false;
	}
});