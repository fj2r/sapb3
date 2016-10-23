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

// les fonctions en dessous sont deprecated !

function loadSelect1() {


$.ajax({
//La page à appeler

"url": "choix1.php?f0=" + $('#voeu0').val(),
"type": "GET",
"cache": false,
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$("#div1").html(data);
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

function loadSelect2() {


$.ajax({
//La page à appeler

"url": "choix2.php?f1=" + $('#voeu1').val(),
"type": "GET",
"cache": false,
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$("#div2").html(data);
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

function loadSelect3() {


$.ajax({
//La page à appeler

"url": "choix3.php?f2=" + $('#voeu2').val(),
"type": "GET",
"cache": false,
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$("#div3").html(data);
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

function loadSelect4() {


$.ajax({
//La page à appeler

"url": "choix4.php?f3=" + $('#voeu3').val(),
"type": "GET",
"cache": false,
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$("#div4").html(data);
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