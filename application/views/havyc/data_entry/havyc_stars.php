
	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
			<td>
	<select id="stars" name="stars">
			<option value="Not Participating">Not Participating</option>
			<option value="1, 2, 3 STAR">1, 2, 3 STAR</option>
			<option value="High Quality (4, 5 STAR)">High Quality (4, 5 STAR)</option>
		</select>
	</td>
	<td><input type="text" name="count"></input></td>
