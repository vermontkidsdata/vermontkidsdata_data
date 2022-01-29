
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
			<td>
	<select id="number" name="number">
			<option value="None">None</option>
			<option value="1 or 2">1 or 2</option>
			<option value="3 or More">3 or More</option>
		</select>
	</td>
	<td><input type="text" name="percent"></input></td>
	