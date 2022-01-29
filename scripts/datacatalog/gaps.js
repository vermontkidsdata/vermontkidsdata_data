var catalog = null;
var stage = null;
var circle = null;
var currentLayer = null;
var mainLayer = null;
var topicLayer = null;
var subTopicLayer = null;
var tweenDuration = 1;
var currentTopic = null;
var currentTopicPoint = null;
var currentSubTopic = null;
var currentSubTopicPoint = null;
var currentElement = null;
var baseDataURL = 'https://data.vermontkidsdata.org';

var elementDetails = function () {
    console.log('showing element details');
    test();
}

function test(){
    var w = window.innerWidth;
    var h = window.innerHeight;
    easeStageLeft(w/2,h,easeDivLeft);
    //easeDivLeft();
    //currentLayer.x(-500);
    //stage.width(w-550);
    //stage.draw();
}

function testClose(){
    console.log('closing');
    gsap.to("#details", { duration: 1, width: 0 });
    var w = window.innerWidth;
    var tween = new Konva.Tween({
        // list of tween specific properties
        node: stage,
        duration: tweenDuration,
        width: w,
        onFinish: function() {

        }

    });
    // play tween
    tween.play();


}

/*
catalogInit -> focusTopic -> fadeMainLayer -> showTopic -> showTopicDetails
 */

function calculatePositionsEllipse(X,Y,width,height,degrees){
    //calculate positions on an ellipse around the center.  X,Y are the center of the ellipse,
    //and width,height are the axis

    //get the radians
    var rad = (Math.PI / 180) * degrees;
    var ePX = X + (width  * Math.cos(rad ));
    var ePY = Y +  (height * Math.sin(rad ));
    var topicPosition = {};
    topicPosition.x = ePX;
    topicPosition.y = ePY;
    return topicPosition;
}

function calculatePositionsCircle(X,Y,radius,degrees){
    //calculate positions on a circle around the center.  X,Y are the center of the circle,
    //and width,height are the axis

    //get the radians
    var rad = (Math.PI / 180) * degrees;
    var xPos = (radius * Math.cos(rad)) + X;
    var yPos = (radius * Math.sin(rad)) + Y;
    var position = {};
    position.x = xPos;
    position.y = yPos;
    return position;
}

function catalogInit(){
    // first we need to create a stage

   //set it to take up the full width and height of the container

    stage = new Konva.Stage({
        container: 'catalog',   // id of container <div>
        width: $('#catalog').width(),
        height: $('#catalog').height(),
        draggable: true,
    });

// then create layers
    mainLayer = new Konva.Layer();
    mainLayer.name("mainLayer");
    topicLayer = new Konva.Layer();
    topicLayer.name("topicLayer");
    subTopicLayer = new Konva.Layer();
    subTopicLayer.name("subTopicLayer");

// create our shape
    circle = new Konva.Circle({
        x: stage.width() / 2,
        y: stage.height() / 2,
        radius: 50,
        fill: 'red',
        stroke: 'black',
        strokeWidth: 4
    });

// add the shape to the layer
    mainLayer.add(circle);

// add the layer to the stage
    stage.add(mainLayer);

// draw the image
    mainLayer.draw();
    currentLayer = mainLayer;

    var tmpLayer = new Konva.Layer();
    var topicPoint;
    var color;
    mainLayer.draggable = true;
    stage.add(tmpLayer);
    tmpLayer.draw();
    for(var i = 1; i < (catalog.length + 1); i++) {
        //layer.remove();
        color = i<2 ? color = 'green' : color = 'blue' ;
        var degrees = (360 / catalog.length) * i;
        //console.log(degrees);
        var topicPosition = calculatePositionsEllipse(stage.width() / 2,stage.height() / 2,
            stage.width() / 4,stage.height() / 4,degrees);
        //console.log('topicPosition',topicPosition);
        catalog[i-1].position = topicPosition;
       // console.log('catalog ',catalog );
        topicPoint = createTopicPoint(catalog[i-1].position.x, catalog[i-1].position.y, color);
        topicPoint.setAttr('catalogIndex', i-1);

        topicPoint.on('click', function(evt) {
            //enterCatalog(sun, stage, layer, layerMain);
            //console.log(this);
            focusTopic(evt.target);
        });

        mainLayer.add(circle);
        var labelDirection = 'right';
        //the stage starts at 3 O'clock... so need to take that into consideration when determining if
        // text direction should go right or left
        if((degrees > 90) && (degrees < 270)){
            labelDirection = 'left';
        }

        tweenLine(stage,mainLayer,stage.width() / 2,stage.height() / 2,catalog[i-1].position.x,catalog[i-1].position.y,
            topicPoint,circle, catalog[i-1].question.name, labelDirection);
        //layer.add(topicPoint);
        stage.add(mainLayer);
        mainLayer.draw();
    }

}

