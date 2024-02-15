<?php 
//Templates that will be used for system emails

function registration_template($fullname, $role, $url){
    $template = 
    '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
.body-wrap,body{background-color:#ecf0f5}.content,.content-wrap{padding:20px}.aligncenter,.btn-primary{text-align:center}*{margin:0;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px}img{max-width:100%}body{-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100%!important;height:100%;line-height:1.6em;color:#6c7b88}table td{vertical-align:top}.body-wrap{width:100%}.container{display:block!important;max-width:600px!important;margin:20px auto!important;clear:both!important}.clear,.footer{clear:both}.content{max-width:600px;margin:0 auto;display:block}.main{background-color:#fff;border-bottom:2px solid #d7d7d7}.content-block{padding:0 0 20px}.header{width:100%;margin-bottom:20px}.footer{width:100%;color:#999}.footer a,.footer p,.footer td{color:#999;font-size:12px}h1,h2,h3{font-family:"Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;color:#1a2c3f;margin:30px 0 0;line-height:1.2em;font-weight:400}h1{font-size:32px;font-weight:500}h2{font-size:24px}h3{font-size:18px}h4{font-size:14px;font-weight:600}ol,p,ul{margin-bottom:10px;font-weight:400}ol li,p li,ul li{margin-left:5px;list-style-position:inside}a{color:#348eda;text-decoration:underline}.btn-primary{text-decoration:none;color:#fff;background-color:#42a5f5;border:solid #42a5f5;border-width:10px 20px;line-height:2em;font-weight:700;cursor:pointer;display:inline-block;text-transform:capitalize}.last{margin-bottom:0}.first{margin-top:0}.alignright{text-align:right}.alignleft{text-align:left}@media only screen and (max-width:640px){.container,.content,body{padding:0!important}.container,.invoice{width:100%!important}h1,h2,h3,h4{font-weight:800!important;margin:20px 0 5px!important}h1{font-size:22px!important}h2{font-size:18px!important}h3{font-size:16px!important}.container{margin:20px auto!important}.content-wrap{padding:10px!important}}
</style>
</head>
<body>
<table class="body-wrap">
<tr>
<td></td>
<td class="container" width="600">
<div class="content">
<table class="main" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td class="content-wrap">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td>
<img src="cid:care_logo">
</td>
</tr>
<tr>
<td class="content-block">
<h3>Dear '.$fullname.',</h3>
</td>
</tr>
<tr>
<td class="content-block">
Your account on the Vendor Management System has been successfully created.
</td>
</tr>
<tr>
<td class="content-block">
You will perform the role of <strong>'.$role.'.</strong>
</td>
</tr>
<tr>
<td class="content-block">
Please confirm your email address by clicking on the link below.</td>
</tr>
<tr>
<td class="content-block aligncenter">
<a href="'.$url.'" class="btn-primary" target="_blank">Confirm email address</a>
</td>
</tr>
<tr>
<td>
</br>
</br>
    <small><i><b>Note:</b> This link expires within 24 hours. Contact System Administrator for support.</i></small>
</td>
</tr>
</table>
</td>
</tr>
</table>
<div class="footer">
<table width="100%" style="  background-color: #1C1D30;">
<td class="aligncenter content-block" >
<p style="font-size:17px;color:aliceblue;padding-top:10px">CARE International in Uganda</p>
<p style="color:#e4701e;"><?php echo date("Y");?> &copy; CARE Uganda - All rights reserved</p>
<p style="color:aliceblue;">+256-312258100 || uga.logistics@careuganda.zohodesk.com </p>
</td>
</tr>
</table>
</div></div>
</td>
<td></td>
</tr>
</table>
</body>
</html>
    ';

    return $template;
}

function password_reset_template($firstname, $url){
    $template = 
    '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
.body-wrap,body{background-color:#ecf0f5}.content,.content-wrap{padding:20px}.aligncenter,.btn-primary{text-align:center}*{margin:0;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px}img{max-width:100%}body{-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100%!important;height:100%;line-height:1.6em;color:#6c7b88}table td{vertical-align:top}.body-wrap{width:100%}.container{display:block!important;max-width:600px!important;margin:20px auto!important;clear:both!important}.clear,.footer{clear:both}.content{max-width:600px;margin:0 auto;display:block}.main{background-color:#fff;border-bottom:2px solid #d7d7d7}.content-block{padding:0 0 20px}.header{width:100%;margin-bottom:20px}.footer{width:100%;color:#999}.footer a,.footer p,.footer td{color:#999;font-size:12px}h1,h2,h3{font-family:"Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;color:#1a2c3f;margin:30px 0 0;line-height:1.2em;font-weight:400}h1{font-size:32px;font-weight:500}h2{font-size:24px}h3{font-size:18px}h4{font-size:14px;font-weight:600}ol,p,ul{margin-bottom:10px;font-weight:400}ol li,p li,ul li{margin-left:5px;list-style-position:inside}a{color:#348eda;text-decoration:underline}.btn-primary{text-decoration:none;color:#fff;background-color:#009999;border:solid #009999;border-width:10px 20px;line-height:2em;font-weight:700;cursor:pointer;display:inline-block;text-transform:capitalize}.last{margin-bottom:0}.first{margin-top:0}.alignright{text-align:right}.alignleft{text-align:left}@media only screen and (max-width:640px){.container,.content,body{padding:0!important}.container,.invoice{width:100%!important}h1,h2,h3,h4{font-weight:800!important;margin:20px 0 5px!important}h1{font-size:22px!important}h2{font-size:18px!important}h3{font-size:16px!important}.container{margin:20px auto!important}.content-wrap{padding:10px!important}}
</style>
</head>
<body>
<table class="body-wrap">
<tr>
<td></td>
<td class="container" width="600">
<div class="content">
<table class="main" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td class="content-wrap">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td>
<img src="cid:care_logo">
</td>
</tr>
<tr>
<td class="content-block">
<h3>Dear '.$firstname.',</h3>
</td>
</tr>
<tr>
<td class="content-block">
You are receiving this email because you have initiated a password reset request for your account.
</td>
</tr>
<tr>
<td class="content-block">
Please click the button below to reset your password.</td>
</tr>
<tr>
<td class="content-block aligncenter">
<a href="'.$url.'" class="btn-primary" target="_blank">Reset Password</a>
</td>
</tr>
<tr>
</tr>
<tr>
<td>
</br>
</br>   
    <small><i><b>Note:</b> This link expires in 60 minutes.</i></small>
<br/>
<br/>
<p style="text-align:center">If you did not initiate this request, please discard this email.</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
<div class="footer">
<table width="100%" style="  background-color: #1C1D30;">
<td class="aligncenter content-block" >
<p style="font-size:17px;color:aliceblue;padding-top:10px">CARE International in Uganda</p>
<p style="color:#e4701e;"><?php echo date("Y");?> &copy; CARE Uganda - All rights reserved</p>
<p style="color:aliceblue;">+256-312258100 || uga.logistics@careuganda.zohodesk.com </p>
</td>
</tr>
</table>
</div></div>
</td>
<td></td>
</tr>
</table>
</body>
</html>
    ';
    return $template;
}
?>
