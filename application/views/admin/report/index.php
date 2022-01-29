<?php $this->load->view('admin/header'); ?>
<a href="<?php echo base_url();?>userlogin/logout">logout</a>
<h3>ACS Reports</h3>
<div style="margin-bottom: 1em;">The following ACS Reports are currently configured in Vermont Insights</div>
<div><a href="<?php echo base_url();?>report/edit">add new report</a></div>
<div style="margin-top: 2em; margin-bottom: 2em;">
<table width="100%" border=1>
	<tr>
		<th>Report Name</th>
		<th>Report Table</th>
		<th></th>
		<th></th>
	</tr>
	<?php foreach($reports as $r){ ?>
	<tr>
		<td><?php echo $r->report_name; ?></td>
		<td><?php echo $r->report_table; ?></td>
		<td>[ <a href="<?php echo base_url();?>report/edit/<?php echo $r->id_acs_report; ?>">edit</a> ]</td>
		<td>[ <a href="<?php echo base_url();?>reportgroup/edit/<?php echo $r->id_acs_report; ?>">report groups</a> ]</td>
	</tr>
	<?php } ?>
</table>
</div>

<?php $this->load->view('admin/footer'); ?>