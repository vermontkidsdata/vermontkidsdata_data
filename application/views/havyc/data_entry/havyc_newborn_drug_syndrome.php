
	<td>
	<select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select>
	</td>
		<td>
	<select id="category" name="category">
			<option value="Infants born exposed to opioids">Infants born exposed to opioids</option>
		</select>
	</td>
	</td>
	<td><input type="text" name="count"></input></td>
