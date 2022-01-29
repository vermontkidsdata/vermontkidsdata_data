//create an ACS object so we can manage state in a more reliable way
var variables = [];
var tableCount = 1;
var dataCombined = 0;
var variableName = "";
var currentTable;
var params = new Object();

function startTransaction(){
	$('#progress').show();
}

function endTransaction(){
	$('#progress').hide();
}

function updateParameters(){
	
	//update parameters with the selected values of the user inputs
	params.variables = variables.toString();
	params.dataset = $('#dataset').val();
	params.state = $('#state').val();
	params.year = $('#year').val();
	params.geography = $('#geography').val();
	params.geoGroup = $( "#geoGroup" ).val();
	
}

function addTableRow(){
	
	var c = [];
	c.push({ data: null, render: 'name' });
	c.push({ data: null, render: 'position' });
	c.push({ data: null, render: 'salary' });
	c.push({ data: null, render: 'start_date' });
	//console.log(c); return false;
	
	var table = $('#results-table').DataTable({
        columns: c
    });
 
	table.row.add( {
        "name":       "Tiger Nixon",
        "position":   "System Architect",
        "salary":     "$3,120",
        "start_date": "2011/04/25"
    } ).draw();
	
	table.row.add( {
        "name":       "Tigress Nixon",
        "position":   "System Architect",
        "salary":     "$3,120",
        "start_date": "2011/04/25"
    } ).draw();

}
function addVariable(variable){
	
	if(variables.includes(variable) == false){
		$('#'+variable).addClass('active');
		variables.push(variable);
	} else {
		for( var i = 0; i < variables.length; i++){ if ( variables[i] === variable) { variables.splice(i, 1); }}
		$('#'+variable).removeClass('active');
	}
	
	console.log(variables);
}

function getCensusAcsData(){
		
	startTransaction();
	updateParameters();
	params.dataCombined = 0;
	/* All the census api calls and associated processing done via the internal API return promises, as they 
	are run asynchronously
	*/
	let acsData = fetchAcs(params.geography, params.year, params.state, params.variables, params.dataset);
	
	//acsData.then(acsData => console.log(acsData) )
	
	
	acsData.then( 
		data => processRawACSData(data)
	).then(
		data => drawDataTable(data, 'results', 'results-table'+tableCount)
	).then(
		msg => showTable()
	);
	
	
	/*
	console.log(url); 
	$.ajax({
		
		  type: 'POST',
		  data: {
			  variables: v,
			  dataset: dataset,
			  state: state,
			  year: year,
			  geography: geography
		  },
		  url: url,
		  success: function(data) {
			  
			 console.log('original data', data);
			 //return false;
			 //renderData(data);
			  
		  } //end success
	});	
	*/
}

function combineDialog(){
	$( "#dialogCombine" ).dialog( "open" );
}

function combineAcsDataSet(){
	
	$( "#dialogCombine" ).dialog( "close" );	
	startTransaction();
	updateParameters();
	params.dataCombined = 1;
	params.variableName = $('#combineName').val();
	/* All the census api calls and associated processing done via the internal API return promises, as they 
	are run asynchronously
	*/
	let acsData = fetchAcs(params.geography, params.year, params.state, params.variables, params.dataset);
	
	acsData.then( 
		data => processRawACSData(data)
	).then(
		data => combineProcessedACSData(data, params.variables, params.variableName)
	).then(
		data => drawDataTable(data, 'results', 'results-table'+tableCount)
	).then(
		msg => showTable()
	);

	
	
	$('#progress').show();
	dataCombined = 1;
	//console.log(variables.toString());
	variableName = $('#combineName').val();
	//console.log(variableName); return false;
	
	if (variableName  != null) {
	
	/*	
	$('#results').html('');
	tableHTML = '<table id="results-table'+tableCount+'" cellpadding=5 ></table>';
	$('#results').html(tableHTML);
	
	var v = variables.toString();
	var dataset = $('#dataset').val();
	var state = $('#state').val();
	var year = $('#year').val();
	var geography = $('#geography').val();
	var url = '/v1/acs_data_combine/';
	console.log(url); 
	$.ajax({
		  type: 'POST',
		  data: {
			  variables: v,
			  dataset: dataset,
			  state: state,
			  year: year,
			  geography: geography,
			  variableName: variableName
		  },
		  url: url,
		  success: function(data) {
			  
			 console.log('processed combined data',data);
			 renderCombinedData(data);
			 
			  
		  } //end success
	});
	
	*/
	
	}
	
	
}

