<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<script type="text/javascript">

	function chartDataSource() {
		var chartType = $('#chart_type').val();
		var chartDatasource = $('#chart_datasource').val();
		//hide all the conditional fields
		$('#census_report_row').hide();
		$('#census_report_denominator_row').hide();
		$('#dataset_row').hide();
		$('#api_row').hide();
		$('#sql_row').hide();
		
		if(chartDatasource == 'census'){
			$('#census_report_row').show();
			$('#census_report_denominator_row').show();
		}
		if(chartDatasource == 'dataset'){
			$('#dataset_row').show();
		}
		if(chartDatasource == 'api'){
			$('#api_row').show();
		}
		if(chartDatasource == 'sql'){
			$('#sql_row').show();
		}
		console.log(chartDatasource);
	}

function chartType(){

	var chartType = $('#chart_type').val();
	if(chartType == 'combination'){
		$('#standard-visualization').hide();
		$('#grouped-visualization').show();
	} else {
		$('#grouped-visualization').hide();
		$('#standard-visualization').show();
	}
	console.log(chartType);
	
}
function dataSet(){
	var dataset = $('#dataset').val();
	
	
	$.get( "/v1/havyc_dataset/"+dataset, function( data ) {
		console.log(data);

		if (data.hasOwnProperty("columns")) {

			$('#labels').empty();
			$('#label_column').empty();
			$('#data_column').empty();
			$('#order_column').empty();
			//$('#columns').html(data.columns);
			var columns = data.columns;
			var columnsArray = columns.split(',');
			//add additional reporting columns here...
			columnsArray.push('NAME');
			$.each(columnsArray, function (index, value) {
				if (value != 'id' && value != 'is_current') {
					$('#labels').append(new Option(value, value));
					$('#label_column').append(new Option(value, value));
					$('#data_column').append(new Option(value, value));
					$('#order_column').append(new Option(value, value));
				}
			});

		}

		  <?php if(isset($chart)){ ?>
		  $('#labels').val('<?php echo $chart->labels; ?>');
		  $('#label_column').val('<?php echo $chart->label_column; ?>');
		  $('#data_column').val('<?php echo $chart->data_column; ?>');
		  $('#order_column').val('<?php echo $chart->order_column; ?>');
			<?php } ?>
			
		  //$('#select1').append(new Option(optionText, optionValue)); 
		});
	//console.log(dataset);
}
</script>

