<div class="box-ot">
	<div class="bg-white">
		<h2 class="text-center mb-3">Annual Leave</h2>
		<hr class="mb-0">
		<div class="card-body">
			<form autocomplete="off" class="form" role="form">
				<div class="form-group">
					<label class="line-h" for="inputPasswordOld">Name</label> 
					<span class="span">A0001 Pham  Vu Hoang Diep</span>
				</div>
				<div class="form-group display-f">
					<label class="line-h" for="inputPasswordOld">Type</label> 
					<div class="form-check-label display-f">
						<div style="width:60px">
							<input class="form-check-input" type="radio" checked="checked" value="1"> 
							<span>AL</span>
						</div>
						<div class="ml-4" style="width:60px">
							<input class="form-check-input" type="radio"> 
							<span>UL</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="line-h" for="inputPasswordOld">From Date</label> 
					<span class="span">2022/03/02</span>
				</div>
				<div class="form-group">
					<label class="line-h" for="inputPasswordOld">To Date</label> 
					<span class="span">2022/03/05</span> 
				</div>
				<div class="form-group">
					<label class="line-h" for="inputPasswordOld">Total</label> 
					<span class="span">2 days</span>
				</div>
				<div class="form-group">
					<label class="line-h" for="inputPasswordOld">Reason</label> 
					<span class="span">Abc abc abc</span>                  
				</div>
				<hr>
				<div class="text-center ">
					<button class="btn-ot" id="CheckIn">Submit</button><button class="btn-ot" id="CheckIn" onclick="location.href='<?php echo $this->Url->build('/annual_leave', true); ?>'" type="button">Back</button>
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>