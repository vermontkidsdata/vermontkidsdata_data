<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container-fluid">

<div class="main-container">

	<div class="row">
		<div><h4>Search Census.gov American Community Survey (ACS)</h4></div>
		<input type="hidden" value="2147484848" id="userId" />
	</div>
	
	<div class="row" >
	<div class="col-4">
	<div class="input-group mb-3" style="margin-top: 20px">
		  <div class="input-group-prepend">
		    <label class="input-group-text" for="dataset">Data Set</label>
		  </div>
		  <select class="custom-select" id="dataset">
				<option value="5">American Community Survey (ACS) 5 Year Estimates</option>
		  </select>
		</div>
	</div>
	<div class="col-2">
	<div class="input-group mb-3" style="margin-top: 20px">
		  <div class="input-group-prepend">
		    <label class="input-group-text" for="state">State</label>
		  </div>
		  <select class="custom-select" id="state">
				<option value="50">VT</option>
		  </select>
		</div>
	</div>
		
		<div class="col-2">
		<div class="input-group mb-3" style="margin-top: 20px">
			  <div class="input-group-prepend">
			    <label class="input-group-text" for="year">Year</label>
			  </div>
			  <select class="custom-select" id="year">
					<option value="2019">2019</option>
					<option value="2018">2018</option>
					<option value="2017">2017</option>
					<option value="2016">2016</option>
					<option value="2015">2015</option>
					<option value="2014">2014</option>
					<option value="2013">2013</option>
					<option value="2012">2012</option>
					<option value="2011">2011</option>
					<option value="2010">2010</option>
					<option value="2009">2009</option>
			  </select>
			</div>
			</div>

		<div class="col-4">
		<div class="input-group mb-3"  style="margin-top: 20px">
		  <div class="input-group-prepend">
		    <label class="input-group-text" for="geography">Geography</label>
		  </div>
		  <select class="custom-select" id="geography">
				<option value="us">United States</option>
				<option value="state">State</option>
				<option value="county" selected>County</option>
				<option value="county+subdivision">County Subdivision</option>
		  </select>
		</div>
		</div>
		</div>
		
	<div class="row" >
	<div class="col">
		<div>
			<div class="input-group mb-3">
			<div class="input-group-prepend">
		    <label class="input-group-text" for="search-text">Search Concepts</label>
		  </div>				  
				  <input type="text" class="form-control" id="search-text" aria-describedby="search-text" style="width: 500px;" value="poverty">
				  
				  <div class="input-group-append" onclick="searchConcepts();" >
				    <span class="input-group-text" id="basiaddon3"><svg class="c-icon">
						<use xlink:href="/images/coreui/sprites/free.svg#cil-search"></use>
						</svg></span>
					</div>
				</div>
		</div>
		
		<div class="input-group mb-3">
		  <div class="input-group-prepend">
		    <label class="input-group-text" for="concepts">Concepts</label>
		  </div>
		  <select class="custom-select" id="concepts">
				
		  </select>
		</div>
		
		
	
	</div>
	
	</div>
	
	<div class="row">
		<div class="col">
			<div>
			<button type="button" class="btn btn-light" onclick="searchVariables();" >Get concept variables</button>
		</div>
		</div>
		<div class="col">
			<div>
				<button type="button" class="btn btn-light" onclick="getCensusAcsData();" >Fetch Data From Census.gov</button> 
				<button type="button" class="btn btn-light" onclick="combineDialog();" >Combine Variables</button> 
				<button type="button" class="btn btn-light" onclick="groupDialog();" >Group by Geography</button>
				<button type="button" class="btn btn-light" onclick="saveDataDialog();" >Save Report</button>
			</div>
			<div id="progress" style="display: none"><img src="/images/ajax-loader.gif" /></div>
		</div>
	</div>
	
	<div class="row">	
		
			<div class="col">
				<div class="list-group" style="margin-top: 20px;" id="variable-list"></div>
			</div>
			
	
	</div>
	
	<div class="row">	
		<div class="col">
		
				<div id="results" style="margin-top: 20px;">
					
				</div>
			</div>
	</div>

</div>

</div>

<div id="dialogCombine" title="Combine Variables" >
	<div >Enter a name for this grouping of variables</div>
	<div style="margin-top: 20px; ">
		<input style="width: 100%; margin-bottom: 10px;" type="text" value="" id="combineName" name="combineName" /> <button type="button" class="btn btn-light" onclick="combineAcsDataSet();" >Combine</button>
	</div>
</div>

<div id="dialogSave" title="Save Data" >
	<div >Enter a name for this dataset</div>
	<div style="margin-top: 20px; width:100% ">
		<div><input style="width: 100%; margin-bottom: 10px;" type="text" value="" id="datasetName" name="datasetName" /> </div>
		<div>Use most recent ACS year or selected year? <select id="use_most_recent" name="use_most_recent">
			<option value="1">Most recent</option>
			<option value="0">Selected</option>
		</select>
		</div>
		<div><button type="button" class="btn btn-light" onclick="saveData();" >Save</button></div>
	</div>
</div>

<div id="dialogGroup" title="Your Geography Maps" >
	<div >Select a geography to group data</div>
	<div style="margin-top: 20px;">
		<select id="geoGroup" name="geoGroup">
			<option value="none">-- Select a Geography Group --</option>
			<?php foreach($geoMaps as $m) { ?>
			<option value="<?php echo $m->geography_map_type; ?>"><?php echo $m->geography_map_label; ?></option>
			<?php } ?>
		</select> <button type="button" class="btn btn-light" onclick="groupAcsDataSet();" >Group</button>
	</div>
</div>

<div id="dialogGroupAlert" title="Cannot Group Data" >
	<div >You must group data from a count subdivision (town) search!</div>
</div>


<script>
$( "#dialogGroup" ).dialog({ autoOpen: false, height: 200, width: 400 });
$( "#dialogGroupAlert" ).dialog({ autoOpen: false, height: 150, width: 500 });
$( "#dialogCombine" ).dialog({ autoOpen: false, height: 200, width: 400 });
$( "#dialogSave" ).dialog({ autoOpen: false, height: 200, width: 600 });
</script>


<?php $this->load->view('footer'); ?>
