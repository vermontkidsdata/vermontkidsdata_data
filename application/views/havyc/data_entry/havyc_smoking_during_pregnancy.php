
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
		<td>
	<select id="geography" name="geography">
		<?php foreach($geographies as $g) { if($g->geography_type == '3') { ?>
			<option value="<?php echo $g->name; ?>"><?php echo $g->name; ?></option>
		<?php } } ?>
		</select>
	</td>
	<td><input type="text" name="percent"></input></td>
