<canvas id="datasetChart_<?php echo $dc['id']; ?>" width="300" height="150" style="background-color: white"></canvas>	
	<script>
		var ctx = document.getElementById('datasetChart_<?php echo $dc['id']; ?>');
		<?php if($dc['chart_type'] == 'line'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'line',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc['chart_title']; ?>',
		            fontSize: 18
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
		    	labels: <?php echo json_encode($dc['labels']); ?>,
		    	datasets: <?php echo json_encode($dc['datasets']); ?>
				
		    }
		})
		<?php } ?>

		<?php if($dc['chart_type'] == 'bar'){ ?>
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
							
						}],
						yAxes: [{
							ticks: {
								min:0
							}
						}]
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
		
		<?php if($dc['chart_type'] == 'horizontal bar'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'horizontalBar',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
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
		
		<?php if($dc['chart_type'] == 'stacked bar'){ ?>
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

		<?php if($dc['chart_type'] == 'pie'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'pie',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
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

		<?php if($dc['chart_type'] == 'doughnut'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'doughnut',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
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
		
	</script>
