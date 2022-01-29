<td><select id="school_year" name="school_year">
		<?php foreach($schoolyears as $a) { ?>
			<option value="<?php echo $a->name; ?>"><?php echo $a->name; ?></option>
		<?php } ?>
		</select></td>
		<td>
	<select id="age_group" name="age_group">
		<?php foreach($ageGroups as $a) { ?>
			<option value="<?php echo $a->name; ?>"><?php echo $a->name; ?></option>
		<?php } ?>
		</select>
	</td>
	<td><select id="measure" name="measure">
		<option value="Total kids">Total kids</option>
		<option value="In high quality care (3-5 stars)">In high quality care (3-5 stars)</option>
		<option value="In all other care (0-2 stars)">In all other care (0-2 stars)</option>
		</select></td>
	<td><input type="text" name="count"></input></td>