function createSubTopicPoint(x,y, fill, radius = 20 ){
    return new Konva.Circle({
        x: x,
        y: y,
        radius: radius,
        fill: fill,
        stroke: 'black',
        strokeWidth: 4
    });
}

function createTopicPoint(x,y,color){
    return new Konva.Circle({
        x: x,
        y: y,
        radius: 20,
        fill: color,
        stroke: 'black',
        strokeWidth: 4
    });
}

var easeDivLeft = function (w,h){
    //ease a div into the window from off screen right

    //gsap.to("#details", { duration: 1, width: w });

    //$("#details").css("background-color", "red");
}

function easeStageLeft(width,height, callbackFunction){
    //set the stage width and animate to make it look like it's easing left
    var tween = new Konva.Tween({
        // list of tween specific properties
        node: stage,
        duration: tweenDuration,
        width: width,
        onFinish: function() {
            $("#details").css("width", width);
            $("#details").css("height", '100%');
            $.ajax({
                type: 'GET',
                url: baseDataURL + '/v1/havyc_chart/67',
                success: function (data) {
                    console.log('chart data', data);
                    renderChart(data, 'elementChart', 700 ,500);
                } //end success
            });
        }

    });
    // play tween
    tween.play();
}

function fadeMainLayer(){

    //before we fade the main layer, add the topic layer, and put a circle where the current topic is
    //console.log('fading', topic);
    /*
    var topicPoint = createTopicPoint(currentTopicPoint.attrs.x, currentTopicPoint.attrs.y, 'green');
    topicPoint.setAttr('catalogIndex', currentTopicPoint.attrs.catalogIndex);
    currentTopicPoint = topicPoint;
    */
    console.log("fadeMainLayer");
    console.log('currentLayer', currentLayer);
    topicLayer.add(currentTopicPoint);
    topicLayer.draw();
    stage.add(topicLayer);
    currentLayer = topicLayer;
    var tween = new Konva.Tween({
        // list of tween specific properties
        node: mainLayer,
        duration: tweenDuration ,
        opacity: 0,
        onFinish: function() {
            //once the main layer has faded, zoom in on the topic and add subtopics
            showTopic(currentTopicPoint);
            }
         });
        // play tween
         tween.play();
}

function fadeInLayer(layer){
    console.log('fadeInLayer', layer);
    var node = null;
    var tween = null;
    if(layer == 'mainLayer'){

        //console.log('fading in topic layer');
        currentLayer = mainLayer;
        //console.log('fading in sub topic layer');
        tween = new Konva.Tween({
            // list of tween specific properties
            node: currentLayer,
            duration: tweenDuration ,
            opacity: 1,
            onFinish: function() {
                console.log("main layer faded in");
                currentTopicPoint.radius(20);
                mainLayer.add(currentTopicPoint);
                mainLayer.draw();

            }
        });
        // play tween

    } else if(layer == 'topicLayer') {
        currentLayer = topicLayer;
        $('#topic').html('&raquo; '+currentTopic.question.name);
        $('#subtopic').html('');
        //console.log('fading in sub topic layer');
        tween = new Konva.Tween({
            // list of tween specific properties
            node: currentLayer,
            duration: tweenDuration ,
            opacity: 1,
            onFinish: function() {
                //resize the point that was in focus

                console.log(currentSubTopicPoint);
                currentSubTopicPoint.radius(20);
                topicLayer.add(currentSubTopicPoint);
                topicLayer.draw();

            }
        });
        // play tween
    }

        tween.play();

}

function fadeToLayer(layer){
        console.log('fadeToLayer', layer);

        //if (layer === 'mainLayer') { console.log('fading to main layer'); return false; }
        //fade the current layer and fade in the new one

        //var layerToShow = layer;
        var tween = new Konva.Tween({
            // list of tween specific properties
            node: currentLayer,
            duration: tweenDuration ,
            opacity: 0,
            onFinish: function() {
                //fadeInLayer(layerToShow);
                fadeInLayer(layer);
            }
        });
        // play tween
        tween.play();


}

