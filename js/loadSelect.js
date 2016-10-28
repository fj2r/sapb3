function loadSelect(id, academie, type) {

var id;
var academie;
var type;

$.ajax({
	
//La page à appeler

url     : "listeEtablissements.php",
type    : "POST",
data    : 'academie='+academie+'&type='+type,
cache   : false,
dataType: "text",

//si AJAX est réussi
"success": function (data){
		$("#"+id).html(data);
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