function getBootstrapDeviceSize() {
	//return $('#users-device-size').find('div:visible').first().attr('id');
	// https://stackoverflow.com/a/8876069
	const width = Math.max(
		document.documentElement.clientWidth,
		window.innerWidth || 0
	)
	if (width <= 576) return 'xs'
	if (width <= 768) return 'sm'
	if (width <= 992) return 'md'
	if (width <= 1200) return 'lg'
	return 'xl'
}

function groupDialog(){
	var geography = $('#geography').val();
	if(geography != 'county+subdivision'){
		$( "#dialogGroupAlert" ).dialog( "open" );
	} else {
		$( "#dialogGroup" ).dialog( "open" );
	}

}

function groupAcsDataSet(){
	
	$( "#dialogGroup" ).dialog( "close" );
	startTransaction();
	updateParameters();
	var dataToGroup;
	
	let acsData = fetchAcs(params.geography, params.year, params.state, params.variables, params.dataset);
	
	
	acsData.then( 
		data => processRawACSData(data)
	).then( function(data) {
			//need to check here if data needs to be combined. 
			if(params.dataCombined == 1){
				dataToCombine = combineProcessedACSData(data, params.variables, params.variableName);
				dataToCombine.then( function(combinedData) { 
					dataToGroup = combinedData;
					console.log('data combined',dataToGroup);
					groupedData = groupProcessedACSData(dataToGroup, params.geoGroup);
					groupedData.then(function(data){
						dataToDraw = drawDataTable(data, 'results', 'results-table'+tableCount);
						dataToDraw.then(
							msg => showTable()
						)
					})
				})
			} else {
				//not need to combine, just group it as is				
				dataToGroup = data;
				console.log('data not combined',dataToGroup);
				groupedData = groupProcessedACSData(dataToGroup, params.geoGroup);
				groupedData.then(function(data){
					dataToDraw = drawDataTable(data, 'results', 'results-table'+tableCount);
						dataToDraw.then(
							msg => showTable()
						)
				})
			}
		}
	);
	
	
	
	/*
	.then(
		data => combineProcessedACSData(data, params.variables, params.variableName)
	).then(
		data => drawDataTable(data, 'results', 'results-table'+tableCount)
	).then(
		msg => showTable()
	);
	*/

		
	$('#results').html('');
	tableHTML = '<table id="results-table'+tableCount+'" cellpadding=5 ></table>';
	$('#results').html(tableHTML);
	
	var v = variables.toString();
	var dataset = $('#dataset').val();
	var state = $('#state').val();
	var year = $('#year').val();
	var geography = $('#geography').val();
	var url = '/v1/acs_data_group/';
	//console.log(url); 
	/*
	$.ajax({
		  type: 'POST',
		  data: {
			  variables: v,
			  dataset: dataset,
			  state: state,
			  year: year,
			  geography: geography,
			  variableName: variableName,
			  dataCombined: dataCombined,
			  geoGroup: geoGroup
		  },
		  url: url,
		  success: function(data) {
			  
			 console.log(data);
			 if(dataCombined == 0){
				 renderData(data, 'group');
			 } else {
				 renderCombinedData(data, 'group');
			 }
			 
			  
		  } //end success
	});
	*/
	
}

function renderCombinedData(data,dataType = null){
	console.log(data);
	console.log('dataType', dataType);
	//return false;
	var c = [];
	  $.each(data, function( index, value ) {
		  if(index > 0){
		  } else {
			  $.each(value, function( idx, val ) {
				  console.log('c data',idx+' : '+val);
				  c.push({ data: null, render: val, title: val });
				  //c.push({ title: val, render: val, data:0 });
			  });
		  } 
	  });
	  console.log(c);			 
	  
	  var table = $('#results-table'+tableCount).DataTable({
		  columns: c,
		  "pageLength": 25,
		  dom: 'B<"clear">lfrtip',
		  buttons: [ 'copy', 'csv', 'excel' ]
	  });
	  
	  if(dataType == 'group'){
		//if the data is being grouped by geography, we need keys with the geo type
		 $.each(data, function( index, value ) {
			console.log('index',index);
		  	console.log('value',value);
		  if(index > 0){
			  var d = new Object();
				//d['head_start'] = 'TEST';
				//d['xxx'] = 500;
			  $.each(c, function( idx, val ) {
					console.log('c idx',idx);
		  			console.log('c val',val);
					d[val.title] = value[val.title];
				  //var columnName = c[idx];	
				  //d[columnName.title] = val;
			  });
			  table.row.add(d).draw(); 
		  }
	  });

	  } else {
	  $.each(data, function( index, value ) {
		 
		  if(index > 0){
			  var d = new Object();
			  $.each(value, function( idx, val ) {
				//console.log('idx',idx);
		  		//console.log('val',val);
				  var columnName = c[idx];	
				  //console.log('column name',columnName);
		// d['head_start'], d['xxx']'
				  d[columnName.title] = val;
			  });
			  //console.log('d',d);
			  //return false;
			  table.row.add(d).draw(); 
		  }
	  });

	}
	  
	  tableCount ++;
	  
	  currentTable = table;
	  
	  $('#progress').hide();
	  $('#variable-list').hide();
	  $('#results').show();
}


