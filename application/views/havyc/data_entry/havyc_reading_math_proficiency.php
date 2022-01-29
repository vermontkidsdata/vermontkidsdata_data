	<td><select id="year" name="year">
		<?php foreach($years as $a) { ?>
			<option value="<?php echo $a->year; ?>"><?php echo $a->year; ?></option>
		<?php } ?>
		</select></td>
		<td><select id="school_year" name="school_year">
		<?php foreach($schoolyears as $a) { ?>
			<option value="<?php echo $a->name; ?>"><?php echo $a->name; ?></option>
		<?php } ?>
		</select></td>
		<td><select id="subject" name="subject">
		<option value="Reading">Reading</option>
		<option value="Math">Math</option>
		</select></td>
		<td><select id="measure" name="measure">
		<option value="All Students">All Students</option>
		<option value="Free & Reduced Lunch Eligible">Free & Reduced Lunch Eligible</option>
		<option value="Special Education">Special Education</option>
		<option value="Historically Marginalized">Historically Marginalized</option>

		</select></td>
	<td><input type="text" name="percent"></input></td>
