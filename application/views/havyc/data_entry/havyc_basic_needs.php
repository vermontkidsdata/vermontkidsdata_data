
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
			<td>
	<select id="category" name="category">
			<option value="Federal poverty level (family of 4)">Federal poverty level (family of 4)</option>
			<option value="Full-time at minimum wage (2 wage earners)">Full-time at minimum wage (2 wage earners)</option>
			<option value="2018 Basic Needs Budget,rural">2018 Basic Needs Budget,rural</option>
			<option value="Median family income (4 person adj. to 2018 dollars)" >Median family income (4 person adj. to 2018 dollars)</option>
		</select>
	</td>
	<td><input type="text" name="amount"></input></td>
