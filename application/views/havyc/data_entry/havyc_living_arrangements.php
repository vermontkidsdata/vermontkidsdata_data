
	<td>
	<select id="household_type" name="household_type">
			<option value="Two-parent Households">Two-parent Households</option>
			<option value="Single-parent Households">Single-parent Households</option>
			<option value="Relatives">Relatives</option>
			<option value="Foster family or non-relatives">Foster family or non-relatives</option>
			<option value="Group quarters">Group quarters</option>
		</select>
	</td>
	<td>
	<select id="age_group" name="age_group">
		<?php foreach($ageGroups as $a) { ?>
			<option value="<?php echo $a->name; ?>"><?php echo $a->name; ?></option>
		<?php } ?>
		</select>
	</td>
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
	<td><input type="text" name="percent"></input></td>
