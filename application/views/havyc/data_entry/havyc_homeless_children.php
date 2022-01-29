
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
	
	<td><input type="text" name="count"></input></td>

