<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#3a57c4">
    <title>NSV SYSTEM Ver1.0</title>

    <link rel="shortcut icon" sizes="45x45" href="images/logo.ico" />
    <?php 
        echo $this->Html->css('new_css/bootstrap.min.css') . PHP_EOL; 
        echo $this->Html->css('new_css/neon-core.css?v=10'); 
        echo $this->Html->css('new_css/all.min.css') . PHP_EOL; 
        echo $this->Html->css('new_css/swiper.min.css') . PHP_EOL; 
        echo $this->Html->css('new_css/circle.css') . PHP_EOL; 
        echo $this->Html->css('new_css/style.css?v=10') . PHP_EOL; ?>
</head>

<body>
    <div class="main-wrapper">
        <div class="home">
            <div class="navbar two-action no-hairline">
                <div class="navbar-inner d-flex align-items-center">
                    <div class="sliding custom-title"><img src="images/title.png" alt="">
                    </div>
                    <div class="right ">
                        <a href="login.html" class="link icon-only"><i class="fa-solid fa-arrow-right-from-bracket"></i></i></a>
                    </div>
                </div>
            </div>
            <div class="page-content header-bg">
                <div class="top-search">
                    <div class="container">
                        <div class="search-area">
                            <div><i class="fa-solid fa-user-tie"></i> <span class="postion">LEADER</span> <span class="div2"><?php echo $staff->StaffID; ?> - <?php echo $staff->Name; ?></span>
                            </div>

                            <div class="clearfix"></div>
                            <hr>
                            <div class="col-12 text-center font-18px">
                                <span id="today"></span><span class="time" id="digital-clock"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->fetch('content'); ?>
        </div>
    </div>

    <?php 
        echo $this->Html->script('new_js/jquery-3.6.0.min.js') . PHP_EOL; 
        echo $this->Html->script('new_js/bootstrap.bundle.min.js') . PHP_EOL; 
    ?>
    <script src="https://kit.fontawesome.com/c52b315e17.js" crossorigin="anonymous"></script>
    <script>
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
            document.getElementById(
                "digital-clock"
            ).innerHTML = `${hours}:${minutes}:${seconds}`;

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '/' + mm + '/' + dd;
            $('#today').html(today)
        }, 1000);

        $(document).ready(function() {
        })
    </script>
    <script>
        $(function() {
            // Dropdown toggle
            $('.dropdown-toggle').click(function() {
                $(this).next('.dropdown').toggle(400);
            });
        });
    </script>
</body>

</html>