var myBarChart = new Chart(ctx, {
	type: 'bar',
	options: {
		title: {
		    display: true,
		    text: '<?php echo $dc["chart_title"]; ?>',
		    fontSize: 18
		},
		
		<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>
		tooltips: {
		    callbacks: {
		        label: function(tooltipItem, data) {
					console.log('tooltipItem',tooltipItem);
					console.log('data',data);
		            var label = data.datasets[tooltipItem.datasetIndex].label || '';
		            if (label) {
		                label += ': ';
		            }
		            label += Number(tooltipItem.value).toFixed(1)+'%';
		            return label;
		        }
		    }
		},

		plugins: {
			datalabels: {
				backgroundColor: function(context) {
					return context.dataset.backgroundColor;
				},
				formatter: function(value, context) {
					//return context.chart.data.dataLabels[context.dataIndex];
					return Number(value).toFixed(1)+'%';
				},
				borderRadius: 4,
				color: '#ffffff',
				font: {
					weight: 'bold',
					fontSize: 26
				}
			}
		},
		scales: {
			xAxes: [{
				stacked: true			
			}],
			yAxes: [{stacked: true,
						ticks: {
							min: <?php if(isset($dc["y_min"]) && $dc["y_min"] != '0' &&  $dc["y_min"] != ''){ echo $dc["y_min"]; } else { echo '0'; } ?>,
							<?php if(isset($dc["y_max"]) && $dc["y_max"] != '0' &&  $dc["y_max"] != ''){ echo 'max: '.$dc["y_max"].','; } ?>
							callback: function(value, index, values) {
									//console.log('ticks',value);
			                        return (value).toFixed(0) + '%';
							}
						}
					}]
		},		


		<?php } ?>

		<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'number'){ ?>
		tooltips: {
		    callbacks: {
		        label: function(tooltipItem, data) {
					console.log('tooltipItem',tooltipItem);
					console.log('data',data);
		            var label = data.datasets[tooltipItem.datasetIndex].label || '';
		            if (label) {
		                label += ': ';
		            }
		            label += Number(tooltipItem.value);
		            return label;
		        }
		    }
		},

		plugins: {
			datalabels: {
				backgroundColor: function(context) {
					return context.dataset.backgroundColor;
				},
				formatter: function(value, context) {
					//return context.chart.data.dataLabels[context.dataIndex];
					return Number(value);
				},
				borderRadius: 4,
				color: '#ffffff',
				font: {
					weight: 'bold',
					fontSize: 26
				}
			}
		},
		scales: {
			xAxes: [{
				stacked: true			
			}],
			yAxes: [{stacked: true,
						ticks: {
							min: <?php if(isset($dc["y_min"]) && $dc["y_min"] != '0' &&  $dc["y_min"] != ''){ echo $dc["y_min"]; } else { echo '0'; } ?>,
							<?php if(isset($dc["y_max"]) && $dc["y_max"] != '0' &&  $dc["y_max"] != ''){ echo 'max: '.$dc["y_max"].','; } ?>
							callback: function(value, index, values) {
									//console.log('ticks',value);
			                        return (value);
							}
						}
					}]
		},		


		<?php } ?>

		
		legend: {
			display: true,
			labels: {
				fontColor: '#666'
			}
		},
			
	},
	data: {
		labels: <?php echo json_encode($dc["labels"]); ?>,
		datasets: <?php echo json_encode($dc["datasets"]); ?>
				
	}
})