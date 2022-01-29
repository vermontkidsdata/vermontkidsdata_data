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
						<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>
							formatter: Math.round
						<?php } else { ?>
							formatter: Math.round
						<?php } ?>	
						
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
			,
			legend: {
				display: <?php if(isset($dc["show_legend"]) && $dc["show_legend"] == '1'){ echo 'true'; } else { echo 'false'; } ?>,
				labels: {
					fontColor: '#414141'
				}
			},
			scales: {
				xAxes: [{
							
				}],
				yAxes: [{
							ticks: {
								min: <?php if(isset($dc["y_min"]) && $dc["y_min"] != '0' &&  $dc["y_min"] != ''){ echo $dc["y_min"]; } else { echo '0'; } ?>,
								<?php if(isset($dc["y_max"]) && $dc["y_max"] != '0' &&  $dc["y_max"] != ''){ echo 'max: '.$dc["y_max"].','; } ?>
								callback: function(value, index, values) {
										//console.log('ticks',value);
										<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>
											return (value).toFixed(0) + '%';
										<?php } else { ?>
											return Number(value);
										<?php } ?>	
										
								}
							}
						}]
				},

				<?php if(count($dc["datasets"]) > 1) { ?>
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							console.log('tooltipItem',tooltipItem);
							console.log('data',data);
							var label = data.datasets[tooltipItem.datasetIndex].label || '';
							if (label) {
								label += ': ';
							}
							<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>
								label += Number(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]).toFixed(1)+'%';
							<?php } else { ?>
								label += Number(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
							<?php } ?>	

							return label;
						}
					}
				},
				<?php } else { ?>
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							//console.log('tooltipItem',tooltipItem);
							//console.log('data',data);
							var label = data.labels[tooltipItem.index] || '';
							if (label) {
								label += ': ';
							}
							label += Number(data.datasets[0].data[tooltipItem.index]);
							return label;
						}
					}
				},

				<?php } ?>
        
			},
		    data: {
		    	labels: <?php echo json_encode($dc['labels']); ?>,
		    	datasets: <?php echo json_encode($dc['datasets']); ?>
				
		    }
		})