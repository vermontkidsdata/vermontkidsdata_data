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
	<td><select id="measure" name="measure">
			<option value="Breastfeeding initiation">Breastfeeding initiation</option>
		<option value="Sustained breastfeeding at 6 months">Sustained breastfeeding at 6 months</option>
		<option value="Exclusive Breastfeeding at 6 months">Exclusive Breastfeeding at 6 months</option>
		</select></td>
	<td><input type="text" name="percent"></input></td>