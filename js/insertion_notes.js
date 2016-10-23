function insertion_notes(num_eleve_etab, notetype) {

$.ajax({
	
//La page à appeler


"url": "ajax-notes-eleves.php?status=0" +"&num_eleve_etab="+num_eleve_etab+"&notetype="+notetype+"&note="+$("#"+notetype+"").val(),
"type": "POST",
"cache":false,
"data":{
	"notetype": notetype ,	
	},
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$("td#parent_"+notetype+"").html(data);
		
	},
	
	//si ça échoue
	"error": function (xhr, textStatus, errorThrown){
			console.log(this);		
		},
		
		// Fonction après tout ceci
		"complete": function(xhr, textStatus){
				console.log("Fin execution")
			},


});

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function suppr_notes(num_eleve_etab, notetype) {

$.ajax({
	
//La page à appeler


"url": "ajax-notes-suppr.php?status=0" +"&num_eleve_etab="+num_eleve_etab+"&notetype="+notetype
//+"&note="+$("#"+notetype+"").val()

,
"type": "POST",
"cache":false,
"data":{
	"notetype": notetype ,	
	},
"dataType": "text",

//si AJAX est réussi
"success": function (data){
		$("td#parent_"+notetype+"").html(data);
		
	},
	
	//si ça échoue
	"error": function (xhr, textStatus, errorThrown){
			console.log(this);		
		},
		
		// Fonction après tout ceci
		"complete": function(xhr, textStatus){
				console.log("Fin execution")
			},


});

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
function btt_visible(notetype) {

$.ajax({
	
		$("div#btt_suppr_"+notetype+"").css("visibility:visible");
	

});

}
*/
