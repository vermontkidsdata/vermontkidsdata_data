let myChartExtend = Chart.controllers.doughnut.prototype.draw;
function drawIndicator(ctx){
console.log('drawing chart',myChart);
	var screenSize =  getBootstrapDeviceSize();
	//set the font size based on the screen it's being viewed on
	if(screenSize == 'xs'){
		fontSize = '36x'
	} else if(screenSize == 'sm'){
		fontSize = '36px'
	} else if(screenSize == 'md'){
		fontSize = '55px'
	} else if(screenSize == 'lg'){
		fontSize = '65px'
	} else {
		fontSize = '100px'
	}
	console.log('screen size', screenSize);
	<?php
		$dataVal = $dc['datasets'][0]['data'][0];
		if($dc['y_data_type'] == 'percent'){
			$dataVal .= '%';
		}

		
	?>
	var centerY = (myChart.chartArea.bottom / 2) + (myChart.chartArea.top / 2);
	//console.log('center y',centerY);
	var centerX = ((myChart.chartArea.right - myChart.chartArea.left) / 2);
	//console.log('center x',centerX);
	let context = ctx.getContext('2d');
	Chart.helpers.extend(Chart.controllers.doughnut.prototype, {
	  draw: function() {
		myChartExtend.apply(this, arguments);
		this.chart.chart.ctx.textAlign = "center"
		this.chart.chart.ctx.font = fontSize+" Arial black";
		this.chart.chart.ctx.fillText("<?php echo $dataVal; ?>", centerX, centerY)
	  }
	});	
}	

var conf = {
		    type: 'doughnut',
			cutoutPercentage: 75,
		    options: {
				cutoutPercentage: 75,
			    title: {
		            display: true,
		            text: '<?php echo $dc["chart_title"]; ?>',
		            fontSize: 18
		        },
			tooltips: {
				enabled: false
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
		}

	

	var myChart = new Chart(ctx, conf );

	drawIndicator(ctx);

	
	