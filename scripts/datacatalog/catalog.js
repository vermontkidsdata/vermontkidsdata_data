var currentEltName = '';
var catLayers = [];
var elementMaps = [];
var elementNames = [];
var catPositions = [];
var layerMain = new Kinetic.Layer(); //main layer
var catLineLayer = new Kinetic.Layer(); //layer of lines from the middle to each topic
var catLayer = new Kinetic.Layer(); //layer of topics
var elementLayer = new Kinetic.Layer(); //layer of elements around each topic and subtopic
var relLayer = new Kinetic.Layer(); // the data relationship layer
var zoom = false;
var zoomLevel = 1;
var catalogEntered = false;


//load the policy questions and the associated data elements
loadCatalog();

function arrayUnique(a) {
    var temp = {};
    for (var i = 0; i < a.length; i++)
        temp[a[i]] = true;
    var r = [];
    for (var k in temp)
        r.push(k);
    return r;
}

function catalogInit(){

	imageObj.onload = function() {
		var sun = new Kinetic.Image({
			x: imgX,
			y: imgY - 100,
			image: imageObj,
			//width: 434,
			// height: 421,
			width: imgWidth,
			height: imgHeight
		});

		sun.on('click', function() {

			if(catalogEntered == false){
				// console.log(layerMain);
				$('#intro-container').hide();
				$('#control-container').show();
				layerMain.children[0].remove();
				enterCatalog(sun, stage);
				//drawCatalog();
				catalogEntered = true;
			}
			//console.log(policyQuestionCatalog.length);
			//drawCatalog();
		});
		var txt = "";

		var simpleText = new Kinetic.Text({
			x: 220,
			y: imgY - 150,
			text: txt,
			fontSize: 40,
			fontFamily: 'helvetica, arial',
			fill: '#003357',

			align: 'center',
			fontStyle: 'bold'
		});

		//console.log(simpleText.attrs.width);

		// add the shape to the layer
		layerMain.add(simpleText);
		layerMain.add(sun);

		// add the layer to the stage
		stage.add(layerMain);
		stage.setDraggable(true);


		//add the

	};
}