function fadeTopicLayer(){

    //before we fade the main layer, add the topic layer, and put a circle where the current topic is
    //console.log('fading', topic);
    /*
    var subTopicPoint = createTopicPoint(currentSubTopic.position.x, currentSubTopic.position.y, 'blue');
    subTopicPoint.setAttr('catalogIndex', currentSubTopicPoint.attrs.catalogIndex);
    // since we are cloning the original point on another layer, we have to replicate the click even handler
    subTopicPoint.on('click', function(evt) {
        console.log('clicking sub topic point');
        showSubTopicDetails(evt.target);
    });
    currentSubTopicPoint = subTopicPoint;

     */
    console.log('fadeTopicLayer');
    //if the sub topic layer is already visible, we don't have to destroy anything or fade out the topic layer
    if(topicLayer.getAttr('opacity') == 0){
        showSubTopic();
    } else {
        //if we're returning to this layer after having faded it out, make sure to reset the opacity
        subTopicLayer.removeChildren();
        subTopicLayer.opacity(1);
        subTopicLayer.add(currentSubTopicPoint);
        //subTopicLayer.add(subTopicPoint);
        subTopicLayer.draw();
        stage.add(subTopicLayer);
        currentLayer = subTopicLayer;
        var tween = new Konva.Tween({
            // list of tween specific properties
            node: topicLayer,
            duration: tweenDuration ,
            opacity: 0,
            onFinish: function() {
                //once the topic layer has faded, zoom in on the subtopic and add subtopic data elements
                showSubTopic();
            }
        });
        // play tween
        tween.play();
    }

}

function fetchCatalog(){
    //get a json representation of the policy questions and their associated data elements
    $.ajax({
        type: 'GET',
        data: {

        },
        url: '/v1/datacatalog',
        success: function(data) {
            //alert(data);
            var obj = data;
            catalog = obj;
            //console.log(obj.length);
            for (var i = 0; i < obj.length; ++i) {
                tmp = obj[i];
            }
            //console.log('catalog ',catalog );
            catalogInit();
        } //end success
    });

}

function focusTopic(topicPoint){
    console.log('focusTopic');
    console.log('currentLayer', currentLayer);
    //console.log(stage);
    //tween the stage to put the topic at the center
    currentTopic = catalog[topicPoint.attrs.catalogIndex];
    currentTopicPoint = topicPoint;
    $('#topic').html('&raquo; '+currentTopic.question.name);
    $('#main').html('<a href="#" onClick="fadeToLayer(\'mainLayer\');">Early Childhood Data Catalog</a>');
    //tweenStageToTopic();
    var x = (stage.width() / 2) - currentTopic.position.x;
    var y = (stage.height() / 2) - currentTopic.position.y;
    //console.log(circle);
    var tween = new Konva.Tween({
        // list of tween specific properties
        node: stage,
        duration: tweenDuration,
        x: x,
        y: y,
        onFinish: function() {
            //console.log('zooming');
            fadeMainLayer();
        }
    });
    // play tween
    tween.play();

}

function showSubTopic(){
    console.log('showSubTopic');


    var tween = new Konva.Tween({
        // list of tween specific properties
        node: currentSubTopicPoint,
        duration: tweenDuration ,
        radius: 40,
        onFinish: function() {
            //now show the details
            showSubTopicElements();
        }
    });
    // play tween
    tween.play();
}

function showSubTopicDetails(subTopic){
    console.log('showSubTopicDetails');
    currentSubTopic = currentTopic.subcategories[subTopic.attrs.catalogIndex];
    currentSubTopicPoint = subTopic;
    //console.log('subTopic',currentSubTopic);
    //console.log('subTopicPoint',subTopic);
    $('#topic').html('&raquo; '+'<a href="#" onclick="fadeToLayer(\'topicLayer\')" >'+currentTopic.question.name+'</a>');
    $('#subtopic').html('&raquo; '+currentSubTopic.question.name);
    tweenStageToSubTopic();

}

