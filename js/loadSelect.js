function loadSelect(numeroElement) {

var numPlus1;
numPlus1 = numeroElement + 1;

$.ajax({
	
//La page à appeler

"url": "choix"+numPlus1+".php?f"+numeroElement+"=" + $("#voeu"+ numeroElement).val(),
"type": "GET",
"cache": false,
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$("#div"+numPlus1).html(data);
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

