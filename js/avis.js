function avis(voeu) {

$.ajax({
//La page à appeler

"url": "avis.php?status=1&filiere="+$('#filiere').val()+"&voeu="+voeu+"&id_pedago="+$('#id_pedago').val(),
"type": "POST",
"cache": false,
"data":{
	"avis": $('#avis_'+voeu).val(),	
	},
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$('td#rep_'+voeu).html(data);
	},
	
	//si ça échoue
	"error": function (xhr, textStatus, errorThrown){
			console.log("Une erreur est survenue");		
		},
		
		// Fonction après tout ceci
		"complete": function(xhr, textStatus){
				console.log("Fin execution")
			},


});

}

/////////////////////////////////////////pour supprimer le voeu
function avis_suppr(voeu, filiere, id_pedago) {

$.ajax({
//La page à appeler

"url": "avis_suppr.php?status=1&voeu="+voeu+"&filiere="+filiere+"&id_pedago=" +id_pedago,
"type": "POST",
"cache": false,
"data":{
	"avis_modif": $('div#rep_'+voeu).val(),	
	},
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$('td#rep_'+voeu).html(data);
	},
	
	//si ça échoue
	"error": function (xhr, textStatus, errorThrown){
			console.log("Une erreur est survenue");		
		},
		
		// Fonction après tout ceci
		"complete": function(xhr, textStatus){
				console.log("Fin execution")
			},


});

}
