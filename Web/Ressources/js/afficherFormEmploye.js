$(document).ready(function(){
	var dateFr = {altField: "#datepicker",
              closeText: 'Fermer',
              prevText: 'Précédent',
              nextText: 'Suivant',
              currentText: 'Aujourd\'hui',
              monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
              monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
              dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
              dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
              dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
              weekHeader: 'Sem.',
              duration: "fast",
              dateFormat: "dd/mm/yy"};
	jQuery('.datepicker').datepicker(
	  	dateFr
	);

	$("#autre").click(function(){
		var text = document.createElement("input");
	    text.setAttribute("type", "text"); 
	    text.setAttribute("name", "autreQualite");
	    text.setAttribute("id", "autreQualite");
	    text.setAttribute("class", "form-control focus_activated");
	    text.setAttribute("placeholder", "Votre personnalité *");
	    text.setAttribute("data-validation-regex-regex", "^[a-zA-Z|éèêëôöîïâàùç |'-]*");
	    text.setAttribute("data-validation-regex-message", "Caractère non valide");

		var paragraphe = document.createElement("p");
	    paragraphe.setAttribute("class", "help-block text-danger");

	    document.getElementById("block-personnalite").append(text);
	    document.getElementById("block-personnalite").append(paragraphe);
	});

	$("#submit").click(function(){			
	  	var date = $(".datepicker").val().split('/');
	  	$("#dateNaissance").val(date[2] + '-' + date[1] + '-' + date[0]);

	  	if ($('#select-country').val() != "" && $('#input-phone').val() != "") {
	    	$('#contact').val($('#select-country').val() + "/" + $('#input-phone').val());
	  	} 

	  	var checkbox   = document.getElementsByName('qualite');
		var input      = document.getElementsByName('autreQualite');
		var perso      = ""; 
		var autrePerso = "";
		for (var i = 0; i < checkbox.length; i++) {
			var check = checkbox[i].checked;
			if (check) {
				perso += checkbox[i].value + "_";
			}
		}
		for (var i = 0; i < input.length; i++) {
			if (input[i].value != "") {
				autrePerso += (input[i].value).charAt(0).toUpperCase() + (input[i].value).slice(1) + "_";
			}
		}
		$("#autrePersonnalite").val(autrePerso);
		var tabPersonnalite    = ( perso + autrePerso).split("_");   
	  	var newTabPersonnalite = tabPersonnalite.filter(function(elem, index, self) {
	      	return index === self.indexOf(elem);
	  	});
		$("#personnalite").val(newTabPersonnalite.toString().replace(/\,/g, '_'));

		if ($('#typePaiement').val() != "en espèce") {
			console.log("on suit le processus");
		} 
		return false;
	});

});