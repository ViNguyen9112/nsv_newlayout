<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">

<head>
    <meta name="google" content="notranslate" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?=SYSTEM_TITLE?></title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo $this->Url->build('/', true); ?>img/favicon.ico">

    <!-- App css -->
    <?php
    echo $this->Html->css('bootstrap.min.css') . PHP_EOL;
    echo $this->Html->css('icons.css?' . date('YmdHis')) . PHP_EOL;
    echo $this->Html->css('style-user.css?' . date('YmdHis')) . PHP_EOL;
    echo $this->Html->script('jquery.min.js') . PHP_EOL;
    ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Poppins:400,500&amp;display=swap" />

    <script type="text/javascript">
        var __baseUrl = "<?php echo $this->Url->build('/', true); ?>";
        var csrfToken = '<?php echo $this->request->getParam('_csrfToken') ?>';
    </script>
    <style>
        .img-flag{
            position: fixed;
            z-index: 9999;
            width: 30px !important;
            height: 15px;
            top: 2%;
        }
        .flag-en {
            right:5%;
        }
        .flag-vn {
            right: 25%;
        }
        .flag-jp {
            right: 15%;
        }
        .clear {
            color:#ef0606 !important;
            visibility: hidden;
        }

        .input-login {
            visibility: hidden;
        }
        .bg-white {
            background:#fff !important;
        }
    </style>
</head>

<body>
    <!-- language -->
    <?php echo $this->Html->image('lang/en.png', ['id' => 'enLang', 'alt' => 'English','class'=>'img-flag flag-en']); ?>
    <?php echo $this->Html->image('lang/jp.png', ['id' => 'jpLang', 'alt' => 'Japan','class'=>'img-flag flag-jp']); ?>
    <?php echo $this->Html->image('lang/vn.png', ['id' => 'vnLang', 'alt' => 'Vietnam','class'=>'img-flag flag-vn']); ?>
    <div class="bg-img bg-white">
        <div class="content" style="display:none">

            <!-- logo -->
            <div class="logo">
                <?php echo $this->Html->image('logo.png', ['alt' => 'logo']); ?>
            </div>
            <?php echo $this->fetch('content'); ?>
        </div>
    </div>

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
    ?>
    <!-- App js -->
    <?php
    echo $this->Html->script('jquery.core.js') . PHP_EOL;
    echo $this->Html->script('jquery.app.js') . PHP_EOL;
    ?>

    <script>
        $(document).ready(function() {
            $('.show').on('click', function() {
                if ($('.show').data('type') == 'show') {
                    $('.show').html('<i class="fas fa-eye-slash"></i>')
                    $('.show').data('type', 'hide')
                    pass = $("input[name='Password']").val()
                    document.getElementsByName('Password')[0].type = 'text'
                    $("input[name='Password']").val(pass)
                } else {
                    $('.show').html('<i class="fas fa-eye"></i>')
                    $('.show').data('type', 'show')
                    pass = $("input[name='Password']").val()
                    document.getElementsByName('Password')[0].type = 'password'
                    $("input[name='Password']").val(pass)
                }
            })

            //  // CLEAR INPUT
            // $('.input-login').on('input', function(){
            //     if($('#StaffID').val() != ""){
            //         $('#clear_staffid').css('visibility','visible')
            //     } else {
            //         $('#clear_staffid').css('visibility','hidden')
            //     }
            //     if($('#Password').val() != ""){
            //         $('#clear_password').css('visibility','visible')
            //     } else {
            //         $('#clear_password').css('visibility','hidden')
            //     }
            // })
            // $('#clear_staffid').on('click', function(){
            //     $('#StaffID').val('')
            //     $('#clear_staffid').css('visibility','hidden')
            // })
            // $('#clear_password').on('click', function(){
            //     $('#Password').val('')
            //     $('#clear_password').css('visibility','hidden')
            // })

            $('#vnLang').on('click',function(){
                // set language
                $('#Language').val('vn_VN')
                // change text
                vnLanguage()
            })
            $('#enLang').on('click',function(){
                // set language
                $('#Language').val('en_US')
                // change text
                enLanguage()
            })
            $('#jpLang').on('click',function(){
                // set language
                $('#Language').val('jp_JP')
                // change text
                jpLanguage()
            })
        })

        function vnLanguage(){
            $('#text_header').html('Vui lòng đăng nhập!')
            $('#Password').attr('placeholder','Mật khẩu')
            $('#Submit').val("ĐĂNG NHẬP")
            if($('.message').length){
                $('.message').html('Bạn chưa được phép truy cập vào trang đó.')
            }
            if($('#flash').length){
                $('#flash_render').html($('#flash').val())
            }
        }

        function enLanguage(){
            $('#text_header').html('Please login!')
            $('#Password').attr('placeholder','Password')
            $('#Submit').val("LOGIN")
            if($('.message').length){
                $('.message').html('You are not authorized to access that location.')
            }
            if($('#flash').length){
                $('#flash_render').html($('#flash').val())
            }
        }

        function jpLanguage(){
            $('#text_header').html('ログインしてください!')
            $('#Password').attr('placeholder','パスワード')
            $('#Submit').val("ログインしてください")
            if($('.message').length){
                $('.message').html('位置情報を有効にしてください。')
            }
            if($('#flash').length){
                $('#flash_render').html($('#flash').val())
            }
        }

        function beforeRender(){
            // check authorized
            if($('.message').length){
                $('.message').html('Bạn chưa được phép truy cập vào trang đó.')
            }
            // set Vietnamese language
            vnLanguage()
            // check render flash
            if($('#flash').length){
                $('#flash_render').html($('#flash').val())
                if($('#current_language').val() == "vn_VN"){
                    vnLanguage()
                } else if($('#current_language').val() == "en_US"){
                    enLanguage()
                } else {
                    jpLanguage()
                }
                $('#StaffID').val($('#StaffID_error').val())
                $('#Password').val($('#Password_error').val())
                $('.input-login').css('visibility','visible')
                // $('#clear_staffid').css('visibility','visible')
                // $('#clear_password').css('visibility','visible')
            } else {
                // clear input
                setTimeout(function(){
                    $('#StaffID').val('')
                    $('#Password').val('')
                    // $('#clear_staffid').css('visibility','hidden')
                    // $('#clear_password').css('visibility','hidden')
                    $('.input-login').css('visibility','visible')
                },1500)
            }
        }
        window.onload = beforeRender()
    </script>
</body>

</html>
