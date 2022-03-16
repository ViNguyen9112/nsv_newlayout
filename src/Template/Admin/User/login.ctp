<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>Alsok Admin</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"/>
    <?php
        echo $this->Html->css('admin/style.css') . PHP_EOL;
    ?>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Poppins:400,500&amp;display=swap"/>
    <?php echo $this->Html->script('jquery.min.js') . PHP_EOL; ?>

    <style>
        .bg-white {
            background:#fff !important;
        }
        .bg-none {
            background: none !important;
            border: none !important;
        }
        #RememberMe {
            width: 17px;
            margin-left: 2px;
        }
    </style>
</head>
<body>
<div class="bg-img bg-white">
    <div class="content" style="display:none">
        <div class="logo"><?php echo $this->Html->image('admin/logo.png', ['alt' => 'logo']); ?></div>
        <header>Admin Login</header>
        <?= $this->Form->create() ?>
            <div class="field">
                <span class="fa fa-user"></span>
                <?= $this->Form->control('StaffID', ['type' => 'text', 'id' => 'StaffID','realonly' => true, "onfocus" => "$(this).removeAttr('readonly');", 'div' => false, 'label' => false,'maxlength' => '10', 'placeholder' => __('ID') ]) ?>
            </div>
            <div class="field space">
                <span class="fa fa-lock"></span>
                <?= $this->Form->control('Password',['type' => 'password', 'id' => 'Password','autocomplete' => false, 'class' => 'pass-key', 'div' => false, 'label' => false,'maxlength' => '10', 'placeholder' => __('Password')]) ?>
                <span class="show" data-type="show"><i class="fas fa-eye"></i></span>
            </div>
            <div class="field space bg-none">
                <?php echo $this->Form->checkbox('remember_me', array('hiddenField' => false, 'label' => false, 'id' => 'RememberMe')); ?>
                <label style="padding: 10px;" for="checkbox-signup">Remember me</label>
            </div>
            <?= $this->Flash->render('auth'); ?>
            <div class="field space">
                <?= $this->Form->text('Sign In', ['type' => 'submit', 'id' => 'btnSubmit']) ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
    <input type="hidden" id="StaffIDCookie" value="<?php if(isset($cookie)) echo $cookie['StaffID'];?>">
    <input type="hidden" id="PasswordCookie" value="<?php if(isset($cookie)) echo $cookie['Password'];?>">
    <input type="hidden" id="RoleCookie" value="<?php if(isset($cookie) && isset($cookie['role'])) echo $cookie['role'];?>">
</div>
</body>
<script src="//kit.fontawesome.com/a076d05399.js"></script>
</html>

<script>
    // show/hide password
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

    function beforeRender(){
        if($('#StaffIDCookie').val() != "" && $('#RoleCookie').val() == 'admin'){
            $('#StaffID').val($('#StaffIDCookie').val())
            $('#Password').val($('#PasswordCookie').val())
            $('#RememberMe').attr('checked', true)
            $('#btnSubmit').click()
        } else {
            $('.bg-img').removeClass('bg-white')
            $('.content').css('display', 'block')
        }
    }
    window.onload = beforeRender()
</script>
