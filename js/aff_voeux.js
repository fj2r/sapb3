function aff_voeux (num_eleve_etab) {

$.ajax({
//La page à appeler

"url": "ajax-voeux-eleves.php?status=1" +"&num_eleve_etab="+num_eleve_etab,
"type": "GET",
"cache": false,
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$("#"+num_eleve_etab) .html(data);
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
