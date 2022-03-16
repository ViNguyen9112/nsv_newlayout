<div class="box-ot">
	<div class="bg-white">
		<h2 class="text-center mb-3"><?php echo $data_language['_overtime_title'][$lang]; ?></h2>
		<hr class="mb-0">
		<div class="card-body">
			<div class="form-group">
				<label class="line-h" for="inputPasswordOld"><?php echo $data_language['_overtime_date'][$lang]; ?></label> 
				<select id="year" name="year" class="form-control1">
					<?php for($y = 2022; $y <= date('Y'); $y++) : ?>
					<option <?php echo ($y == date('Y')) ? 'selected="selected"' : ''; ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
					<?php endfor; ?>
				</select>
				<select id="month" name="month" class="ml-2 form-control1">
					<?php for($y = 1; $y <= 12; $y++) : ?>
					<option <?php echo ($y == date('m')) ? 'selected="selected"' : ''; ?> value="<?php echo $y; ?>"><?php echo ($y < 10) ? '0'.$y : $y; ?></option>
					<?php endfor; ?>
				</select>
			</div>
			<hr class="mt-0">
			<div id="result-ot">

			</div>
		</div>
	</div>
</div>