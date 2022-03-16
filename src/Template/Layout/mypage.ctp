<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">

<head>
    <meta name="google" content="notranslate" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?=SYSTEM_TITLE?></title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo $this->Url->build('/', true); ?>img/favicon.ico">
    <script src="//polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script async defer src="//maps.googleapis.com/maps/api/js?key=<?=MAP_KEY?>&libraries=&v=weekly"></script>
    <!-- CSS -->
    <?php
	echo $this->Html->css('new.css') . PHP_EOL;
    echo $this->Html->css('bootstrap.min.css') . PHP_EOL;
    echo $this->Html->css('icons.css?' . date('YmdHis')) . PHP_EOL;
    echo $this->Html->css('style-user.css?' . date('YmdHis')) . PHP_EOL;
    echo $this->Html->css('report.css?v=7' . date('YmdHis')) . PHP_EOL;
    echo $this->Html->css('venobox.css') . PHP_EOL;
    // JS
    echo $this->Html->script('jquery.min.js') . PHP_EOL;
    ?>
    
	<style>
	body.font-VN {
		font-family: Arial, Helvetica, sans-serif;
	}
	.datepicker {font-size: 13px !important;font-family: "Noto Sans JP", sans-serif !important;color: #545454;padding-left: 13px !important;}

	.datepicker_form {
		position: relative;
	}

	.datepicker_form .fa {
		position: absolute;
		top: 11px;
		right: 23px;
	}
	button[disabled] {
		background: #CCC;
		border: #CCC;
	}
	.newsBox {
		text-align: left;
	}

	.newsBox span {
		font-size: 12px;
	}

	.newsBox.unread {
		background: #dad8d8 !important;
	}
	#ot_list {
		max-height: 60vh;
		overflow-y: scroll;
	}
	ul.dropdown li a {
		font-size: 12px;
		text-transform: uppercase;
	}
	.newsBox p {
    	font-size: 12px !important;
	}
	.status_request {
		display: inline-block;
	}
	input[type=text][disabled] {
		background: #f1f1f1;
	}
	.datepicker[disabled] {
		background: #f1f1f1;
	}
	.sec-bottom {
		min-height: 40vh;
		height: auto;
		padding: 10px 0;
	}
	</style>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Comfortaa:300:400:500:600:700|Prompt:300:400:500:600:700|Poppins:400,500&amp;display=swap" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" />
    <script type="text/javascript">
        var __baseUrl = "<?php echo $this->Url->build('/', true); ?>";
        var csrfToken = '<?php echo $this->request->getParam('_csrfToken') ?>';
    </script>
    <style>
    .img-flag{
        width: 30px !important;
        height: 15px;
        float: right;
        margin-left: 10px;
    }
    .flag-en {
        height: 14px !important;
    }
    </style>

    <?php echo $this->fetch('head-end'); ?>
	<?php if(isset($lang) && isset($data_language)) : ?>
	<script>
	var language_choice = '<?php echo $lang; ?>';
	var language = <?php echo json_encode($data_language); ?>;
	var role = '<?php echo $staff->Position; ?>';
	</script>
	<?php endif; ?>
</head>