function enterCatalog(obj, stage){
	var newHeight = (obj.attrs.height * .4);
	var newWidth = (obj.attrs.width * .4);
	var newX = (stage.attrs.width/2) - (newWidth/2);
	var newY = (stage.attrs.height/2) - (newHeight/2);
	//console.log(stage);
	 var tween = new Kinetic.Tween({
	        node: obj, 
	        duration: 1,
	        x: newX,
	        y: newY - 50,
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
  	  var cat = new Kinetic.Circle({
        x: catPosition.x + catPosition.xoffset,
        y: catPosition.y,
        radius: 25,
        fill: '#c9cee2',
        stroke: '#003357',
        strokeWidth: 3,
        questionname: policyQuestion.question.name,
        starlayer: i
      });
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
  	  cat.on('click', function() {
	            //enterCatalog(sun, stage, layer, layerMain);
		 		//console.log(this);
		 		//alert('POLICY QUESTION: '+this.attrs.questionname);
  		  		$('#text-box').show();
		 		$('#text-box').html('<b>TOPIC:</b> '+this.attrs.questionname);

	          });
  	  
  	  //first remove the layer from the stage, then add the new node and add it back
  	  catLayer.remove();
  	  catLayer.add(cat);
  	  stage.add(catLayer);
  	  
  	  //catLayers.push(catLayer);
  	  //console.log(category);
  	  if(category.hasOwnProperty("subcategories")){
  		 if(category.subcategories.length > 0 ){
  	  		 //console.log('adding sub categories');
  	  		 drawSubCatalog(category.subcategories, star);
  	  	  }
  	  }
  	 
  	drawCategoryElements(cat, category, star);
  	 
    
}

function drawCategoryElements(cat, category, star, radius, starsize, fontsize){
	
   	 //console.log('getting star');
	if(radius == null || radius == ''){ radius = 65; }
	if(starsize == null || starsize == ''){ starsize = 10; }
	if(fontsize == null || fontsize == ''){ fontsize = 12; }
   	 
	 var eltCenterX = star.attrs.x + (star.attrs.width / 2);
   	 var eltCenterY = star.attrs.y + (star.attrs.height / 2);
   	 var policyQuestion = category;
   	 var policyQuestionElts = policyQuestion.elements;
   	 
   	 
   	 //console.log('elements '+policyQuestionElts);
   	 var eltCollected = 0;
   	 var eltNotCollected = 0;
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
   		 //default color for not collected
   		 var eltColor = '#faa749';
   		 if(policyQuestionElt && policyQuestionElt.hasOwnProperty("status") && policyQuestionElt.status == '1'){
   			eltColor = '#68ab44';
   			eltCollected += 1;
   		 }
   		 if(policyQuestionElt && policyQuestionElt.hasOwnProperty("status") && policyQuestionElt.status == '3'){
   			eltColor = '#f2566e';
   			eltNotCollected += 1;
    	}
   		 
   		 if(policyQuestionElt && policyQuestionElt.hasOwnProperty("status") && policyQuestionElt.status == '2'){
   			eltNotCollected += 1;
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
		     
		     var elt = new Kinetic.Circle({
		         x: xPos,
		         y: yPos,
		         radius: 5,
		         fill: eltColor,
		         stroke: '#003357',
		         strokeWidth: 1,
		         eltname: eltName,
			     name: eltName,
			     id: eltId
		       });
		     
		    
		 
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
   	 
   	//draw in the percentages and pie chart graphics
   	//console.log(category);
   	var wedge = new Kinetic.Wedge({
        x: eltCenterX,
        y: eltCenterY,
        radius: cat.attrs.radius,
        angleDeg: (eltCollected/policyQuestionElts.length) * 360,
        fill: '#68ab44',
        stroke: '#413e75',
        strokeWidth: 2,
        rotationDeg: -120
      });

      // add the shape to the layer
      catLayer.add(wedge);
      var radOffset = 5;
      if(cat.attrs.radius > 20 ){ radOffset = 10; } 
      var pctCircle = new Kinetic.Circle({
          x: eltCenterX,
          y: eltCenterY,
          radius: cat.attrs.radius - radOffset,
          fill: '#fff',
          stroke: '#003357',
          strokeWidth: 1,
        });
      
      //catLayer.add(pctCircle);
   	 
   	if(policyQuestionElts.length > 0){
	   	var pctComplete = (eltCollected/policyQuestionElts.length)*100;
	   	var txt = Math.round(pctComplete)+"%";
	   	var pctTxt = new Kinetic.Text({
	        x:  eltCenterX-(fontsize/2),
	        y:  eltCenterY-(fontsize/2),
	        text: txt,
	        fontSize: fontsize,
	        fontFamily: 'Calibri',
	        fill: '#003357',
	        align: 'center',
	        fontStyle: 'bold'
	    });
	   	catLayer.add(pctTxt);
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
  	  var policyQuestion = catalog[i];
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
	var centerY = (stage.getHeight() / 2) - 50;
	console.log('questions', catalog.length);
		for(i = 1; i < (catalog.length + 1); i++){
      
    	  var degrees = (360 / catalog.length) * i;
    	  var xOffset = 0;
			var yOffset = 0;
			//stagger the offsets so that elements are not overlapping
			var offsetAdjustment = 50;
			console.log(degrees);
			if (i % 2 == 0) {
				//console.log('even');
				offsetAdjustment = -50;
			}
			else {
				//console.log('odd');
				offsetAdjustment = 50;
			}
    	  // based on where things are, create some offsets to maximize the screen real estate
    	  if(degrees >= 0 &&  degrees < 70 ) { 
    		  xOffset = 100;
    	  	}
    	  if(degrees > 110 &&  degrees < 180 ) { 
					xOffset = -100 ;
    	  	}
    	  if(degrees > 290 &&  degrees <= 360 ) { 
					xOffset = 100 ;
    	  	}
    	  if(degrees > 180 &&  degrees < 250 ) { 
					xOffset = -100 ;
    	  	}
	      var rad = (Math.PI / 180) * degrees;
	      var xPos = (250 * Math.cos(rad)) + centerX + offsetAdjustment;
			var yPos = (250 * Math.sin(rad)) + centerY ;
	      
	      var catPos = new Array();
	      catPos['x'] = xPos;
	      catPos['y'] = yPos;
	      catPos['xoffset'] = xOffset;
	      //draw a line with a tween to what will be the center of each category
	      var callback = false;
	      if(i == catalog.length ){ callback = true; }
	      tweenCategoryLine(stage, catLineLayer, '#61729e', centerX, centerY, xPos, yPos, xOffset,yOffset, 3, catalog[i-1], catPos );
	      //add the position of the category to an array
	      catPositions.push(catPos);
      
      }
    console.log('category positiions', catPositions);
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
	      if(i == catalog.length ){ callback = true; }
	      tweenSubCategoryLine(stage, catLineLayer, centerX, centerY, xPos, yPos, xOffset,yOffset, 1, subCategories[i-1], catPos );
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
  	 var subcat = new Kinetic.Circle({
         x: catPosition.x+ catPosition.xoffset,
         y: catPosition.y,
         radius: 15,
         fill: '#c9cee2',
         stroke: '#003357',
         strokeWidth: 1,
         questionname: policyQuestion.question.name,
         starlayer: i
       });
  	subcat.on('click', function() {
	            //enterCatalog(sun, stage, layer, layerMain);
		 		//console.log(this);
		 		//alert('POLICY QUESTION: '+this.attrs.questionname);
  		  		$('#text-box').show();
		 		$('#text-box').html('<b>SUB TOPIC</b>: '+this.attrs.questionname);
	          });
  	  
  	  //first remove the layer from the stage, then add the new node and add it back
  	  catLayer.remove();
  	  catLayer.add(subcat);
  	  stage.add(catLayer);
  	  
  	  //catLayers.push(catLayer);
  	  //console.log(category);
  	  if(category.hasOwnProperty("subcategories")){
  		 if(category.subcategories.length > 0 ){
  	  		 //console.log('adding sub categories');
  	  		 drawSubCatalog(category.subcategories, star);
  	  	  }
  	  }
  	 
  	drawCategoryElements(subcat, category, star, 45, 7, 11);
  	 
    
}

function drawElements(){
	
	for(i = 0; i < catLayer.children.length; i++){
   	 //console.log('getting star');
   	 var star = catLayer.children[i];
   	 var eltCenterX = star.attrs.x + (star.attrs.width / 2);
   	 var eltCenterY = star.attrs.y + (star.attrs.height / 2);
   	 var policyQuestion = catalog[i];
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
	var offsetX = 0;
	var offsetY = 0;
	
	var eltName = $('#data_element').val();
	
	//console.log(offsetX);
	
	//for(e = 0; e < uNames.length; e++){
		//var eName = '.'+e;
		var elts = stage.get('.'+eltName);
		//console.log(elts);
		for(i = 0; i < elts.length; i++){
			var currentElt = elts[i];
			var nextElt = elts[i +1];
			if(i == elts.length-1){
				nextElt = elts[0];
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
	
	//}
	if(zoom){
		relLayer.remove();
		relLayer.setScale(2);
		stage.add(relLayer);
		elementLayer.remove();
	   	stage.add(elementLayer);
	    catLayer.remove();
	   	stage.add(catLayer);
	}
	//stage.add(elementLayer);
	
}

function showData(){
	$('#catalog-table').animate({ 'margin-left': '0px'}, 2000);
}

function tweenCategoryLine(stage, layer, stroke, startX, startY, endX, endY, xOffset, yOffset, strokeWidth, category, catPos){
	
	var line = new Kinetic.Line({
        points: [startX, startY, startX, startY],
        stroke: stroke,
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
	console.log('category line start x', startX);
	console.log('category line start y', startY);
	console.log('category line end x', endX);
	console.log('category line end y', endY);
	tween.play();
	//layer.add(line);
    //stage.add(layer);
}

function tweenSubCategoryLine(stage, layer, startX, startY, endX, endY, xOffset, yOffset, strokeWidth, category, catPos){
	
	var line = new Kinetic.Line({
        points: [startX, startY, startX, startY],
        stroke: '#61729e',
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
	stage.add(relLayer);
	stage.add(elementLayer);
	stage.add(catLayer);
	
	stage.add(layerMain);
	
	//console.log(layers);
	
	zoom = true;
	}	
	zoomAdd();
}

function zoomStageOut() {
	
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
		stage.add(relLayer);
		stage.add(elementLayer);
		stage.add(catLayer);
		
		stage.add(layerMain);
		
		//console.log(layers);
		if(zoomLevel == 1){
			zoom = false;
		}
	
	}
	
	zoomAdd();
	
}
