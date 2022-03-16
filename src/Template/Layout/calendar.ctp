<!DOCTYPE html>
<html lang="en" translate="no">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title><?= SYSTEM_TITLE ?></title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo $this->Url->build('/', true); ?>img/favicon.ico">
    <script src="//polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script async defer src="//maps.googleapis.com/maps/api/js?key=<?= MAP_KEY ?>&libraries=&v=weekly"></script>
    <!-- CSS -->
    <?php
    echo $this->Html->css('bootstrap.min.css') . PHP_EOL;
    echo $this->Html->css('admin/sb-admin-2.min.css?' . date('YmdHis')) . PHP_EOL;
    echo $this->Html->css('calendar/fullcalendar.css') . PHP_EOL;
    echo $this->Html->css('calendar/datepicker.css') . PHP_EOL;
    echo $this->Html->css('calendar/jquery-ui.css') . PHP_EOL;
    echo $this->Html->css('icons.css?' . date('YmdHis')) . PHP_EOL;
    echo $this->Html->css('style.css?' . date('YmdHis')) . PHP_EOL;
    // JS
    echo $this->Html->script('jquery.min.js') . PHP_EOL;
    ?>

    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script type="text/javascript">
        let __baseUrl = "<?php echo $this->Url->build('/', true); ?>";
        let __csrfToken = '<?php echo $this->request->getParam('_csrfToken') ?>';
    </script>
    <?php echo $this->fetch('head-end'); ?>
</head>

<body id="calendar-body">
    <?php echo $this->element('Mypage/div_dictionary'); ?>
    <div class="bg-img1">
        <div class="header" style="height:7vh">
            <a href="<?php echo $this->Url->build('/', true); ?>"><img src="<?php echo $this->Url->build('/', true); ?>img/text.png?v=123" alt="logo" /></a>
        </div>
        <div>
            <?php echo $this->fetch('content'); ?>
            <div class="copy" style="height:3vh">© NetSurf Co., Ltd.</div>
            <canvas id="canvas" style="display: none;"></canvas>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <?= $this->Html->script('calendar/jquery.min.js') . PHP_EOL ?>
    <?= $this->Html->script('admin/bootstrap.bundle.min.js') . PHP_EOL ?>
    <?php
    //echo $this->Html->script('calendar/moment.min.js', ['block' => 'body-end']);
    echo $this->Html->script('calendar/jquery.min.js', ['block' => 'body-end']);
    //echo $this->Html->script('calendar/fullcalendar.js', ['block' => 'body-end']);
    echo $this->Html->script('jquery.ui.monthpicker.js', ['block' => 'body-end']);
    ?>

    <script src='//cdn.jsdelivr.net/npm/moment@2.24.0/min/moment.min.js'></script>
    <script src='//cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js'></script>
    <?php echo $this->fetch('body-end'); ?>
</body>

</html>