<body id="toppage-body"<?php echo (isset($lang)) ? ' class="font-'.$lang.'"' : ''; ?>>
    <div class="bg-img1">
        <div class="header" style="height:8vh;margin-bottom:15px;">
            <!-- language -->
            <?php echo $this->Html->image('lang/en.png', ['alt' => 'English','class'=>'img-flag flag-en', 'url' => ['controller' => 'App', 'action' => 'changeLanguage', 'en_US']]); ?>
            <?php echo $this->Html->image('lang/jp.png', ['alt' => 'Japan','class'=>'img-flag', 'url' => ['controller' => 'App', 'action' => 'changeLanguage', 'jp_JP']]); ?>
            <?php echo $this->Html->image('lang/vn.png', ['alt' => 'Vietnam','class'=>'img-flag', 'url' => ['controller' => 'App', 'action' => 'changeLanguage', 'vn_VN']]); ?>
			<!-- Notification -->
			<?php if($staff->Position == 'Area Leader') : ?>
			<div class="notification" id="OTNotice">
				<div class="notBtn" href="javascript:void(0);">
					<div class="number"></div>
					<nav class="dropdownContainer text-white">
						<i class="fa fa-bell dropdown-toggle"></i> OT
						<ul class="dropdown">
							<ul class="tabs" style="width: 100%;">
								<li class="tab-item" style="width: 50%;" data-id="my_ot_list"><a href="javascript:void(0);">Manage<span id="total_ot_request"></span></a></li>
								<li class="tab-item" style="width: 50%;" data-id="ot_list"><a href="javascript:void(0);" class="active">My OT<span id="total_ot"></span></a></li>
							</ul>
							<div class="wrapper_tab-content">
								<div id="my_ot_list" class="d-none">

								</div>
								<div id="ot_list">

								</div>
							</div>
						</ul>
					</nav>
				</div>
			</div>
			<div class="notification" id="ALNotice">
				<div class="notBtn" href="javascript:void(0);">
					<div class="number"></div>
					<nav class="dropdownContainer text-white">
						<i class="fa fa-bell dropdown-toggle"></i> AL
						<ul class="dropdown dropdown1">
							<ul class="tabs" style="width: 100%;">
								<li class="tab-item" style="width: 50%;" data-id="my_al_list"><a href="javascript:void(0);">Manage<span id="total_al_request"></span></a></li>
								<li class="tab-item" style="width: 50%;" data-id="al_list"><a href="javascript:void(0);" class="active">My AL<span id="total_al"></span></a></li>
							</ul>
							<div class="wrapper_tab-content">
								<div id="my_al_list" class="d-none">

								</div>
								<div id="al_list">

								</div>
							</div>
						</ul>
					</nav>
				</div>
			</div>
			<?php elseif($staff->Position == 'Leader') : ?>
			<div class = "notification" id="OTNotice">
				<div class = "notBtn" href = "javascript:void(0);">
					<div class="number"></div>
					<nav class="dropdownContainer text-white">
						<i class="fa fa-bell dropdown-toggle"></i> 
						<ul class="dropdown dropdown1">
							<ul class="tabs">
								<li class="tab-item" data-id="al_list"><a href="javascript:void(0);" class="active">My AL<span id="total_al"></a></li>
								<li class="tab-item" data-id="ot_list"><a href="javascript:void(0);">My OT<span id="total_ot"></a></li>
							</ul>
							<div class="wrapper_tab-content">
								<div id="al_list">

								</div>
								<div id="ot_list" class="d-none">

								</div>
							</div>
						</ul>
					</nav>
				</div>
			</div>

			<?php endif; ?>
            <!-- text logo -->
            <a href="<?php echo $this->Url->build('/', true); ?>"><img src="<?php echo $this->Url->build('/', true); ?>img/text.png?v=123" alt="logo" /></a>
        </div>
        <div class="main-highlight" style="min-height:46vh">
            <?php echo $this->fetch('content'); ?>
            <div class="copy" style="height:3vh">© NetSurf Co., Ltd.</div>
            <canvas id="canvas" style="display: none;"></canvas>
        </div>
    </div>

    <!-- elements -->
    <?php echo $this->element('Mypage/popup_sign'); ?>
    <?php echo $this->element('Mypage/popup_report'); ?>
    <?php echo $this->element('Mypage/popup_view_image'); ?>
    <?php echo $this->element('Mypage/popup_look_camera'); ?>
    <?php echo $this->element('Mypage/div_dictionary'); ?>

    <!-- jQuery  -->
    <?php
    echo $this->Html->script('bootstrap.bundle.min.js') . PHP_EOL;
    echo $this->Html->script('bootstrap.bundle.min.js') . PHP_EOL;
    echo $this->Html->script('detect.js') . PHP_EOL;
    echo $this->Html->script('fastclick.js') . PHP_EOL;
    echo $this->Html->script('jquery.blockUI.js') . PHP_EOL;
    echo $this->Html->script('waves.js') . PHP_EOL;
    echo $this->Html->script('jquery.slimscroll.js') . PHP_EOL;
    echo $this->Html->script('jquery.scrollTo.min.js') . PHP_EOL;
    echo $this->Html->script('plugins/moment/moment.js') . PHP_EOL;
    echo $this->Html->script('jquery.core.js') . PHP_EOL;
    echo $this->Html->script('jquery.app.js') . PHP_EOL;
    echo $this->Html->script('sweetalert.min.js') . PHP_EOL;
    echo $this->Html->script('venobox.min.js') . PHP_EOL;
	echo $this->Html->script('bootstrap-datepicker.min.js') . PHP_EOL;
    ?>
    <!-- App js -->
    <?php
    echo $this->Html->script('attached-picture.js?v='. date('YmdHis')) . PHP_EOL;
    echo $this->Html->script('mypage.js?v='. date('YmdHis')) . PHP_EOL;

    if(mb_strtolower($this->request->getParam('action')) == 'index'):
        echo $this->Html->script('capture.js?v='.date('YmdHis')) . PHP_EOL;
    endif;
    ?>

    <?php echo $this->fetch('body-end'); ?>

    <script>
	var digitalClock = document.getElementById("digital-clock");
	if(digitalClock !== null)
	{
		// Digital Clock
		setInterval(() => {
			let time = new Date();
			let hours = time.getHours();
			let minutes = time.getMinutes();
			let seconds = time.getSeconds();

			// Prepending 0 if less than 10
			hours = hours >= 10 ? hours : "0" + hours;
			minutes = minutes >= 10 ? minutes : "0" + minutes;
			seconds = seconds >= 10 ? seconds : "0" + seconds;

			// Adding the time in the DOM
			digitalClock.innerHTML = `${hours}:${minutes}:${seconds}`;

			var today = new Date();
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();

			today = yyyy + '/' + mm + '/' + dd;
			$('#today').html(today)
		}, 1000);
	}
    </script>
</body>

</html>
