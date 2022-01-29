function editReportGroup(id){
	var modal = $('#rgModal');
	
	var variables = $('#report_group_variables_'+id).val();
	var name = $('#report_group_name_'+id).val();
	var id = $('#report_group_id_'+id).val();
	$('#rgModalLabel').html('Report Group');
	$('#report_group_variables').val(variables);
	$('#report_group_name').val(name);
	$('#report_group_id').val(id);
	console.log('variables: '+id);
	console.log(modal);
}
function saveReportGroup(){
	var variables = $('#report_group_variables').val();
	var name = $('#report_group_name').val();
	var id = $('#report_group_id').val();
	//console.log(variables); return false;
	$.ajax({
		  type: 'POST',
		  data: {
					table_variables: variables,
					report_group_name: name,
					id_acs_report_group: id
				},
		  url: '/reportgroup/ajax_save_variables',
		  success: function(data) {
			  console.log(data);
			  $('#rgModal').modal('hide');
			  //update the field
			  var vars = variables.split(',');
			  console.log(vars);
			  var varStr = '';
			  $.each(vars, function( index, value ) {
				  varStr += value+'<br>';
				});
			  $('#variables_display_'+id).html(varStr);
			  $('#report_group_variables_'+id).val(variables);
			  $('#report_group_name_'+id).val(name);
		  } //end success
	});	
}
