<?php $this->load->view('admin/header'); ?>

<div><a href="<?php echo base_url();?>report">back to reports</a></div>

<h3>Report Groups: <?php echo $report->report_name; ?></h3>

<form method="post" action="<?php echo base_url();?>reportgroup/generate_defaults">
<input type="hidden" value="<?php echo $report->id_acs_report; ?>" name="report_id" id="report_id" />

<div class="container">
	<div class="row">
		<div class="col-12">
		<h4>Create Report Groups</h4>
			<p>Fill out the fields below to generate stub records for all report groups for this report.  Note that all records will  
			contain placeholder data and will need to be edited</p>
		</div>
	</div>
	<div class="row">
		
		<div class="col-6">
		
			
			<div class="form-group"> 	
				<label for="variable_groups">Variable Groups (comma separated)</label>
		    <input class="form-control" type="text" id="variable_groups" name="variable_groups" value="">
		   </div>
		   
		   <div class="form-group"> 	
				<label for="age_categories">Age Categories (comma separated - use 'All' for no age categories)</label>
		    <input class="form-control" type="text" id="age_categories" name="age_categories" value="">
		   </div>
		   
		   <div class="form-group"> 	
				<label for="additional_columns">Sex </label>
		    <div class="form-check form-check-inline">
				  <input class="form-check-input" type="checkbox" id="additional_columns1" name="genders1" value="male">
				  <label class="form-check-label" for="inlineCheckbox1">Male</label>
				</div>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" type="checkbox" id="additional_columns2" name="genders2"  value="female">
				  <label class="form-check-label" for="inlineCheckbox2">Female</label>
				</div>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" type="checkbox" id="additional_columns3" name="genders3"  value="all">
				  <label class="form-check-label" for="inlineCheckbox3">All</label>
				</div>
		   </div>
 			<div class="form-group"> 	
				<label for="template">Report Template</label>
		    <select class="form-control" id="template" name="template">
		    <?php 
		    foreach($templates as $t) { ?>
		      <option><?php echo $t; ?></option>
		      <?php  } ?>
		    </select>
		   </div>
				
		   
		</div>
		<div class="col-6">
			<div class="form-group"> 	
				<label for="numerator_name">Numerator Name</label>
		    <input class="form-control" type="text" id="numerator_name" name="numerator_name" value="">
		   </div>
		   <div class="form-group"> 	
				<label for="denominator_name">Denominator Name</label>
		    <input class="form-control" type="text" id="denominator_name" name="denominator_name" value="">
		   </div>
		   <div class="form-group"> 	
				<label for="additional_columns">Additional ACS Data Column Names (comma separated)</label>
		    <input class="form-control" type="text" id="additional_columns" name="additional_columns" value="">
		   </div>
		  
		   <div style="margin-bottom: 1em;">
			  	<input class="btn btn-primary" type="submit" value="Create">
				</div>
		</div>
		
	</div>
</div>
</form>

<table width="100%" border=1>
<th></th>
<th>Report ID</th>
<th>Group ID</th>
<th>Variables</th>
<th>Group Name</th>

<?php
$groupCnt = 1;
foreach($reportGroups as $rg){
?>

<tr>
	<td><?php echo $groupCnt; ?>. </td>
	<td valign="top"><?php echo $rg->report_id; ?></td>
	<td valign="top"><?php echo $rg->report_group_id; ?></td>
	<td>
	<div id="variables_display">
	<div style="float: right"><a onclick="editReportGroup('<?php echo $rg->id_acs_report_group; ?>');" data-toggle="modal" data-target="#rgModal"><i class="fa fa-edit"></i></a></div>
	<div id="variables_display_<?php echo $rg->id_acs_report_group; ?>">
	<?php 
		$variables = explode(",",$rg->table_variables);
		foreach($variables as $v){ echo $v.'<br>'; }
	?>
	</div>
	<input type="hidden" value="<?php echo $rg->id_acs_report_group; ?>" id="report_group_id_<?php echo $rg->id_acs_report_group; ?>"></input> 
	<input type="hidden" value="<?php echo $rg->table_variables; ?>" id="report_group_variables_<?php echo $rg->id_acs_report_group; ?>"></input> 
	<input type="hidden" value="<?php echo $rg->report_group_name; ?>" id="report_group_name_<?php echo $rg->id_acs_report_group; ?>"></input> 
	</div>
	
	</td>
	<td valign="top">
	<?php 
		$names = explode("!!",$rg->report_group_name);
		foreach($names as $v){ echo $v.'<br>'; }
	?>
	<?php //echo $rg->report_group_name; ?>
	</td>

</tr>

<?php $groupCnt += 1; } ?>

</table>

<!-- Variable Modal -->
<div class="modal fade" id="rgModal" tabindex="-1" role="dialog" aria-labelledby="rgModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rgModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<input type="hidden" value="" id="report_group_id" style="width: 100%" ></input>
        <div style="margin-bottom: .5em;"><input type="text" value="" id="report_group_variables" style="width: 100%" ></input></div>
        <input type="text" value="" id="report_group_name" style="width: 100%" ></input>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveReportGroup();">Save changes</button>
      </div>
    </div>
  </div>
</div>




<?php $this->load->view('admin/footer'); ?>