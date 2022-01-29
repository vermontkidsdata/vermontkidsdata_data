<?php //print_r($dc["datasets"]); ?>
var myBarChart = new Chart(ctx, {
	type: 'bar',
	options: {
		title: {
		    display: true,
		    text: '<?php echo $dc["chart_title"]; ?>',
		    fontSize: 18
		},

	<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'number'){ ?>

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
		            label += Number(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
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

		scales: {
			xAxes: [{
							
			}],
			yAxes: [{
						ticks: {
							min:0,
							callback: function(value, index, values) {
									//console.log('ticks',value);
			                        return (value);
							}
						}
					}]
		},
		
		<?php } ?>
		
		<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>

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
		            label += Number(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]).toFixed(1)+'%';
		            return label;
		        }
		    }
		},
		<?php } else { ?>
		tooltips: {
		    callbacks: {
		        label: function(tooltipItem, data) {
					console.log('tooltipItem',tooltipItem);
					console.log('data',data);
		            var label = data.labels[tooltipItem.index] || '';
		            if (label) {
		                label += ': ';
		            }
		            label += Number(data.datasets[0].data[tooltipItem.index]).toFixed(1)+'%';
		            return label;
		        }
		    }
		},
		<?php } ?>

		scales: {
			xAxes: [{
							
			}],
			yAxes: [{
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

<?php if(isset($dc["show_legend"]) && $dc["show_legend"] == '1'){ ?>
		legend: {
			display: true,
			labels: {
				fontColor: '#414141'
			}
		},

<?php } else {  ?>
		legend: {
			display: false,
			labels: {
				fontColor: 'rgb(255, 99, 132)'
			}
		},
<?php } ?>
		plugins: {
			datalabels: {
				backgroundColor: function(context) {
					return context.dataset.backgroundColor;
				},
				formatter: function(value, context) {
					//return context.chart.data.dataLabels[context.dataIndex];
					<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>
					 value += '%';
					<?php } ?>
					return value;
				},
				borderRadius: 4,
				color: '#ffffff',
				font: {
					weight: 'bold',
					fontSize: 26
				}
			}
		}			
	},
	data: {
		labels: <?php echo json_encode($dc["labels"]); ?>,
		datasets: <?php echo json_encode($dc["datasets"]); ?>
				
	}
})