
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
		<td>
			<select id="household_type" name="household_type">
			<?php foreach($householdtypes as $h) { ?>
			<option value="<?php echo $h->name; ?>"><?php echo $h->name; ?></option>
		<?php } ?>
		</select>
		</td>
		<td>
			<select id="funding" name="funding">
			<option value="Base Rate">Base Rate</option>
			<option value="Quality Differential">Quality Differential</option>
			<option value="Parent Co-payment">Parent Co-payment</option>
			<option value="Provider Rate">Provider Rate</option>
		</select>
		</td>
	<td><input type="text" name="count"></input></td>
