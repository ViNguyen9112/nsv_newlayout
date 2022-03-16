<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- FIXME: add name staff -->
<title>Alsok Admin - Namecard</title>
<style>
    /*start_all*/
    body {
        min-width: 320px;
        background: fff;
        color: #1a1a1a;
        font-family: "Helvetica Neue";
        font-size: 15px;
        font-weight: 600;
        line-height: 1.8;
        letter-spacing: .09em;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        margin:30px
    }

    t {
        box-sizing: border-box;
    }

    img {
        width: 100%;
    }

    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    user-select: none;
    }

    .card{
        width:10.3cm;
        height:6cm;
        border:1px #999 solid;
        margin:0 auto;
        top:50%;
        /* background:url('../../img/namecard/bg.png') no-repeat; */
        /* background-size: 10.3cm 6cm; */
    }

    .content{
        width:100%;
        padding:20px 20px 5px 20px;
        height:177px;
    }

    .logo{
        width: 80px;
        float:left;
    }

    .title{
        width: 260px;
        float: right;
        font-size: 12px;
        font-weight:600;
        letter-spacing: 0px;
        /* padding-left: 10px; */
        padding-top: 0;
        text-align: right;
    }

    img {
        width:100%
    }

    .photo{
        width: 120px;
        /* float:left; */
        border: 2px #293991 solid;
        margin-top: 30px;
    }

    .info{
        width: 220px;
        float:right;
        font-size: 18px;
        text-align: center;
        line-height: 24px;
        padding-top: 15px;
        margin-bottom: 10px;
        position: absolute;
        top: 40px;
        right: 140px;
    }

    span {
        font-size:15px
    }

    .qr{
        width:100%;
        text-align:center;
        margin-top: 10px;
        padding-top: 20px;
        /* position: relative; */
        display: block;
        position: absolute;
        top: 80px;
        right: 240px;
    }

    .qr img{
        width: 80px;
    }

    .footer{
        font-size:8px;
        text-transform:uppercase;
        text-align:right;
        /* position: absolute; */
        /* bottom: 0px; */
        letter-spacing: 0px;
        margin: 8px;
    }
</style>
</head>

<body>
<div class="card" style="background:url(<?php echo $data['img_bg']; ?>) no-repeat">
    <div class="content">
      <div class="logo">
        <img alt=""  src="<?php echo $data['img_logo']; ?>" /></div>
        <div class="title">ALSOK VIETNAM SECURITY SERVICES JSC</div>
        <div class="photo"><img alt=""  src="<?php echo $data['img_demo']; ?>" /></div>
        <div class="info">
          <span><?php echo $data['StaffID']; ?></span><br/>
          <?php echo $data['Name']; ?>
        </div>
        <div class="qr"><img alt=""  src="<?php echo $data['img_qr']; ?>" /></div>
      </div>
      <div class="footer">Professional Security Sevices Provider</div>
  </div>
</body>

</html>
