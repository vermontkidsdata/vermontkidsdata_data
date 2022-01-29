
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
		<td><select id="series_group" name="series_group">
		<option value="Pre-K Enrollment">Pre-K Enrollment</option>
		</select></td>
	<td><input type="text" name="count"></input></td>
