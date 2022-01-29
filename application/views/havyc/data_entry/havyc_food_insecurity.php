
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
	</td>
			<td>
	<select id="geography" name="geography">
			<option value="United States">United States</option>
			<option value="Vermont">Vermont</option>
		</select>
	</td>
	<td><input type="text" name="percent"></input></td>
