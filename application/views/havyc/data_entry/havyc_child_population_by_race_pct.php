
	<td>
	<select id="race" name="race">
		<?php foreach($race as $r) { ?>
			<option value="<?php echo $r->name; ?>"><?php echo $r->name; ?></option>
		<?php } ?>
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
	<td><input type="text" name="pct"></input></td>
