function arrayUnique(a) {
    var temp = {};
    for (var i = 0; i < a.length; i++)
        temp[a[i]] = true;
    var r = [];
    for (var k in temp)
        r.push(k);
    return r;
}
function enterCatalog(obj, stage){
	var newHeight = (obj.attrs.height * .25);
	var newWidth = (obj.attrs.width * .25);
	var newX = (stage.attrs.width/2) - (newWidth/2);
	var newY = (stage.attrs.height/2) - (newHeight/2);
	//console.log(stage);
	 var tween = new Kinetic.Tween({
	        node: obj, 
	        duration: 1,
	        x: newX,
	        y: newY,
	        width: newWidth,
	        height: newHeight,
	        onFinish: function() {
	        	drawCatalog();
	          }
	      });
	 tween.play();
}


function drawCategory(category, catPos){
	
	//console.log(category);
	
  	  //we'll create a new layer for each category so we can control zooming, etc..
      //var starLayer = new Kinetic.Layer();
  	  var policyQuestion = category;
  	  var imgWidth = 50;
  	  var imgHeight = 50;
  	  var catPosition =  catPos;
  	  var star = new Kinetic.Image({
            x: (catPosition.x + catPosition.xoffset)  - (imgWidth/2),
            y: catPosition.y - (imgHeight/2),
            image: imageObjStarSmall,
            //width: 434,
           // height: 421,
            width: imgWidth,
            height: imgHeight,
            questionname: policyQuestion.question.name,
            starlayer: i
          });
  	  star.on('click', function() {
	            //enterCatalog(sun, stage, layer, layerMain);
		 		//console.log(this);
		 		//alert('POLICY QUESTION: '+this.attrs.questionname);
  		  		$('#text-box').show();
		 		$('#text-box').html('POLICY QUESTION: '+this.attrs.questionname);
	          });
  	  
  	  //first remove the layer from the stage, then add the new node and add it back
  	  catLayer.remove();
  	  catLayer.add(star);
  	  stage.add(catLayer);
  	  
  	  //catLayers.push(catLayer);
  	  //console.log(category);
  	  if(category.hasOwnProperty("subcategories")){
  		 if(category.subcategories.length > 0 ){
  	  		 //console.log('adding sub categories');
  	  		 drawSubCatalog(category.subcategories, star);
  	  	  }
  	  }
  	 
  	drawCategoryElements(category, star);
  	 
    
}