function renderData(data){
	 
	  
	  var c = [];
	  $.each(data, function( index, value ) {
		  if(index > 0){
		  } else {
			  $.each(value, function( idx, val ) {
				  console.log(idx+' : '+val);
				  c.push({ data: null, render: val, title: val });
				  //c.push({ title: val, render: val, data:0 });
			  });
		  } 
	  });
	  console.log(c);			 
	  
	  var table = $('#results-table'+tableCount).DataTable({
		  columns: c,
		  "pageLength": 25,
		  dom: 'B<"clear">lfrtip',
		  buttons: [ 'copy', 'csv', 'excel' ]
	  });
	  
	  
	  
	  $.each(data, function( index, value ) {
		  //console.log(value);
		  if(index > 0){
			  var d = new Object();
			  $.each(value, function( idx, val ) {
				  var columnName = c[idx];
				  //console.log(columnName);
				  d[columnName.title] = val;
			  });
			  //console.log(d);
			  //return false;
			  table.row.add(d).draw(); 
		  }
	  });
	  
	  tableCount ++;
	  
	  currentTable = table;
	  
	  $('#progress').hide();
	  $('#variable-list').hide();
	  $('#results').show();
}

function saveDataDialog(){
	$( "#dialogSave" ).dialog( "open" );
}

function saveData(){
	
	$( "#dialogSave" ).dialog( "close" );
	var v = variables.toString();
	var dataset = $('#dataset').val();
	var datasetName = $('#datasetName').val();
	var state = $('#state').val();
	var year = $('#year').val();
	var geography = $('#geography').val();
	var combineName = $('#combineName').val();
	var geoGroup = $( "#geoGroup" ).val();
	var userId = $( "#userId" ).val();
	var useMostRecent = $( "#use_most_recent" ).val();
	var url = '/v1/acs_dataset/';
	// Increment a counter for each row
	currentTable.data().each( function (d) {
	    console.log(d);
	} );
	
	$.ajax({
		
		  type: 'POST',
		  data: {
			  variables: v,
			  dataset: dataset,
			  name: datasetName,
			  state: state,
			  year: year,
			  geography: geography,
			  combine_name: combineName,
			  geo_group: geoGroup,
			  dataset_owner: userId,
			  use_most_recent: useMostRecent
		  },
		  url: url,
		  success: function(data) {
			  
			 console.log(data);
			 //return false;
			  
		  } //end success
	});
}

function searchConcepts() {
	
	var searchTxt = $('#search-text').val();
	console.log(searchTxt);
	$('#concepts').empty();
	$.ajax({
		  type: 'GET',
		  url: '/v1/search_concepts/'+searchTxt,
		  success: function(data) {
			  console.log(data);
			  $.each(data, function( index, value ) {
				  $('#concepts').append(new Option(value.group+" : "+value.concept, value.concept)); 
			  });
		  } //end success
	});	
	
}
function searchVariables() {
	
	$('#results').hide();
	
	var concept = encodeURI($('#concepts').val());
	concept = concept.replace(/\(/g, '%28').replace(/\)/g, '%29');
	//console.log(concept); return false;
	variables = [];
	$('#variable-list').html('');
	$('#variable-list').show();
	console.log(concept);
	//return false;
	$.ajax({
		  type: 'GET',
		  url: '/v1/search_concept_variables/'+concept,
		  success: function(data) {
			  console.log(data);
			  var listHtml = '';
			  $.each(data, function( index, value ) {
				  listHtml += '<a id="'+value.variable+'" href="#" onclick="addVariable(\''+value.variable+'\');" class="list-group-item list-group-item-action">'+value.label+'</a>';
			  });
			  $('#variable-list').html(listHtml);
		  } //end success
	});	
	
}

function showTable(){
	  
	endTransaction();
	$('#variable-list').hide();
	$('#results').show();
}

