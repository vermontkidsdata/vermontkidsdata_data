
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
	<td><select id="measure" name="measure">
			<option value="Smoked tobacco during the last trimester">Smoked tobacco during the last trimester</option>
		<option value="Drank alcohol during the last trimester">Drank alcohol during the last trimester</option>
		<option value="Used another substance during the last trimester">Used another substance during the last trimester</option>
		<option value="Received MAT during pregnancy">Received MAT during pregnancy</option>
		</select></td>
	<td><input type="text" name="percent"></input></td>
