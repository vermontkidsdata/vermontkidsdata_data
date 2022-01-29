<a href="<?php echo base_url();?>userlogin/logout">logout</a>
<h3>Report Groups</h3>
<div>Report groups define the structure for one row of data for an ACS report, including all variables and dimensions necessary 
to carry out calculations required for the report, e.g. a numerator and denominator value for percentage. Therefore, a Report Group 
might contain multiple rows from the <i>acs_report_group</i> table. For example, a report group for one row of data for the B17001 
poverty report would look as follows (rows in the database table):</div>
<div style="margin-top: 2em; margin-bottom: 2em;">
<table width="100%" border=1>
	<tr>
		<th>Report ID</th>
		<th>Group ID</th>
		<th>Table Variables</th>
		<th>Report Group Name</th>
		<th>Is Numerator</th>
		<th>Is Denominator</th>
		<th>Is Value</th>
		<th>Is Total</th>
	</tr>
	<tr>
		<td>1</td>
		<td>1</td>
		<td>B17001_005E,B17001_004E</td>
		<td>Estimate!!Total!!Income in the past 12 months below poverty level!!Male!!Under 6</td>
		<td>1</td>
		<td>0</td>
		<td>1</td>
		<td>0</td>
	</tr>
	<tr>
		<td>1</td>
		<td>1</td>
		<td>B17001_034E,B17001_033E</td>
		<td>Estimate!!Total!!Income in the past 12 months at or above poverty level!!Male!!Under 6</td>
		<td>0</td>
		<td>0</td>
		<td>0</td>
		<td>0</td>
	</tr>
	<tr>
		<td>1</td>
		<td>1</td>
		<td>B17001_005E,B17001_004E,B17001_034E,B17001_033E</td>
		<td>Estimate!!Total!!Income in the past 12 months all!!Male!!Under 6</td>
		<td>0</td>
		<td>1</td>
		<td>0</td>
		<td>1</td>
	</tr>

</table>
</div>

<table width="100%">
	<tr>
		<td width="50%" valign="top">
		<h3>Add A New Report Group</h3>
		<p>
		You can create a report group in one of two ways:
		<ul>
			<li>From scratch (advanced users only)</li>
			<li>Copying the strucuture of an existing group for a simliar report, and then editing the variables and titles accordingly</li>
		</ul>
		<div style="margin-bottom: 1em;">
		<select id="report" name="report">
			<option value="0">-- select a report --</option>
			<?php foreach($reports as $r){ ?>
				<option value="<?php echo $r->id_acs_report; ?>"><?php echo $r->report_name; ?></option>
			<?php } ?>
		</select>
		</div>
		
		<div style="margin-bottom: 1em;">
		<select id="report" name="report">
			<option value="0">-- select a report to copy group from --</option>
			<?php foreach($reports as $r){ ?>
				<option value="<?php echo $r->id_acs_report; ?>"><?php echo $r->report_name; ?></option>
			<?php } ?>
		</select>
		</div>
		
		<div style="margin-bottom: 1em;">
		<input type="submit" value="Copy Group"></input>
		</div>
		
		</p>
		</td>
		<td width="50%" valign="top">
		<h3>Current Reports With Report Groups</h3>
		<p>
		<table>
		<?php foreach($reports as $r){ ?>
			<tr>
				<td><?php echo $r->report_name; ?></td>
				<td><a href="<?php echo base_url();?>reportgroup/edit/<?php echo $r->id_acs_report; ?>">edit</a></td>
			</tr>	
		<?php } ?>
		</table>
		</p>
		</td>
	</tr>
</table>