function drawCategoryElements(category, star, radius, starsize){
	
   	 //console.log('getting star');
	if(radius == null || radius == ''){ radius = 65; }
	if(starsize == null || starsize == ''){ starsize = 10; }
   	 
	 var eltCenterX = star.attrs.x + (star.attrs.width / 2);
   	 var eltCenterY = star.attrs.y + (star.attrs.height / 2);
   	 var policyQuestion = category;
   	 var policyQuestionElts = policyQuestion.elements;
   	 
   	 
   	 //console.log('elements '+policyQuestionElts);
   	 for(x = 1; x < (policyQuestionElts.length + 1); x++){
   		 
   		 var policyQuestionElt = policyQuestionElts[x-1];
   		 //console.log(policyQuestionElt);
   		 var eltName = '';
   		 var eltId = '0';
   		 if(policyQuestionElt && policyQuestionElt.hasOwnProperty("variable_name")){
   			 eltName = policyQuestionElt.variable_name;
   		 }
   		 if(policyQuestionElt && policyQuestionElt.hasOwnProperty("id")){
   			 eltId = policyQuestionElt.id;
   		 }
   		
   		 var eltImage = imageObjElementNoData;
   		 if(policyQuestionElt && policyQuestionElt.hasOwnProperty("status") && policyQuestionElt.status == '1'){
   			eltImage = imageObjElementData;
   		 }
   		 if(policyQuestionElt && policyQuestionElt.hasOwnProperty("status") && policyQuestionElt.status == '3'){
    			eltImage = imageObjElementNoDataNotCollected;
    		 }
   		  		 
   		 if(eltName != ''){
   		 var degrees = 360 / policyQuestionElts.length * x;
	    	 var rad = (Math.PI / 180) * degrees;
		     var xPos = (radius * Math.cos(rad)) + eltCenterX;
		     var yPos = (radius * Math.sin(rad)) + eltCenterY;
		     //easeLine(stage, elementLayer, eltCenterX, eltCenterY, xPos, yPos, 0,0,1);
		    
		     var line = new Kinetic.Line({
		         points: [eltCenterX, eltCenterY, xPos, yPos],
		         stroke: '#2d879f',
		         strokeWidth: 1,
		         lineCap: 'round',
		         lineJoin: 'round'
		       });
		     
		     var imgWidth = starsize;
		     var imgHeight = starsize;
		     var elt = new Kinetic.Image({
		            x: xPos  - (imgWidth/2),
		            y: yPos - (imgHeight/2),
		            image: eltImage,
		            width: imgWidth,
		            height: imgHeight,
		            eltname: eltName,
			        name: eltName,
			        id: eltId
		          });
		     
		     //console.log(elt);
		 
		 	elt.on('click', function() {
	            //enterCatalog(sun, stage, layer, layerMain);
		 		//console.log(this);
		 		//alert(this.attrs.eltname);
		 		$('#text-box').show();
		 		$('#text-box').html('DATA ELEMENT: '+this.attrs.eltname);
	          });
		 	//stage.add(elementLayer);
		 	
		 		elementLayer.add(line);
			 	//elementLayer.add(circle);
			 	elementLayer.add(elt);
			 	
   	 }
		 	
   	 }
   	catLayer.remove();
   	elementLayer.remove();
   	stage.add(elementLayer);
   	stage.add(catLayer);
}


function drawCategories(index){
	
	console.log(index);
	
    for(i = 0; i < catPositions.length; i++){
  	  //we'll create a new layer for each category so we can control zooming, etc..
      //var starLayer = new Kinetic.Layer();
  	  var policyQuestion = policyQuestionCatalog[i];
  	  var imgWidth = 50;
  	  var imgHeight = 50;
  	  var catPosition =  catPositions[i];
  	  var star = new Kinetic.Image({
            x: (catPosition.x + catPosition.xoffset)  - (imgWidth/2),
            y: catPosition.y - (imgHeight/2),
            image: imageObjStarSmall,
            //width: 434,
           // height: 421,
            width: imgWidth,
            height: imgHeight,
            questionname: policyQuestion.question.name,
            starlayer: i
          });
  	  star.on('click', function() {
	            //enterCatalog(sun, stage, layer, layerMain);
		 		//console.log(this);
		 		alert('POLICY QUESTION: '+this.attrs.questionname);
	          });
  	  catLayer.add(star);
  	  stage.add(catLayer);
  	  catLayers.push(catLayer);
    }
    
}

function drawCatalog(){
	
	//first get the position of the main categories around the center	
	
	var centerX = stage.getWidth() / 2;
	var centerY = stage.getHeight() / 2;
      
		for(i = 1; i < (policyQuestionCatalog.length + 1); i++){
      
    	  var degrees = 360 / 11 * i;
    	  var xOffset = 0;
    	  var yOffset = 0;
    	  //console.log(degrees);
    	  // based on where things are, create some offsets to maximize the screen real estate
    	  if(degrees >= 0 &&  degrees < 70 ) { 
    		  xOffset = 150; 
    	  	}
    	  if(degrees > 110 &&  degrees < 180 ) { 
    		  xOffset = -150; 
    	  	}
    	  if(degrees > 290 &&  degrees <= 360 ) { 
    		  xOffset = 150; 
    	  	}
    	  if(degrees > 180 &&  degrees < 250 ) { 
    		  xOffset = -150; 
    	  	}
	      var rad = (Math.PI / 180) * degrees;
	      var xPos = (250 * Math.cos(rad)) + centerX;
	      var yPos = (250 * Math.sin(rad)) + centerY;
	      
	      var catPos = new Array();
	      catPos['x'] = xPos;
	      catPos['y'] = yPos;
	      catPos['xoffset'] = xOffset;
	      //draw a line with a tween to what will be the center of each category
	      var callback = false;
	      if(i == policyQuestionCatalog.length ){ callback = true; } 
	      tweenCategoryLine(stage, catLineLayer, centerX, centerY, xPos, yPos, xOffset,yOffset, 5, policyQuestionCatalog[i-1], catPos );
	      //add the position of the category to an array
	      catPositions.push(catPos);
      
      }
    
	  //stage.add(elementLayer);
      //stage.add(catLayer);
	  layerMain.remove();
      stage.add(layerMain);
      //console.log(catPositions);
     
}

