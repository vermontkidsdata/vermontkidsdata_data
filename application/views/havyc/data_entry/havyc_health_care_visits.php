
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
		<td>
	<select id="age_group" name="age_group">
		<?php foreach($ageGroups as $a) { ?>
			<option value="<?php echo $a->name; ?>"><?php echo $a->name; ?></option>
		<?php } ?>
		</select>
	</td><td>
	<select id="visit_type" name="visit_type">
			<option value="Have seen a health care provider">Have seen a health care provider</option>
			<option value="Have not seen a health care provider">Have not seen a health care provider</option>
		</select>
	</td>
	<td><input type="text" name="percent"></input></td>
