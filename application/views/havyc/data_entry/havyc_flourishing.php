
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
			<td>
	<select id="measure" name="measure">
			<option value="Meets 0 to 2 Items">Meets 0 to 2 Items</option>
			<option value="Meets 3 Items">Meets 3 Items</option>
			<option value="Meets all 4 Items">Meets all 4 Items</option>
		</select>
	</td>
	<td><input type="text" name="percent"></input></td>