function showSubTopicElements(){

    console.log('elements', currentSubTopic.elements);
    console.log('element length', currentSubTopic.elements.length);
    console.log('sub topic layer', subTopicLayer);


    var elements = currentSubTopic.elements;
    var centerX = currentSubTopicPoint.attrs.x;
    var centerY = currentSubTopicPoint.attrs.y;
    for(var i = 1; i < (elements.length + 1); i++) {
        var element = elements[i-1];
        var variable = element.variable_name;
        //console.log('element', element);
        //layer.remove();
        var degrees = (360 / elements.length) * i;
        //console.log(degrees);
        var subTopicElementPosition = calculatePositionsCircle(centerX ,centerY ,
            stage.height() / 4,degrees);
        //console.log('subTopicPosition',subTopicPosition);
        //return false;
        element.position = subTopicElementPosition;
        // console.log('catalog ',catalog );
        var fill = i == 7 ? "red" : 'orange';
        var subTopicElementPoint = createSubTopicPoint(element.position.x, element.position.y, fill);
        subTopicElementPoint.setAttr('catalogIndex', i-1);

        subTopicElementPoint.on('click', function(evt) {
            showSubTopicElementDetails(evt.target, variable);
        });

        //set the direction of a text label
        var labelDirection = 'right';
        //the stage starts at 3 O'clock... so need to take that into consideration when determining if
        // text direction should go right or left
        if((degrees > 90) && (degrees < 270)){
            labelDirection = 'left';
        }

        tweenSubTopicLine(stage,subTopicLayer,centerX ,centerY ,
            element.position.x,element.position.y, subTopicElementPoint, variable, labelDirection);
        //topicLayer.add(currentTopicPoint);
        currentSubTopicPoint.moveToTop();
        subTopicLayer.draw();
        /*
        mainLayer.add(circle);
        tweenLine(stage,mainLayer,stage.width() / 2,stage.height() / 2,catalog[i-1].position.x,catalog[i-1].position.y, topicPoint,circle);
        //layer.add(topicPoint);
        stage.add(mainLayer);
        mainLayer.draw();

        */
    }

}

function showSubTopicElementDetails(element, variable){
    console.log('element', element);
    console.log('variable', variable);
    currentElement = element;
    $('#dataelement').html('&raquo; '+'<a href="#" onclick="testClose()" >'+variable+'</a>');
    var x = (stage.width() / 2) - currentElement.attrs.x;
    var y = (stage.height() / 2) - currentElement.attrs.y;
    tweenStagePosition(x,y,elementDetails);

}

function showText(layer, x,y,text, labelDirection, offsetX){

    var text = new Konva.Text({
        x: x + offsetX,
        y: y,
        text: text,
        fontSize: 15,
        fontFamily: 'Calibri',
        fill: 'green'
    });
    //console.log('showing text',labelDirection);
    if(labelDirection == 'left'){
        text.x(x - text.textWidth - offsetX);
    }
    layer.add(text);
}

function showTopic(topic){
    console.log('showTopic');
    console.log('currentLayer', currentLayer);
    currentLayer.opacity(1);
    //console.log('topicLayer',topicLayer);
    var tween = new Konva.Tween({
        // list of tween specific properties
        node: topic,
        duration: tweenDuration ,
        radius: 40,
        onFinish: function() {
            //now show the details
            //console.log('tweened topic', currentTopic);
            showTopicDetails();
        }
    });
    // play tween
    tween.play();
}

function showTopicDetails(){
    //currently this assumes the structure of main category -> topic -> subtopic -> data elements
    //console.log('showTopicDetails', topic);
    //get the catalog item for the topic
    //currentTopic = catalog[topic.attrs.catalogIndex];
    //console.log('catalog topic', currentTopic );
    //set up the navigation breadcrumb

    //console.log('topic catalog',topicCatalog);
    for(var i = 1; i < (currentTopic.subcategories.length + 1); i++) {
        //layer.remove();
        var degrees = (360 / currentTopic.subcategories.length) * i;
        //console.log(degrees);
        var subTopicPosition = calculatePositionsEllipse(currentTopicPoint.attrs.x,currentTopicPoint.attrs.y,
            stage.width() / 4,stage.height() / 4,degrees);
        //console.log('subTopicPosition',subTopicPosition);
        //return false;
        var subTopic = currentTopic.subcategories[i-1];
        currentTopic.subcategories[i-1].position = subTopicPosition;
        // console.log('catalog ',catalog );
        var fill = i == 7 ? "green" : 'blue';
        var subTopicPoint = createSubTopicPoint(currentTopic.subcategories[i-1].position.x, currentTopic.subcategories[i-1].position.y, fill);
        subTopicPoint.setAttr('catalogIndex', i-1);

        subTopicPoint.on('click', function(evt) {
            console.log('clicking sub topic point');
            showSubTopicDetails(evt.target);
        });

        var labelDirection = 'right';
        //the stage starts at 3 O'clock... so need to take that into consideration when determining if
        // text direction should go right or left
        if((degrees > 90) && (degrees < 270)){
            labelDirection = 'left';
        }

        tweenSubTopicLine(stage,topicLayer,currentTopicPoint.attrs.x,currentTopicPoint.attrs.y,
            currentTopic.subcategories[i-1].position.x,currentTopic.subcategories[i-1].position.y, subTopicPoint, subTopic.question.name, labelDirection);
        //topicLayer.add(currentTopicPoint);
        currentTopicPoint.moveToTop();
        topicLayer.draw();
        /*
        mainLayer.add(circle);
        tweenLine(stage,mainLayer,stage.width() / 2,stage.height() / 2,catalog[i-1].position.x,catalog[i-1].position.y, topicPoint,circle);
        //layer.add(topicPoint);
        stage.add(mainLayer);
        mainLayer.draw();

        */
    }
}