function drawSubCatalog(subCategories, star){
	
	//get the positions of the categories around the main category	
	//console.log(star);
	var centerX = star.attrs.x + (star.attrs.width)/2;
	var centerY = star.attrs.y + (star.attrs.height)/2;
    
	
		for(i = 1; i < (subCategories.length + 1); i++){
      
    	  var degrees = 360 / subCategories.length * i;
    	  if(degrees == 360){
    		  degrees = 20;
    	  }
    	  var xOffset = 0;
    	  var yOffset = 0;
    	  //console.log(degrees);
    	  // based on where things are, create some offsets to maximize the screen real estate
    	  if(degrees >= 0 &&  degrees < 70 ) { 
    		  xOffset = 70; 
    	  	}
    	  if(degrees > 110 &&  degrees < 180 ) { 
    		  xOffset = -70; 
    	  	}
    	  if(degrees > 290 &&  degrees <= 360 ) { 
    		  xOffset = 70; 
    	  	}
    	  if(degrees > 180 &&  degrees < 250 ) { 
    		  xOffset = -70; 
    	  	}
	      var rad = (Math.PI / 180) * degrees;
	      
	      var xPos = (70 * Math.cos(rad)) + centerX;
	      var yPos = (70 * Math.sin(rad)) + centerY;
	      
	      var catPos = new Array();
	      catPos['x'] = xPos;
	      catPos['y'] = yPos;
	      catPos['xoffset'] = xOffset;
	      //draw a line with a tween to what will be the center of each category
	      var callback = false;
	      if(i == policyQuestionCatalog.length ){ callback = true; } 
	      tweenSubCategoryLine(stage, catLineLayer, centerX, centerY, xPos, yPos, xOffset,yOffset, 3, subCategories[i-1], catPos );
	      //add the position of the category to an array
	      catPositions.push(catPos);
      
      }
    
	
	  layerMain.remove();
      stage.add(layerMain);

     
}

function drawSubCategory(category, catPos){
	
	//console.log(category);
	
  	  //we'll create a new layer for each category so we can control zooming, etc..
      //var starLayer = new Kinetic.Layer();
  	  var policyQuestion = category;
  	  var imgWidth = 30;
  	  var imgHeight = 30;
  	  var catPosition =  catPos;
  	  var star = new Kinetic.Image({
            x: (catPosition.x + catPosition.xoffset)  - (imgWidth/2),
            y: catPosition.y - (imgHeight/2),
            image: imageObjStarSmall,
            //width: 434,
           // height: 421,
            width: imgWidth,
            height: imgHeight,
            questionname: policyQuestion.question.name,
            starlayer: i
          });
  	  star.on('click', function() {
	            //enterCatalog(sun, stage, layer, layerMain);
		 		//console.log(this);
		 		//alert('POLICY QUESTION: '+this.attrs.questionname);
  		  		$('#text-box').show();
		 		$('#text-box').html('SUB POLICY QUESTION: '+this.attrs.questionname);
	          });
  	  
  	  //first remove the layer from the stage, then add the new node and add it back
  	  catLayer.remove();
  	  catLayer.add(star);
  	  stage.add(catLayer);
  	  
  	  //catLayers.push(catLayer);
  	  //console.log(category);
  	  if(category.hasOwnProperty("subcategories")){
  		 if(category.subcategories.length > 0 ){
  	  		 //console.log('adding sub categories');
  	  		 drawSubCatalog(category.subcategories, star);
  	  	  }
  	  }
  	 
  	drawCategoryElements(category, star, 45, 7);
  	 
    
}

