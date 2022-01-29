
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
		<td><select id="school_year" name="school_year">
		<?php foreach($schoolyears as $a) { ?>
			<option value="<?php echo $a->name; ?>"><?php echo $a->name; ?></option>
		<?php } ?>
		</select></td>
		<td><select id="measure" name="measure">
		<option value="Vermont">Vermont</option>
		<option value="Boys">Boys</option>
		<option value="Girls">Girls</option>
		<option value="Free & Reduced Lunch Eligible">Free & Reduced Lunch Eligible</option>
		<option value="Not Free & Reduced Lunch Eligible">Not Free & Reduced Lunch Eligible</option>
		<option value="Attended Publicly Funded Pre-K">Attended Publicly Funded Pre-K</option>
		<option value="Did Not Attend Publicly Funded Pre-K">Did Not Attend Publicly Funded Pre-K</option>
		<option value="Percent of Students Surveyed">Percent of Students Surveyed</option>
		</select></td>
	<td><input type="text" name="percent"></input></td>
