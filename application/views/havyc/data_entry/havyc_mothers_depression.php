
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
	<td><select id="measure" name="measure">
		<option value="Depression">Depression</option>
		<option value="Health care visit for depression or anxiety">Health care visit for depression or anxiety</option>
		</select></td>
	<td><input type="text" name="percent"></input></td>