function drawElements(){
	
	for(i = 0; i < catLayer.children.length; i++){
   	 //console.log('getting star');
   	 var star = catLayer.children[i];
   	 var eltCenterX = star.attrs.x + (star.attrs.width / 2);
   	 var eltCenterY = star.attrs.y + (star.attrs.height / 2);
   	 var policyQuestion = policyQuestionCatalog[i];
   	 var policyQuestionElts = policyQuestion.elements;
   	 
   	 
   	 //console.log('elements '+policyQuestionElts);
   	 for(x = 1; x < (policyQuestionElts.length + 1); x++){
   		 
   		 var policyQuestionElt = policyQuestionElts[x-1];
   		 var eltName = '';
   		 if(policyQuestionElt && policyQuestionElt.hasOwnProperty("variable_name")){
   			 eltName = policyQuestionElt.variable_name;
   		 }
   		 
   		 if(eltName != ''){
   		 var degrees = 360 / policyQuestionElts.length * x;
	    	 var rad = (Math.PI / 180) * degrees;
		     var xPos = (50 * Math.cos(rad)) + eltCenterX;
		     var yPos = (50 * Math.sin(rad)) + eltCenterY;
		     //easeLine(stage, elementLayer, eltCenterX, eltCenterY, xPos, yPos, 0,0,1);
		    
		     var line = new Kinetic.Line({
		         points: [eltCenterX, eltCenterY, xPos, yPos],
		         stroke: '#2d879f',
		         strokeWidth: 1,
		         lineCap: 'round',
		         lineJoin: 'round'
		       });
		     
		     var circle = new Kinetic.Circle({
		         x: xPos,
		         y: yPos,
		         radius: 3,
		         stroke: 'black',
		         strokeWidth: 1,
		         fill: 'red',
		         eltname: eltName,
		         name: eltName
		       });
		     
		     //console.log(eltName);
		     elementMaps.push(circle);
		     if(true){
		    	 elementNames.push(eltName);
		     }
		    
		 	
		 	
		 	circle.on('click', function() {
	            //enterCatalog(sun, stage, layer, layerMain);
		 		//console.log(this);
		 		alert(this.attrs.eltname);
	          });
		 	//stage.add(elementLayer);
		 	
		 		elementLayer.add(line);
			 	elementLayer.add(circle);
			 	
   	 }
		 	
   	 }
   	
     }
}


