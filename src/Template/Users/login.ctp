<style>
    .bg-none {
        text-align: left;
        margin-left: 2px;
    }
</style>

<header><span id="text_header">Please Login!</span></header>
<!--  -->
<p id="flash_render"></p>
<?php echo $this->Form->create('TBLMStaff'); ?>
    <div class="field">
        <span class="fa fa-user"></span>
        <!-- <span id="clear_staffid" class="clear"><i class="far fa-times-circle"></i></span> -->
        <?php echo $this->Form->text('StaffID', array('autocomplete' => "off", 'id' => 'StaffID', 'class' => 'input-login', 'placeholder' => 'ID', 'maxlength' => '10')); ?>
    </div>
    <div class="field space">
        <span class="fa fa-lock"></span>
        <!-- <span id="clear_password" class="clear"><i class="far fa-times-circle"></i></span> -->
        <?php echo $this->Form->password('Password', array('autocomplete' => "off", 'id' => 'Password', 'class' => 'pass-key input-login', 'placeholder' => 'Password', 'maxlength' => '10')); ?>
        <span class="show" data-type="show"><i class="fas fa-eye"></i></span>
    </div>
    <div class="bg-none">
        <?php echo $this->Form->checkbox('remember_me', array('hiddenField' => false, 'label' => false, 'id' => 'RememberMe')); ?>
        <label style="padding: 10px;" for="checkbox-signup">Remember me</label>
    </div>
    <input type="hidden" id="Language" name="Language" value="vn_VN">
    <div class="field space">
        <input id="btnSubmit" type="submit" value="LOGIN" />
    </div>
<?php echo $this->Form->end(); ?>
<div style="display:none">
    <?php if(isset($flash)):?>
        <input type="hidden" id="flash" value="<?php echo $flash; ?>">
        <input type="hidden" id="current_language" value="<?php echo $Language; ?>">
        <input type="hidden" id="StaffID_error" value="<?php echo $StaffID; ?>">
        <input type="hidden" id="Password_error" value="<?php echo $Password; ?>">
    <?php endif; ?>
</div>

<input type="hidden" id="StaffIDCookie" value="<?php if(isset($cookie)) echo $cookie['StaffID']; ?>">
<input type="hidden" id="PasswordCookie" value="<?php if(isset($cookie)) echo $cookie['Password']; ?>">
<input type="hidden" id="RoleCookie" value="<?php if(isset($cookie) && isset($cookie['role'])) echo $cookie['role'];?>">

<script>
    function beforeRender(){
        if($('#StaffIDCookie').val() != "" && $('#RoleCookie').val() == 'user'){
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