<div class="container">
  <div class="row">
    <div class="col">
     <h4 style="margin-top: 20px;">Add/Edit Chart</h4>
     
     <form method="post" action="/charts/save<?php if(isset($chart)){ echo '/'.$chart->id; }?>">
     
     	<table cellpadding=10>
				<tr>
					<td>Chart Type:</td>
					<td>
						<select id="chart_type" name="chart_type" onchange="chartType();">
						<option value="none"> -- select chart type -- </option>
							<option value="bar" <?php if(isset($chart->chart_type) && $chart->chart_type == 'bar'){ echo 'selected'; } ?>>Bar</option>
							<option value="stacked bar" <?php if(isset($chart->chart_type) && $chart->chart_type == 'stacked bar'){ echo 'selected'; }?>>Stacked Bar</option>
							<option value="horizontal bar" <?php if(isset($chart->chart_type) && $chart->chart_type == 'horizontal bar'){ echo 'selected'; }?>>Horizontal Bar</option>
							<option value="line" <?php if(isset($chart->chart_type) && $chart->chart_type == 'line'){ echo 'selected'; }?>>Line</option>
							<option value="pie" <?php if(isset($chart->chart_type) && $chart->chart_type == 'pie'){ echo 'selected';} ?>>Pie</option>
							<option value="doughnut" <?php if(isset($chart->chart_type) && $chart->chart_type == 'doughnut'){ echo 'selected';} ?>>Doughnut</option>
							<option value="combination" <?php if(isset($chart->chart_type) && $chart->chart_type == 'combination'){ echo 'selected';} ?>>Combination</option>
						</select>
					</td>
				</tr>
				<tr>
						<td>Chart title</td>
						<td><input type="text" id="chart_title" name="chart_title" style="width: 550px" value="<?php if(isset($chart->chart_title)){ echo $chart->chart_title; } ?>" ></input></td>
			</tr>
			
			</table>
			
			<!--  GROUPED VISUALIZATIONS -->
			<div id="grouped-visualization" <?php if(isset($chart) && $chart->chart_type == 'combination'){ ?> style="display: visible" <?php } else { ?> style="display: none" <?php } ?>  >
			
			<div style="margin-bottom: 2em;">Select multiple visulizations to group together as multiple series in one.  NOTE: The visualizations to group must be structurally identical. 
			That is, they must have the same geography and same data colums with identical column headings</div>
			
			<select id="charts">
				<?php foreach($charts as $c) { ?>
					<option value="<?php echo $c->id; ?>"><?php echo $c->chart_title; ?></option>
				<?php } ?>
			</select>
			
			</div>
			
			<!-- END GROUPED VISUALIZATIONS -->
			
			<!-- ALL STANDARD VISUALIZATIONS -->
			
			<div id="standard-visualization" <?php if(isset($chart) && $chart->chart_type != 'combination'){ ?> style="display: visible" <?php } else { ?> style="display: none" <?php } ?>  >
			
			<table cellpadding=10>
			 
			 <tr>
					<td>Chart Data Source:</td>
					<td>
						<select id="chart_datasource" name="chart_datasource" onchange="chartDataSource();">
						<option value="none"> -- select chart data source-- </option>
							<option value="dataset" <?php if(isset($chart->chart_datasource) && $chart->chart_datasource == 'dataset'){ echo 'selected'; } ?>>Dataset</option>
							<option value="sql" <?php if(isset($chart->chart_datasource) && $chart->chart_datasource == 'sql'){ echo 'selected'; }?>>SQL Query</option>
							<option value="api" <?php if(isset($chart->chart_datasource) && $chart->chart_datasource == 'api'){ echo 'selected'; }?>>API Endpoint</option>
							<option value="census" <?php if(isset($chart->chart_datasource) && $chart->chart_datasource == 'census'){ echo 'selected'; }?>>Census</option>
						</select>
					</td>
				</tr>
				<tr <?php if (isset($chart->chart_datasource) && $chart->chart_datasource == 'dataset'){ 
				    echo 'style="display: visible;"'; } else { echo 'style="display: none;"'; } ?> id="dataset_row">
					<td>Dataset:</td>
					<td>
						<select id="dataset" name="dataset" onchange="dataSet();">
						<option value="none"> -- select dataset -- </option>
						<?php foreach($datasets as $d) { ?>
							<option value="<?php echo $d->id; ?>" <?php if(isset($chart->dataset) && $chart->dataset == $d->id){ echo 'selected'; } ?>><?php echo $d->title; ?></option>
						<?php }?>
						</select>
					</td>
				</tr>
				<tr <?php if (isset($chart->chart_datasource) && $chart->chart_datasource == 'census'){ 
				    echo 'style="display: visible;"'; } else { echo 'style="display: none;"'; } ?> id="census_report_row">
					<td>Census Report:</td>
					<td>
						<select id="census_report" name="census_report">
						<option value="none"> -- select census report-- </option>
						<?php foreach($censusReports as $r) { ?>
							<option value="<?php echo $r->id; ?>" <?php if(isset($chart->dataset) && $chart->dataset == $r->id){ echo 'selected'; } ?>><?php echo $r->name; ?></option>
						<?php }?>
						</select>
					</td>
				</tr>
				<tr <?php if (isset($chart->chart_datasource) && $chart->chart_datasource == 'census'){ 
				    echo 'style="display: visible;"'; } else { echo 'style="display: none;"'; } ?> id="census_report_denominator_row">
					<td valign="top">Census Report Denominator*:</td>
					<td>
						<select id="census_report_denominator" name="census_report_denominator">
						<option value="none"> -- select census denominator report-- </option>
						<?php foreach($censusReports as $r) { ?>
							<option value="<?php echo $r->id; ?>" <?php if(isset($chart->dataset_denominator) && $chart->dataset_denominator == $r->id){ echo 'selected'; } ?>><?php echo $r->name; ?></option>
						<?php }?>
						</select>
						<div>* this will be used for percent calculations when population counts are returned from the first report</div>
					</td>
				</tr>
				<tr <?php if (isset($chart->chart_datasource) && $chart->chart_datasource == 'api'){ 
				    echo 'style="display: visible;"'; } else { echo 'style="display: none;"'; } ?> id="api_row">
					<td>Chart API Endpoint</td>
					<td><input type="text" id="api_endpoint" name="api_endpoint" style="width: 350px" value="<?php if(isset($chart->api_endpoint)){ echo $chart->api_endpoint; } ?>" ></input></td>
				</tr>
				<tr <?php if (isset($chart->chart_datasource) && $chart->chart_datasource == 'sql'){ 
				    echo 'style="display: visible;"'; } else { echo 'style="display: none;"'; } ?> id="sql_row">
					<td valign="top">Chart SQL Query:</td>
					<td >
						<textarea rows="10" cols="100" id="data_query" name="data_query"><?php if(isset($chart->data_query)){ echo $chart->data_query; } ?></textarea>
					</td>
				</tr>
				<tr>
					<td>Auto-generate dataset(s) from SQL:</td>
					<td>
						<select id="map_query" name="map_query" >
						<option value="0" <?php if(isset($chart->map_query) && $chart->map_query == '0'){ echo 'selected'; } ?>>No</option>
						<option value="1" <?php if(isset($chart->map_query) && $chart->map_query == '1'){ echo 'selected'; } ?>>Yes</option>
						</select>
					</td>
				</tr>
				</table>


	
				<table cellpadding="10">
					
					<tr>
						<td>Chart sub title</td>
						<td><input type="text" id="chart_sub_title" name="chart_sub_title" style="width: 350px" value="<?php if(isset($chart->chart_sub_title)){ echo $chart->chart_sub_title; } ?>" ></input></td>
					</tr> 
					<tr>
						<td><div>X-Axis Column</div>
						<div>
							<div><b>Required for all chart types</b></div>
							<div></div>
						</div>
						
						</td>
						<td valign="top"><input type="text" id="labels" name="labels" value="<?php if(isset($chart->labels)) {echo $chart->labels; } ?>"></input></td>
					</tr>
					<tr>
						<td><div>Y-Axis Column</div>
						<div><b>Required for all chart types</b></div>
						</td>
						<td valign="top"><input style="width: 350px"  type="text" id="data_column" name="data_column" value="<?php if(isset($chart->data_column)) {echo $chart->data_column; } ?>"></input></td>
					</tr>
					<tr>
						<td>Y-Axis Data Type</td>
						<td><select type="text" id="y_data_type" name="y_data_type">
								<option value="number" <?php if(isset($chart->y_data_type) && $chart->y_data_type == 'number') {echo 'selected'; } ?>>Number</option>
								<option value="percent"<?php if(isset($chart->y_data_type) && $chart->y_data_type == 'percent') {echo 'selected'; } ?>>Percent</option>
							</select></td>
					</tr>
					<tr>
						<td><div>Data Label Column</div>
						<div><b>Stacked bar</b> : how the single bar will be grouped</div>
						<div><b>Bar</b> : used when label values might be different type than axis values</div>
						</td>
						<td valign="top"><input  style="width: 350px" type="text" id="data_label_column" name="data_label_column"  value="<?php if(isset($chart->data_label_column)){ echo $chart->data_label_column; } ?>"></input></td>
					</tr>
					<tr>
						<td><div>Series Column</div>
						<div><b>** Only use for multipel series - otherwise leave blank</b></div>
						</td>
						<td valign="top"><input type="text" id="label_column" name="label_column" value="<?php if(isset($chart->label_column)){ echo $chart->label_column; } ?>"></input></td>
					</tr>
					<tr>
						<td>Order Column</td>
						<td><input type="text" id="order_column" name="order_column" value="<?php if(isset($chart->order_column)){ echo $chart->order_column; } ?>"></input></td>
					</tr>
					<tr>
						<td>Show Legend</td>
						<td><select id="show_legend" name="show_legend" >
								<option value="0" <?php if(isset($chart->show_legend) && $chart->show_legend == '0') {echo 'selected'; } ?>>No</option>
								<option value="1" <?php if(isset($chart->show_legend) && $chart->show_legend == '1') {echo 'selected'; } ?>>Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Show Data Labels</td>
						<td><select id="show_legend" name="show_legend" >
								<option value="0" <?php if(isset($chart->show_datalabels) && $chart->show_datalabels == '0') {echo 'selected'; } ?>>No</option>
								<option value="1" <?php if(isset($chart->show_datalabels) && $chart->show_datalabels == '1') {echo 'selected'; } ?>>Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Y-axis Min Value</td>
						<td><input type="text" id="y_min" name="y_min" value="<?php if(isset($chart->y_min)){ echo $chart->y_min; } ?>"></input></td>
					</tr>
					<tr>
						<td>Y-axis Max Value</td>
						<td><input type="text" id="y_max" name="y_max" value="<?php if(isset($chart->y_max)){ echo $chart->y_max; } ?>"></input></td>
					</tr>
					
				</table>
				
				

	</div>

    <!--  END STANDARD CHART VISUALIZATION -->
	<table cellpadding=10>	
	<tr>
		<td><input type="submit" value="Save" /></td>
		<td></td>
	</tr>
	</table>
	
	</form>	
     
    </div>
    
  </div>

	<div class="row">


	<div class="col" style="padding-top: 20px; padding-bottom: 20px;">
    
		<?php if(isset($chart) && $chart->map_query == '1' ) { 
			$dc = $chartData;
			//print_r($dc); exit;
				require(getcwd().DIRECTORY_SEPARATOR."application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.
						"admin".DIRECTORY_SEPARATOR."chart".DIRECTORY_SEPARATOR."chart_renderer_auto.php");
		} else if(isset($chart) && isset($chartData) && !empty($chartData)) { 
			$dc = $chartData;
				require(getcwd().DIRECTORY_SEPARATOR."application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.
						"admin".DIRECTORY_SEPARATOR."chart".DIRECTORY_SEPARATOR."chart_renderer.php");
		 } ?>
		    
    </div>

		</div>