function showDataRelationships(){
	//console.log(elementNames);
	var uNames = arrayUnique(elementNames);
	//console.log(uNames);
	//var offsetX = imageObjElementData.width/2;
	//var offsetY = imageObjElementData.height/2;
	var offsetX = 5;
	var offsetY = 5;
	
	var eltName = $('#data_element').val();
	
	//console.log(offsetX);
	
	//for(e = 0; e < uNames.length; e++){
		//var eName = '.'+e;
		var elts = stage.get('.'+eltName);
		console.log(elts);
		for(i = 0; i < elts.length; i++){
			var currentElt = elts[i];
			var nextElt = elts[i +1];
			if(i == elts.length-1){
				nextElt = elts[0];
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
	
	//}
	if(zoom){
		relLayer.remove();
		relLayer.setScale(2);
		stage.add(relLayer);
	}
	//stage.add(elementLayer);
	
}

function showData(){
	$('#catalog-table').animate({ 'margin-left': '0px'}, 2000);
}


function showStory(){
	$('#catalog-table').animate({ 'margin-left': '-955px'}, 2000);
}

function tweenCategoryLine(stage, layer, startX, startY, endX, endY, xOffset, yOffset, strokeWidth, category, catPos){
	
	var line = new Kinetic.Line({
        points: [startX, startY, startX, startY],
        stroke: '#bcae7d',
        strokeWidth: strokeWidth,
        lineCap: 'round',
        lineJoin: 'round',
        category: category,
        catPos: catPos
      });
	
	catLineLayer.remove();
	catLineLayer.add(line);
    stage.add(catLineLayer);
	
	var tween = new Kinetic.Tween({
        node: line, 
        duration: 1,
        points: [startX, startY, endX+xOffset, endY+yOffset],
        onFinish: function() {
        	 drawCategory(this.node.attrs.category,this.node.attrs.catPos ); 
          }
      });
	tween.play();
	//layer.add(line);
    //stage.add(layer);
}

function tweenSubCategoryLine(stage, layer, startX, startY, endX, endY, xOffset, yOffset, strokeWidth, category, catPos){
	
	var line = new Kinetic.Line({
        points: [startX, startY, startX, startY],
        stroke: '#bcae7d',
        strokeWidth: strokeWidth,
        lineCap: 'round',
        lineJoin: 'round',
        category: category,
        catPos: catPos
      });
	
	catLineLayer.remove();
	catLineLayer.add(line);
    stage.add(catLineLayer);
	
	var tween = new Kinetic.Tween({
        node: line, 
        duration: 1,
        points: [startX, startY, endX+xOffset, endY+yOffset],
        onFinish: function() {
        	 drawSubCategory(this.node.attrs.category,this.node.attrs.catPos ); 
          }
      });
	tween.play();
	//layer.add(line);
    //stage.add(layer);
}

function zoomClear(){

	eltId = 'zoom'+zoomLevel;
	$('#'+eltId).removeClass('tick-on');
	$('#'+eltId).addClass('tick-off');
	
}

function zoomAdd(){
	eltId = 'zoom'+zoomLevel;
	$('#'+eltId).removeClass('tick-off');
	$('#'+eltId).addClass('tick-on');
}

function zoomStageIn(){
	//console.log(stage.getAbsolutePosition());
	zoomClear();
	
	if(zoomLevel < 10 ){
	
	stage.setDraggable(true);
	zoomLevel += 1;
	var layers = stage.children;
	for(i = 0; i < layers.length; i++){
		var tmpLayer = layers[i];
		//tmpLayer.remove();
		tmpLayer.setScale(zoomLevel);
		//stage.add(tmpLayer);
	}
	layerMain.remove();
    catLineLayer.remove();
    catLayer.remove(); 
    elementLayer.remove();
    relLayer.remove(); 
	//catLayer.setScale(2);
	
	stage.add(catLineLayer);
	stage.add(elementLayer);
	stage.add(catLayer);
	stage.add(relLayer);
	stage.add(layerMain);
	
	//console.log(layers);
	
	zoom = true;
	}
	
	zoomAdd();
	
	

}

function zoomStageOut(){
	
	zoomClear();
	
	if(zoomLevel > 1){
	
		zoomLevel -= 1;
		stage.setDraggable(true);
		var layers = stage.children;
		for(i = 0; i < layers.length; i++){
			var tmpLayer = layers[i];
			//tmpLayer.remove();
			tmpLayer.setScale(zoomLevel);
			//stage.add(tmpLayer);
		}
		layerMain.remove();
	    catLineLayer.remove();
	    catLayer.remove(); 
	    elementLayer.remove();
	    relLayer.remove(); 
		//catLayer.setScale(2);
		
		stage.add(catLineLayer);
		stage.add(elementLayer);
		stage.add(catLayer);
		stage.add(relLayer);
		stage.add(layerMain);
		
		//console.log(layers);
		if(zoomLevel == 1){
			zoom = false;
		}
	
	}
	
	zoomAdd();
	
}