function tweenLine(stage,layer,  startX,startY, endX, endY,topicPoint,circle, text, labelDirection){
    var line = new Konva.Line({
        points: [startX, startY, startX, startY],
        stroke: '#61729e',
        strokeWidth: tweenDuration ,
        lineCap: 'round',
        lineJoin: 'round'
    });
    layer.remove();
    layer.add(line);
    circle.moveToTop();
    layer.draw();

    var tween = new Konva.Tween({
        // list of tween specific properties
        node: line,
        duration: 1,
        points: [startX, startY, endX, endY],
        onFinish: function() {
            var offsetX = topicPoint.attrs.radius + 10;
            showText(layer, endX, endY, text, labelDirection, offsetX);
            layer.add(topicPoint);
            //stage.add(layer);
            //console.log(circle);


// draw the image
            layer.draw();
        }
    });
// play tween
    tween.play();

}

function tweenSubTopicLine(stage,layer,startX,startY, endX, endY,subTopicPoint, text = '', labelDirection = ''){
    //console.log('labelDirection', labelDirection);
    var topicline = new Konva.Line({
        points: [startX, startY, startX, startY],
        stroke: '#61729e',
        strokeWidth: tweenDuration ,
        lineCap: 'round',
        lineJoin: 'round'
    });
   // console.log('line', topicline);
    //topicLayer.remove();
    layer.add(topicline);
    //circle.moveToTop();
    layer.draw();

    var tween = new Konva.Tween({
        // list of tween specific properties
        node: topicline,
        duration: 1,
        points: [startX, startY, endX, endY],
        onFinish: function() {
            console.log(subTopicPoint)
            var offsetX = subTopicPoint.attrs.radius + 10;
            layer.add(subTopicPoint);
            showText(layer, endX, endY, text, labelDirection, offsetX);
            //add text to the layer
            // draw the image
            layer.draw();
        }
    });
// play tween
    tween.play();

}

function tweenStageToSubTopic(){

    //find the offset of the circle center to the center of the stage, and then move the stage by that
    //console.log('tween sub topic',currentSubTopic);
    var x = (stage.width() / 2) - currentSubTopicPoint.attrs.x;
    var y = (stage.height() / 2) - currentSubTopicPoint.attrs.y;
    //console.log(circle);
    var tween = new Konva.Tween({
        // list of tween specific properties
        node: stage,
        duration: tweenDuration,
        x: x,
        y: y,
        onFinish: function() {
            console.log('fadingTopicLayer');
            fadeTopicLayer();
        }
    });
    // play tween
    tween.play();
}

function tweenStageToTopic(){

    //find the offset of the circle center to the center of the stage, and then move the stage by that
    //console.log('topic',currentTopic);
    var x = (stage.width() / 2) - currentTopic.position.x;
    var y = (stage.height() / 2) - currentTopic.position.y;
    //console.log(circle);
    var tween = new Konva.Tween({
        // list of tween specific properties
        node: stage,
        duration: tweenDuration,
        x: x,
        y: y,
        onFinish: function() {
            //console.log('zooming');
            fadeMainLayer();
        }
    });
    // play tween
    tween.play();
}

