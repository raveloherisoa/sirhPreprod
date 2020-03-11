$(document).on("click", ".delete", function () {
    var id   = $(this).data('id');
    var name = $(this).data('name');
    var url  = $(this).data('url');
    $('.modal-body #text-confirmation').text('Voulez-vous vraiment supprimer "' + name + '" ?');
    document.getElementById('action-delete').href = url + id;
     
});

$('#idInterlocuteur').change(function(){
	if ($('#idInterlocuteur').val() == "autre") {		
		window.location.href = "create-interlocuteur";
	} else {
		window.location.href = "save-interlocuteur_niveau_entretien?idInterlocuteur=" + $('#idInterlocuteur').val();
	}
});