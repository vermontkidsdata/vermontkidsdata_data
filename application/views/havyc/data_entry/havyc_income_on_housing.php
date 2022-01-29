
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
			<td>
	<select id="houseing_type" name="housing_type">
			<option value="Rent">Rent</option>
			<option value="Mortgage">Mortgage</option>
		</select>
	</td>
	<td><input type="text" name="percent"></input></td>
