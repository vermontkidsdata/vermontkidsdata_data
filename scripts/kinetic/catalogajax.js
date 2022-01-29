function loadPolicyQuestionCatalog(){
	//get a json representation of the policy questions and their associated data elements
	$.ajax({
		  type: 'POST',
		  data: {
				
				},
		  url: '/catalogajax/load_policy_questions_with_elements',
		  success: function(data) {
			  //alert(data);
			  var obj = jQuery.parseJSON(data);
			  policyQuestionCatalog = obj;
			  //console.log(obj.length);
			  for (var i = 0; i < obj.length; ++i) {
				  tmp = obj[i];
			  }

			} //end success
	});
	
}

function showDataMetaRelationships(){
	//console.log(elementNames);
	var meta = $('#meta').val().trim();
	var offsetX = 5;
	var offsetY = 5;
	$.ajax({
		  type: 'POST',
		  data: {
				meta: meta
				},
		  url: '/catalogajax/search_data_elements_meta',
		  success: function(data) {
			  //alert(data);
			  var elts = [];
			  var obj = jQuery.parseJSON(data);
			  for (var i = 0; i < obj.length; ++i) {
				  tmpId = obj[i];
				  elts.push(stage.get('#'+tmpId));
			  }
			  
			  for(i = 0; i < elts.length; i++){
					var currentElt = elts[i][0];
					var nextElt = null;
					if(i == elts.length-1){
						nextElt = elts[0][0];
					} else {
						nextElt = elts[i +1][0];
					}
					
					var line = new Kinetic.Line({
					    points: [currentElt.attrs.x+offsetX, currentElt.attrs.y+offsetY, nextElt.attrs.x+offsetX, nextElt.attrs.y+offsetY],
				        stroke: '#2d879f',
				        strokeWidth: 1,
				        lineCap: 'round',
				        lineJoin: 'round',
				        dashArray: [5, 5]
				      });
					
					relLayer.remove();
					relLayer.add(line);
				    stage.add(relLayer);
				}

				if(zoom){
					relLayer.remove();
					relLayer.setScale(2);
					stage.add(relLayer);
				}
			  
			} //end success
	});

	
}