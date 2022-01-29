
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
			<td>
	<select id="household_type" name="household_type">
			<option value="All Families">All Families</option>
			<option value="Single Parent, Female Head of Household">Single Parent, Female Head of Household</option>
		</select>
	</td>
	<td><input type="text" name="percent"></input></td>
