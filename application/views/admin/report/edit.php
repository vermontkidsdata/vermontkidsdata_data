<?php $this->load->view('admin/header'); ?>

<form method="post" action="<?php echo base_url();?>report/save">
<div class="container">
	<div class="row">
		<div class="col-12">
		<h3><?php echo $reportfunction; ?> ACS Report</h3>
		</div>
	</div>
	
	<div class="row">
	<div class="col-12">		
		 	<div class="form-group">
				<input class="form-control" type="text" placeholder="Report Name" id="report-name" name="report_name">
			</div>
			 <div class="form-group">
				<input class="form-control" type="text" placeholder="Report Table" id="report_table" name="report_table">
			</div>
			<div class="form-group">
	    	<label for="reportSchema">Report Schema</label>
	    	<textarea class="form-control" id="reportSchema" name="report_schema"  rows="3"></textarea>
	  	</div>		
	</div>
	</div>
	
	<div class="row">
	  <div class="col-6">	 
	  	<div class="form-group"> 	
				<label for="baselineYear">Baseline Year</label>
		    <select multiple class="form-control" id="baseline_year" name="baseline_year">
		    <?php foreach($years as $y) { ?>
		      <option><?php echo $y->year; ?></option>
		      <?php } ?>
		    </select>
		   </div>
		   <div class="form-group">
		   	<input class="form-control" type="text" placeholder="Primary Measure" id="primary_measure" name="primary_measure">
		   </div>
		   <label>Primary Measure Sort</label>
		   <div class="form-group">		   
		   	<div class="form-check form-check-inline">
				  <input class="form-check-input" type="radio" name="primary_measure_sort" id="primary_measure_sort1" value="asc">
				  <label class="form-check-label" for="inlineRadio1">asc</label>
				</div>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" type="radio" name="primary_measure_sort" id="primary_measure_sort2" value="desc">
				  <label class="form-check-label" for="inlineRadio2">desc</label>
				</div>
		   </div>			
		   <div class="form-group">
		   	<input class="form-control" type="text" placeholder="Denominator" id="denominator" name="denominator">
		   </div>
  	</div>
	 </div>
	 
	 <div class="row">
	  <div class="col-3">	
	  	<input class="btn btn-primary" type="submit" value="Submit">
	  </div>
	 </div>
	
</div>
</form>

<?php $this->load->view('admin/footer'); ?>
