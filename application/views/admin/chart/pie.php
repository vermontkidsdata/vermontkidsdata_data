var myLineChart = new Chart(ctx, {
		    type: 'pie',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
		        }

			<?php if(isset($dc["y_data_type"]) && $dc["y_data_type"] != '' && $dc["y_data_type"] == 'percent'){ ?>,
		    	tooltips: {
		            callbacks: {
		                label: function(tooltipItem, data) {
			                //console.log(tooltipItem.index);
							//console.log(data);
		                    var label = data.labels[tooltipItem.index] || '';
		                    //console.log(tooltipItem);
		                    //console.log(data.datasets[0].data[tooltipItem.index]);
		                    if (label) {
		                        label += ': ';
		                    }
		                    label += data.datasets[0].data[tooltipItem.index].toFixed(1)+'%';
		                    return label;
		                }
		            }
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
        
			    },			    
		    data: {
		    	labels: <?php echo json_encode($dc["labels"]); ?>,
		    	datasets: <?php echo json_encode($dc["datasets"]); ?>
				
		    }
		})