
	<td><select id="fiscal_year" name="fiscal_year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
		<td>
	<select id="age_group" name="age_group">
		<?php foreach($ageGroups as $a) { ?>
			<option value="<?php echo $a->name; ?>"><?php echo $a->name; ?></option>
		<?php } ?>
		</select>
	</td>

	<td>
	<select id="measure" name="measure">
		<option value="Have Voucher and Searching for Housing">Have Voucher and Searching for Housing</option>
		<option value="Stably Housed">Stably Housed</option>
		<option value="No Voucher (Homeless orAt-Risk)">No Voucher (Homeless orAt-Risk)<option>
		</select>
	</td>
	<td><input type="text" name="count"></input></td>
