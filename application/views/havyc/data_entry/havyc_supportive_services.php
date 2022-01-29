
	<td><select id="fiscal_year" name="fiscal_year">
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
	<select id="measure" name="measure">
		<option value="Conditional Custody">Conditional Custody</option>
		<option value="Family Support">Family Support</option>
		<option value="DCF Custody">DCF Custody<option>
		</select>
	</td>
	<td><input type="text" name="count"></input></td>
