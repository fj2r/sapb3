function loadSelect(id, academie, type, secteur) {

var id;
var academie;
var type;
var secteur;

$.ajax({
	
//La page à appeler

url     : "listeEtablissements.php",
type    : "POST",
data    : 'academie='+academie+'&type='+type+'&secteur='+secteur,
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

function loadSelectPourModif(id, academie, type, statut, idVoeu) {

var id;
var academie;
var type;

$.ajax({
	
//La page à appeler

url     : "listeEtablissementsPourModif.php?statut="+statut+"&idVoeu="+idVoeu,
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

function loadMap (id, latitude, longitude, infowindow) {

var id;
var latitude;
var longitude;
var infowindow;

$.ajax({
	
//La page à appeler

url     : "map.php?statut=eleve",
type    : "POST",
data    : 'latitude='+latitude+'&longitude='+longitude+'&infowindow='+infowindow,
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

