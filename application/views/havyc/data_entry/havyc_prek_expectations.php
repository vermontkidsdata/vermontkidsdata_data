<td><select id="year" name="year">
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
		<option value="Meets Expectations">Meets Expectations</option>
		<option value="Exceeds Expectations">Exceeds Expectations</option>
		<option value="Not Meeting Expectations">Not Meeting Expectations</option>
		</select></td>
	<td><select id="subject" name="subject">
		<option value="Literacy">Literacy</option>
		<option value="Math">Math</option>
		<option value="Social-emotional">Social-emotional</option>
		</select></td>
	<td><input type="text" name="percent"></input></td>