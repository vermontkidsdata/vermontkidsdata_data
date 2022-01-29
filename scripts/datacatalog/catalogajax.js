var catalog = null;

function loadCatalog(){
	//get a json representation of the policy questions and their associated data elements
	$.ajax({
		  type: 'GET',
		  data: {
				
				},
		  url: '/v1/datacatalog',
		  success: function(data) {
			  //alert(data);
			  console.log('catalog',data); 
			  var obj = data;
			  catalog = obj;
			  //console.log(obj.length);
			  for (var i = 0; i < obj.length; ++i) {
				  tmp = obj[i];
			  }
			  console.log('catalog ',catalog );
		  } //end success
	});
	
}

function showDataMetaRelationships(){
	//console.log(elementNames);
	var meta = $('#meta').val().trim();
	var offsetX = 0;
	var offsetY = 0;
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
			  //console.log(obj);
			  for (var i = 0; i < obj.length; ++i) {
				  tmpId = obj[i];
				  elts.push(stage.get('#'+tmpId));
			  }
			  
			  eltNames = '';
			  eltNamesArray = [];
			  for(i = 0; i < elts.length; i++){
				  var currentElt = elts[i][0];
				  //console.log(currentElt);
				  var eltName = currentElt.attrs.eltname;
				  var eltId = currentElt.attrs.id;
				  var eltPos = eltNamesArray.indexOf(eltName);
				  if(eltPos < 0){
					  eltNames += '<div class="catalog-label"><input type="checkbox" name="metaElements" value="'+eltId+'" />'+eltName+'</div>';
					  eltNamesArray.push(eltName);
				  }
				  
			  }
			  
			  $('#metaElts').html(eltNames);
			  
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
				        stroke: '#8e96a1',
				        strokeWidth: 4,
				        lineCap: 'round',
				        lineJoin: 'round',
				        dashArray: [5, 5]
				      });
					var line2 = new Kinetic.Line({
					    points: [currentElt.attrs.x+offsetX, currentElt.attrs.y+offsetY, nextElt.attrs.x+offsetX, nextElt.attrs.y+offsetY],
				        stroke: '#e6e6e8',
				        strokeWidth: 2,
				        lineCap: 'round',
				        lineJoin: 'round',
				        dashArray: [5, 5]
				      });
					
					relLayer.remove();
					relLayer.add(line);
					relLayer.add(line2);
				    stage.add(relLayer);
				    elementLayer.remove();
				   	stage.add(elementLayer);
				    catLayer.remove();
				   	stage.add(catLayer);
				}

				if(zoom){
					relLayer.remove();
					relLayer.setScale(2);
					stage.add(relLayer);
					elementLayer.remove();
				   	stage.add(elementLayer);
				    catLayer.remove();
				   	stage.add(catLayer);
				}
			  
			} //end success
	});

	
}

function showStory(){
	
	var meta = $('#meta').val().trim();
	var data_element = $('#data_element').val().trim();
	//console.log(data_element);
	
	var data_elements = [];
	if(data_element != 'none'){
		data_elements.push(data_element);
	}
	
	var selected = [];
	$('input[name=metaElements]:checked').each(function(){
        selected.push(this.value);
    });
	//console.log(selected);
	
	
	
	$.ajax({
		  type: 'POST',
		  data: {
				elements: selected.toString(),
				meta: meta,
				data_elements: data_elements.toString()
				},
		  url: '/catalogajax/build_story_for_meta_elements',
		  success: function(data) {
			  //alert(data);
			  var obj = jQuery.parseJSON(data);
			  var story = '';
			  var elementNarratives = obj['element_narratives'];
			  var dataElementNarratives = obj['data_element_narratives'];
			  var metaNarrative = obj['meta_narrative'];
			  if(metaNarrative.narrative_title != null){
				 // console.log(metaNarrative.narrative_title.length);
				  if(metaNarrative.narrative_title.length > 0){
					  story += '<div class="story-narrative-title">'+metaNarrative.narrative_title+'</div>';
					  story += '<div class="">'+metaNarrative.narrative+'</div>';
				  }
			  }
			  
			  
				  for(i = 0; i < elementNarratives.length; i++){
					  //console.log('narrative');
					  tmpNarrative = elementNarratives[i];
					  
					  story += '<div class="story-narrative-title">'+tmpNarrative.narrative_title+'</div>';
					  story += '<div class="">'+tmpNarrative.narrative+'</div>';
					  
					  
				  }
				  
				  for(i = 0; i < dataElementNarratives.length; i++){
					  //console.log('narrative');
					  tmpNarrative = dataElementNarratives[i];
					  
					  story += '<div class="story-narrative-title">'+tmpNarrative.narrative_title+'</div>';
					  story += '<div class="">'+tmpNarrative.narrative+'</div>';
					  
					  
				  }
			  
			 // console.log(elementNarratives);
			  if(story == ''){
				  story += '<div  class="story-narrative-title">In order to build your story you must first select some data elements</div>';
			  }
			  $('#story-content').html(story);
			  $('#catalog-table').animate({ 'margin-left': '-955px'}, 2000);

			} //end success
	});
	
}