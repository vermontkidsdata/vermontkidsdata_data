<?php  //print_r($dc); exit; ?>
<canvas id="datasetChart_<?php echo $dc['id']; ?>" width="300" height="150" style="background-color: white"></canvas>	
	<script>
		var ctx = document.getElementById('datasetChart_<?php echo $dc['id']; ?>');

		<?php if($dc['chart_type'] == 'line'){ 
		require(getcwd().DIRECTORY_SEPARATOR."application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.
						"admin".DIRECTORY_SEPARATOR."chart".DIRECTORY_SEPARATOR."line.php");
		 } ?>

		<?php if($dc['chart_type'] == 'doughnut'){ 
		require(getcwd().DIRECTORY_SEPARATOR."application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.
						"admin".DIRECTORY_SEPARATOR."chart".DIRECTORY_SEPARATOR."doughnut.php");
		 } ?>

		<?php if($dc['chart_type'] == 'pie'){ 
		require(getcwd().DIRECTORY_SEPARATOR."application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.
						"admin".DIRECTORY_SEPARATOR."chart".DIRECTORY_SEPARATOR."pie.php");
		 } ?>

		<?php if($dc['chart_type'] == 'bar'){ 
		require(getcwd().DIRECTORY_SEPARATOR."application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.
						"admin".DIRECTORY_SEPARATOR."chart".DIRECTORY_SEPARATOR."bar.php");
		
		} ?>
		
		<?php if(trim($dc['chart_type']) == 'stacked bar'){ 
		require(getcwd().DIRECTORY_SEPARATOR."application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.
						"admin".DIRECTORY_SEPARATOR."chart".DIRECTORY_SEPARATOR."stackedbar.php");
		
		} ?>
		
	</script>
