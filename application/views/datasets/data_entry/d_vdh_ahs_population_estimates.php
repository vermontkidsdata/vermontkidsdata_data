
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
			<td><input type="text" name="age"></input></td>
			<td><input type="text" name="gender"></input></td>
			<td><input type="text" name="ahs_district"></input></td>
			<td><input type="text" name="population"></input></td>