function tweenStagePosition(x,y,callbackFunction){
    //function to move the stage to a certain position
    //var x = (stage.width() / 2) - currentSubTopicPoint.attrs.x;
    //var y = (stage.height() / 2) - currentSubTopicPoint.attrs.y;
    //console.log(circle);
    var tween = new Konva.Tween({
        // list of tween specific properties
        node: stage,
        duration: tweenDuration,
        x: x,
        y: y,
        onFinish: callbackFunction
    });
    // play tween
    tween.play();
}

function renderChart(data, elt = 'elementChart', w, h) {

    //clear out the current chart

    console.log('chart data', data);
    console.log('ctx ', elt);
    var chartData = data;
    console.log('legend', data.show_legend);
    var showLegend = true;
    if (data.show_legend === undefined || data.show_legend === null || data.show_legend == 0) { showLegend = false; }
    //$('.wp-block-bbf-indicators').hide();
    //$('#' + elt).show();

    //var ctx = elt;
    //************ STACKED BAR CHART *********************************
    if (data.chart_type == 'stacked bar') {

        var conf = {
            type: 'bar',
            options: {
                title: {
                    display: true,
                    text: data.chart_title,
                    fontSize: 18
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            console.log('tooltipItem', tooltipItem);
                            console.log('chartData', chartData);
                            console.log('data', data);
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (chartData.y_data_type == 'percent') {
                                label += Number(tooltipItem.value).toFixed(1) + '%';
                            } else {
                                label += Number(tooltipItem.value);
                            }
                            return label;
                        }
                    }
                },
                legend: {
                    display: showLegend,
                    labels: {
                        fontColor: '#414141'
                    }
                },
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: {
                            min: 0,
                            callback: function (value, index, values) {
                                //console.log('ticks', value);
                                if (chartData.y_data_type == 'percent') {
                                    return (value).toFixed(0) + '%';
                                } else {
                                    return (value);
                                }
                            }
                        }
                    }]
                },
                plugins: {
                    datalabels: {
                        backgroundColor: function (context) {
                            return context.dataset.backgroundColor;
                        },
                        borderRadius: 4,
                        color: 'white',
                        font: {
                            weight: 'bold'
                        },
                        formatter: Math.round
                    }
                }
            },
            data: {
                labels: data.labels,
                datasets: data.datasets

            }

        }

        var yMax = Number(data.y_max);
        if (data.y_max === undefined || data.y_max === null || data.y_max == 0) { } else { conf.options.scales.yAxes[0].ticks.max = yMax; console.log('ticks', conf.options.scales.yAxes[0].ticks); }

        var yMin = Number(data.y_min);
        if (data.y_min === undefined || data.y_min === null || data.y_min == 0) { } else {
            conf.options.scales.yAxes[0].ticks.min = yMin; console.log('ticks', conf.options.scales.yAxes[0]);
        }

        var ctx = document.getElementById('indicatorChart');
        var stackedBarChart = new Chart(ctx, conf);

        currentChartObj = stackedBarChart;

    }
    //*************  END STACKED BAR CHART
    //************ BAR CHART *********************************
    if (data.chart_type == 'bar') {
        //set up bar char config object
        var conf = {
            type: 'bar',
            options: {
                title: {
                    display: true,
                    text: data.chart_title,
                    fontSize: 18
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (chartData.y_data_type == 'percent') {
                                label += Number(tooltipItem.value).toFixed(1) + '%';
                            } else {
                                label += Number(tooltipItem.value);
                            }
                            return label;
                        }
                    }
                },
                legend: {
                    display: showLegend,
                    labels: {
                        fontColor: '#414141'
                    }
                },
                scales: {
                    xAxes: [{

                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            callback: function (value, index, values) {
                                //console.log('ticks', value);
                                if (chartData.y_data_type == 'percent') {
                                    return (value).toFixed(0) + '%';
                                } else {
                                    return (value);
                                }
                            }
                        }
                    }]
                },
                plugins: {
                    datalabels: {
                        backgroundColor: function (context) {
                            return context.dataset.backgroundColor;
                        },
                        borderRadius: 4,
                        color: 'white',
                        font: {
                            weight: 'bold'
                        },
                        formatter: Math.round
                    }
                }
            },
            data: {
                labels: data.labels,
                datasets: data.datasets
            }
        }; //end config object
        //set y min/max values if they are configured
        console.log('y max', data.y_max);
        var yMax = Number(data.y_max);
        if (data.y_max === undefined || data.y_max === null || data.y_max == 0) { } else { conf.options.scales.yAxes[0].ticks.max = yMax; console.log('ticks', conf.options.scales.yAxes[0].ticks); }

        console.log('y min', data.y_min);
        var yMin = Number(data.y_min);
        if (data.y_min === undefined || data.y_min === null || data.y_min == 0) { } else { conf.options.scales.yAxes[0].ticks.min = yMin; console.log('ticks', conf.options.scales.yAxes[0].ticks); }

        console.log('config', conf);
        var ctx = document.getElementById('elementChart');
        console.log('ctx',ctx);
        //ctx.width  = w;
        //ctx.height = h;
        var barChart = new Chart(ctx, conf);

        currentChartObj = barChart;

    }
    //*************  END BAR CHART

    //************ DOUGHNUT CHART *********************************
    if (data.chart_type == 'doughnut') {
        console.log('rendering doughnut chart');
        var conf = {
            type: 'doughnut',
            options: {
                cutoutPercentage: 75,
                title: {
                    display: true,
                    text: data.chart_title,
                    fontSize: 18
                },
                tooltips: {
                    enabled: false
                },
                plugins: {
                    datalabels: {
                        display: false
                    }
                }
            },
            data: {
                labels: data.labels,
                datasets: data.datasets

            }

        }
        var ctx = document.getElementById('indicatorChart');
        var doughnutChart = new Chart(ctx,conf);

        currentChartObj = doughnutChart;

        var myChartExtend = Chart.controllers.doughnut.prototype.draw;

        drawIndicator(ctx, myChartExtend,data);

    }
    //*************  END PIE CHART

    //************ PIE CHART *********************************
    if (data.chart_type == 'pie') {
        console.log('rendering pie chart');
        var ctx = document.getElementById('indicatorChart');
        var pieChart = new Chart(ctx, {
            type: 'pie',
            options: {
                title: {
                    display: true,
                    text: data.chart_title,
                    fontSize: 18
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var label = data.labels[tooltipItem.index] || '';
                            if (label) {
                                label += ': ';
                            }
                            label += data.datasets[0].data[tooltipItem.index].toFixed(1) + '%';
                            return label;
                        }
                    }
                }
            },
            data: {
                labels: data.labels,
                datasets: data.datasets

            }

        });

        currentChartObj = pieChart;

    }
    //*************  END PIE CHART

    //************ LINE CHART *********************************
    if (data.chart_type == 'line') {
        console.log('rendering line chart');
        var ctx = document.getElementById('indicatorChart');
        var conf = {
            type: 'line',
            options: {
                title: {
                    display: true,
                    text: data.chart_title,
                    fontSize: 18
                },
                scales: {
                    xAxes: [{

                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            callback: function (value, index, values) {
                                //console.log('ticks', value);
                                if (chartData.y_data_type == 'percent') {
                                    return (value) + '%';
                                } else {
                                    return (value);
                                }

                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            console.log('tooltipItem', tooltipItem);
                            console.log('data', data);
                            var label = data.labels[tooltipItem.index] || '';
                            if (label) {
                                label += ': ';
                            }
                            if (chartData.y_data_type == 'percent') {
                                label += data.datasets[0].data[tooltipItem.index] + '%';
                            } else {
                                label += data.datasets[0].data[tooltipItem.index];
                            }

                            return label;
                        }
                    }
                },
                legend: {
                    display: showLegend,
                    labels: {
                        fontColor: '#414141'
                    }
                }
            },
            data: {
                labels: data.labels,
                datasets: data.datasets

            }
        };
        var yMax = Number(data.y_max);
        var yMin = Number(data.y_min);

        console.log('ymin', data.y_min);
        if (data.y_max === undefined || data.y_max === null || data.y_max == 0) { } else {
            console.log('setting y max to: ' + yMax);
            conf.options.scales.yAxes[0].ticks.max = yMax;
        }
        if (data.y_min === undefined || data.y_min === null || data.y_min == 0) { } else {
            console.log('setting y min to: ' + yMin);
            conf.options.scales.yAxes[0].ticks.min = yMin;
        }
        console.log('conf', conf);
        var lineChart = new Chart(ctx, conf );

        currentChartObj = lineChart;

    }
    //*************  END LINE CHART

    $('#progress').hide();

    //console.log('chart options', theChart.options);
    //theChart.options.scales.xAxes[0].stacked = true;
    //theChart.options.scales.yAxes[0].stacked = true;

}

