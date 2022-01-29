<?php $this->load->view('header'); ?>

<?php $this->load->view('sub-header'); ?>

<div class="container">
<h4 style="margin-top: 20px;">Saved Census/ACS Report: <?php echo $report->name; ?></h4>
    <div class="row">	
		<div class="col">
		
				<div id="results" style="margin-top: 20px;">
					<table id="results-table1" cellpadding=5 ></table>
				</div>
			</div>
	</div>
</div>

<script>
	console.log('table data to draw',<?php echo json_encode($tableData); ?>);
	dataToDraw = drawDataTable(<?php echo json_encode($tableData); ?>, 'results', 'results-table1');
						dataToDraw.then(
							msg => showTable()
						);
	
</script>


<?php $this->load->view('footer'); ?>