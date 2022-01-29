
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
	<select id="care_type" name="care_type">
			<option value="Any Care">Any Care</option>
			<option value="Center Based">Center Based</option>
			<option value="Paid Home Base">Paid Home Base</option>
			<option value="Unpaid Home Base">Unpaid Home Base</option>
		</select>
	</td>
	<td><input type="text" name="percent"></input></td>