</div>


<?php if( isset($chart) && $chart->map_query != '1' && $chart->chart_type != 'combination') { ?>
<div class="container">
  <div class="row">
  	<div class="col-8">
  	
  			<?php if(isset($chart)){ ?>
				<form method="post" action="/charts/add_dataset">
				<?php if(isset($chart)){ ?><input type="hidden" name="chart_id" value="<?php echo $chart->id; ?>" /> <?php } ?>
				<h4 style="margin-top: 20px; margin-bottom: 20px;">Add Chart Datasets / Series</h4>
				<table cellpadding=10>
				<?php if(isset($chartDatasetColumns)) { foreach($chartDatasetColumns as $key=>$val){ ?>
					<tr>
						<td valign="top"><?php echo $key; ?></td>
						<td>
							<select id="<?php echo $key?>" name="<?php echo $key; ?>">
							<?php foreach($val as $v) { ?>
								<option value="<?php echo $v->$key; ?>"><?php echo $v->$key; ?></option>
							<?php } ?>
							</select> <input type="radio" value="<?php echo $key; ?>" name="label"> Use this value 
						</td>
						
					</tr>
				<?php } } ?>
				<tr>	
					<td>Background color</td>
					<td><select id="background_color" name="background_color">
							<?php foreach($colors as $key=>$val) { ?>
								<option value="<?php echo $val; ?>" ><?php echo $key; ?></option>
							<?php } ?>
							</select></input></td>
				</tr>
				<tr>	
					<td>Border color</td>
					<td><select id="border_color" name="border_color">
							<?php foreach($colors as $key=>$val) { ?>
								<option value="<?php echo $val; ?>" ><?php echo $key; ?></option>
							<?php } ?>
							</select></td>
				</tr>
				<tr>	
					<td>Fill</td>
					<td><select id="fill" name="fill">
					<option value="0" selected>No</option>
					<option value="1">Yes</option>
					</select></td>
				</tr>
				<tr>	
					<td>Fill</td>
					<td><select id="fill" name="fill">
					<option value="0" selected>No</option>
					<option value="1">Yes</option>
					</select></td>
				</tr>
				<tr>
						<td><input type="submit" value="Add" /></td>
						<td></td>
					</tr>
			</table>
			</form>
			<?php } ?>
  	
  	</div>
  	<div class="col-4">
  	
  	 <h4 style="margin-top: 20px;">Chart Datasets / Series</h4>
      <ul>
      <?php if(isset($chartDatasets)) { foreach($chartDatasets as $cd) { ?>
			<li><?php echo $cd->label; ?> <a  href="/charts/delete_dataset/<?php echo $chart->id; ?>/<?php echo $cd->id; ?>">
							<i class="cil-trash"></i></a></li>
			<?php } } ?>
			</ul>
  	</div>
  </div>
 </div>
<?php } ?>



<?php if(isset($chart)) { ?>
<script>
dataSet();
</script>

<?php } ?>

<?php $this->load->view('footer'); ?>