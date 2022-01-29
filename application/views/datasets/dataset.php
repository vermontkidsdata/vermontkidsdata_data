<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<!-- 	window.chartColors = {
			red: 'rgb(255, 99, 132)',
			orange: 'rgb(255, 159, 64)',
			yellow: 'rgb(255, 205, 86)',
			green: 'rgb(75, 192, 192)',
			blue: 'rgb(54, 162, 235)',
			purple: 'rgb(153, 102, 255)',
			grey: 'rgb(201, 203, 207)'
		}; -->


<?php //print_r($columns); ?>

<div class="container-fluid" >

<div class="row" style="padding: 20px;">
<h4>How are Vermont's Young Children Dataset: <?php echo $dataset->title; ?></h4>
</div>

<?php foreach($datasetCharts as $dc) { ?>
<?php //print_r($dc); ?>
<div class="row" style="padding: 50px;">
<canvas id="datasetChart_<?php echo $dc["id"]; ?>" width="600" height="200" style="background-color: white"></canvas>	
	<script>
		var ctx = document.getElementById('datasetChart_<?php echo $dc["id"]; ?>');
		<?php if($dc["chart_type"]== 'line'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'line',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
		        }
        	<?php if(isset($dc["show_lines"]) && $dc["show_lines"] == 0 ) {  ?>, showLines: false <?php } ?>

        	,
		    scales: {
	        "xAxes": [{
	                "display": true
	            }
	        ],
	        "yAxes": [{
	                "display": true,
	                "ticks": {
	                    "min": 0
	                }
	            }
	        ]
	    	}
        	<?php if(isset($dc["show_lines"]) && $dc["show_lines"] == 0 ) {  ?>, showLines: false <?php } ?>
					       	
        	<?php if(isset($dc["show_annotation"]) && $dc["show_annotation"] == 1 ) {  ?>,
			    annotation: {
					annotations: [{
						//drawTime: 'afterDraw', // overrides annotation.drawTime if set
						//id: 'a-line-1', // optional
						type: 'line',
						mode: 'horizontal',
						scaleID: 'y-axis-0',
						value: 61,
						borderColor: 'red',
						borderWidth: 2,
						label: {
				              backgroundColor: "black",
				              content: "Vermont: 61%",
				              enabled: true,
				              position: "left"
				            },
		
										// Fires when the user clicks this annotation on the chart
										// (be sure to enable the event in the events array below).
										onClick: function(e) {
											// `this` is bound to the annotation element
										}
									}]
							}
        	<?php } ?>
        	<?php if(isset($dc["show_datalabels"]) && $dc["show_datalabels"] == 1 ) {  ?>,
								plugins: {
									datalabels: {
										backgroundColor: function(context) {
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
        	<?php } else {  ?>
        	,
        	plugins: {
						datalabels: {
						display: false
						}
					}
        	<?php } ?>
						
			    }
				
	    	,
		    data: {
		    	labels: <?php echo json_encode($dc["labels"]); ?>,
		    	datasets: <?php echo json_encode($dc["datasets"]); ?>				
		    }
		    
		    
		})
		<?php } ?>

		<?php if($dc["chart_type"]== 'bar'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'bar',
		    options: {
		    	<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>
		    	tooltips: {
		            callbacks: {
		                label: function(tooltipItem, data) {
			                
		                    var label = data.datasets[tooltipItem.datasetIndex].label || '';
		                    //console.log(tooltipItem);
		                    //console.log(data.datasets[tooltipItem.datasetIndex]);
		                    if (label) {
		                        label += ': ';
		                    }
		                    label += (tooltipItem.yLabel*100).toFixed(1)+'%';
		                    return label;
		                }
		            }
		    	},
		    	<?php } ?>
			    legend: { position: 'bottom' },
			    title: {
		            display: true,
		            text: [ '<?php echo $dc["chart_title"]; ?>'
		            <?php if(isset($dc["chart_sub_title"])) { echo ",'".$dc["chart_sub_title"]."'"; } ?> ],
		            fontSize: 18
		        }, 
			      scales: {
						xAxes: [{
							
						}],
						yAxes: [{
							ticks: {
								<?php if(isset($dc["y_min"]) && $dc["y_min"] != '') { ?>
									min: <?php echo $dc["y_min"]; ?>
									<?php } else { ?>
									min:0
								<?php } ?>

								<?php if(isset($dc["y_max"]) && $dc["y_max"] != '') { ?>
									,max: <?php echo $dc["y_max"]; ?>
								<?php } ?>
																
								<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>
								,
								callback: function(value, index, values) {
			                        return (value*100).toFixed(0) + '%';
			          }
				        <?php } ?> 
				        <?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'currency'){ ?>
								,
								callback: function(value, index, values) {
			                        return '$' + value;
			          }
				        <?php } ?>  
				        
							}
						}],
						
						}

		        <?php if(isset($dc["show_datalabels"]) && $dc["show_datalabels"] == 1 ) {  ?>,
						plugins: {
							datalabels: {
								backgroundColor: function(context) {
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
					<?php } else {  ?>
					,
					plugins: {
								datalabels: {
								display: false
								}
							}
					<?php } ?>
					
				
			    },
		    data: {
		    	labels: <?php echo json_encode($dc["labels"]); ?>,
		    	datasets: <?php echo json_encode($dc["datasets"]); ?>
				
		    }
		})
		<?php } ?>
		
		<?php if($dc["chart_type"]== 'horizontal bar'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'horizontalBar',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
		        }
			    },
		    data: {
		    	labels: <?php echo json_encode($dc["labels"]); ?>,
		    	datasets: <?php echo json_encode($dc["datasets"]); ?>
				
		    }
		})
		<?php } ?>
		
		<?php if($dc["chart_type"]== 'stacked bar'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'bar',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
		        },
			        scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
			    },
			    
		    data: {
		    	labels: <?php echo json_encode($dc["labels"]); ?>,
		    	datasets: <?php echo json_encode($dc["datasets"]); ?>
				
		    }
		})
		<?php } ?>

		<?php if($dc["chart_type"]== 'pie'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'pie',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
		        }
			    },			    
		    data: {
		    	labels: <?php echo json_encode($dc["labels"]); ?>,
		    	datasets: <?php echo json_encode($dc["datasets"]); ?>
				
		    }
		})
		<?php } ?>

		<?php if($dc["chart_type"]== 'doughnut'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'doughnut',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
		        }
			    },			    
		    data: {
		    	labels: <?php echo json_encode($dc["labels"]); ?>,
		    	datasets: <?php echo json_encode($dc["datasets"]); ?>
				
		    }
		})
		<?php } ?>
		
	</script>
</div>
<?php } ?>
<?php //print_r($datasetdata); ?>

<?php foreach($datasetTables as $dt) { ?>

<?php //print_r($dt); ?>
<div class="row" style="padding: 20px;">
<div class="container-fluid" style="background-color:#ffffff; padding: 20px;">
<table id="table" style="width: 100%">
	<thead>
   	<tr style="background-color: <?php echo $dt['headerBgColor']; ?>" >
   	<?php foreach($dt['columns'] as $c){ ?>
   	<th><?php echo $c; ?></th>
   	<?php } ?>
   	</tr>
   	</thead>
   	<tbody>
   	<?php foreach($dt['dataset'] as  $d){ ?>
   	<tr>   	
   		<?php foreach($d as $key => $val){?>
   			<td><?php echo $val; ?></td>
   		<?php } ?>   		  	
   	</tr>
   	<?php } ?>
   </tbody>
</table>
</div>
</div>
<script>
$(document).ready(function() {
    $('#table').DataTable({
		  dom: 'B<"clear">lfrtip',
		  buttons: [ 'copy', 'csv', 'excel' ]
	  });
} );
</script>

<?php } ?>

<?php //print_r( $datasetdata ); ?>


<?php $this->load->view('footer'); ?>