/*
 All functions that interact with the various APIs to retreive and process data return promises, since we need things to 
run asynchronously
*/

function drawDataTable(data, tableContainer, tableElt){
	 //draw a data table from a structured data set, assuming the first element of the array/object is
	// a row of column headings.  'tableElt' is the div where the table will be rendered on the page 
	return new Promise(function(resolve, reject) {
		
		//set up the table elements on the page
		console.log('table data to draw', data);
		$('#'+tableContainer).html('');
		tableHTML = '<table id="'+tableElt+'" cellpadding=5 ></table>';
		$('#results').html(tableHTML);
		
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
		  console.log('columns',c);			 
		  
		  var table = $('#'+tableElt).DataTable({
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
	currentTable = table;
	tableCount ++;
	msg = 'table drawn';
	resolve(msg);

	});
	  

}

function combineProcessedACSData(acsdata, variables, variableName){
	/*
		combine the variables of a raw dataset into one variable
	*/
	return new Promise(function(resolve, reject) {
		var url = '/v1/combine_processed_acs/';
		$.ajax({
		  type: 'POST',
		  data: {
			  acsdata: acsdata,
			  variables: variables,
			  variableName: variableName
		  },
		  url: url,
		  success: function(data) {
			 resolve(data);			  
		  } //end success
		});
	});	
	
}


function fetchAcs(geography, year, state, variables, dataset){
	/*
		this is the first step of any census data action - it simply passes a standard Gazetteer data type (state/county/county+subdivision)
		into the Census API, gets the raw data, and puts it into a readable array of names and values
	*/
	return new Promise(function(resolve, reject) {
		var url = '/v1/fetch_acs/';
		$.ajax({
			
			  type: 'POST',
			  data: {
				  variables: variables,
				  state: state,
				  year: year,
				  geography: geography,
				  dataset: dataset
			  },
			  url: url,
			  success: function(data) {
				 //fetchCallback(data);		
				resolve(data);	  
			  } //end success
		});	
	});	
	
}

function groupProcessedACSData(acsdata, geogroup){
	/*
		combine the variables of a raw dataset into one variable
	*/
	return new Promise(function(resolve, reject) {
		var url = '/v1/group_processed_acs/';
		$.ajax({
		  type: 'POST',
		  data: {
			  acsdata: acsdata,
			  geogroup: geogroup
		  },
		  url: url,
		  success: function(data) {
			 resolve(data);			  
		  } //end success
		});
	});	
	
}

function processRawACSData(acsData){
	
	/* Create columns headings that replace variable IDs with names, and then convert the 
	numerical FIPS codes in the returned data into readable names, e.g.
	"county", "state", "Estimate!!Total"
	"Rutland County", "Vermont", "56742"
	
	** takes a raw json response from the census API as an argument
	 
	*/
	return new Promise(function(resolve, reject) {
		var url = '/v1/process_acs/';
		$.ajax({
			
			  type: 'POST',
			  data: {
				  acsdata: acsData
			  },
			  url: url,
			  success: function(data) {
				 resolve(data);			  
			  } //end success
		});	
	});
	
}




