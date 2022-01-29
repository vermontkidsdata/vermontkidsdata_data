<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Insert title here</title>
<script src="https://www.bbfdata.com/scripts/Chart.js"></script>
	<link rel="stylesheet" href="https://www.bbfdata.com/stylesheets/Chart.css">
</head>
<body>

<canvas id="datasetChart" width="900" height="500" style="background-color: white"></canvas>	
	<script>
		var ctx = document.getElementById('datasetChart');
		<?php if($chart["chart_type"]== 'line'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'line',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $chart["chart_title"]; ?>',
		            fontSize: 18
		        }
			    },
		    data: {
		    	labels: <?php echo json_encode($chart["labels"]); ?>,
		    	datasets: <?php echo json_encode($chart["datasets"]); ?>
				
		    }
		})
		<?php } ?>
		<?php if($chart["chart_type"]== 'horizontal bar'){ ?>
		var myLineChart = new Chart(ctx, {
		    type: 'horizontalBar',
		    options: {
			    title: {
		            display: true,
		            text: '<?php echo $chart["chart_title"]; ?>',
		            fontSize: 18
		        }
			    },
		    data: {
		    	labels: <?php echo json_encode($chart["labels"]); ?>,
		    	datasets: <?php echo json_encode($chart["datasets"]); ?>
				
		    }
		})
		<?php } ?>
	</script>

</body>
</html>