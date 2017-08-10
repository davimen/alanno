<div class="sitepath"><?php include("modum/sitePath.php");?></div>
<div class="left">
    <?php include("modum/left.php");?>
</div>
<div class="right">
<?php
if($_SESSION["Free"]==0)
{
	$html->redirectURL("account.html");
}



if(isset($_POST["subForget"]))
{

$email=$_POST["email"];
$email=$dbf->escapeStr($email);
$code=strtoupper($_POST["captcha"]);
if($code!=$_SESSION["captcha_id"])
{
	$msg="Security code error";
}else
{
	$rstcheck=$dbf->getDynamic("member","email='".$email."'","");
	if($dbf->totalRows($rstcheck)>0)
	{
		$rowcheck=$dbf->nextData($rstcheck);
		$firstname=$rowcheck["firstname"];
		$random=rand(1,100);
		$password=$username.date("His_").$random;
		$affect=$dbf->updateTable("member",array("password"=>md5($password)),"email='".$email."'");
		if($affect>0)
		{
		     /* Get email address
                  *****************************/

                  /* Get template
                  *****************************/


                  $subject="Cung cấp lại mật khẩu thành viên của website designercollection4you.com";
                  $body='Email:'.$email.'<br/>';
                  $body.='Password:'.$password.'<br/>';

                  require("modum/class.phpmailer.php");
                  $mail = new PHPMailer();
                  $SMTP_Host = $arraySMTPSERVER["host"];
                  $SMTP_Port = 25;
                  $SMTP_UserName = $arraySMTPSERVER["user"];
                  $SMTP_Password = $arraySMTPSERVER["password"];
                  $from = $SMTP_UserName;
                  $fromName = $yourname;
                  $to = $email;

                  $mail->IsSMTP();
                  $mail->Host     = $SMTP_Host;
                  $mail->SMTPAuth = true;
                  $mail->Username = $SMTP_UserName;
                  $mail->Password = $SMTP_Password;
                  $mail->From     = $from;
                  $mail->FromName = $fromName;
                  $mail->AddAddress($to);
                  $mail->AddReplyTo($from, $fromName);
                  $mail->AddCC("thuy.tq@angiang24h.com","Quên mật khẩu");

                  $mail->WordWrap = 50;
                  $mail->IsHTML(true);
                  $mail->Subject  =  $subject;
                  $mail->Body     =  $body;
                  $mail->AltBody  =  "This is the text-only body";

                   if($mail->Send())	{
                      $html->redirectURL("/forgot-password/success");
                    }else
                  {
                      $html->redirectURL("/forgot-password/error");
                  }
		      }
          }else {
                $html->redirectURL("/forgot-password/email-error");
          }
    }
}

require 'captcharand.php';
$_SESSION['captcha_id'] = strtoupper($strcaptcha);
 echo $html->normalForm("frmForget",array("class"=>"jNice","action"=>"","method"=>"post"));

if(in_array($URL[1],array("success","error","email-error")))
{
    if($URL[1]=="success")
        $msg = $words["ALERTFORGETPASSWORDSUCCESS"];
    else if($URL[1]=="error")
        $msg = $words["ALERTFORGETPASSWORDERROR"];
    else
    {
        $msg ="Email này không tồn tại trong hệ thống. Xin vui lòng kiểm tra lại";
    }

}

    ?>

    <div class='title_header'><?=$words["FORGETPASSWORD"]?></div>
    <div class="pro_c">
    <div  style="text-align:left;padding-left:15px"><span class="saodo"><?=$msg?></span></div>
    <div id="clear"></div>
    <div class="productall1">
    <div class="clear" style="padding-top:5px;"></div>
	<div class="right_row1">E-mail</div>
	<div class="right_row2">
	<input class="full" type="text" value="<?=$email?>" onFocus="this.select()" name="email" id="email"/><span class="saodo">*</span>
	</div>
    <div class="clear"></div>
    </div>

	<div class="clear" style="padding-top:7px;"></div>

	<div class='title'><b><?=$words["INFOSECURITYCODE"]?></b></div>

    <div class="productall1">
	<div class="clear" style="padding-top:5px;"></div>
	<div class="right_row1"><?=$words["SECURITYCODE"]?></div>
	<div class="right_row2">
        <div id="captchaimage"><a href="javascript:void(0);" id="refreshimg" title="Click to refresh image">
        <img src="captchaimages/image.php?<?php echo time()?>" border="0" width="132" height="46" alt="Captcha image" /></a></div>
        <input type="text" maxlength="10" name="captcha" id="captcha" onfocus="this.select()" class="inputCode" /><span class="saodo">*</span>
	</div>
	<div class="clear"></div>
    <div class="right_row1"></div>
	<div class="right_row2">
	<input type="submit" value="Submit" name="subForget" id="subForget"  />
	</div>
    <div class="clear"></div>
    </div>
    <div id="clear"></div>
</div>
<div class='box_bottom_main'></div>
<?php
echo $html->closeForm();
?>

<script language="javascript">
$(function(){
	$("#refreshimg").click(function(){
		$.post('captchanewsession.php');
		$("#captchaimage").load('captchaimage_req.php');
		return false;
	});
});
$().ready(function() {
$("#frmForget").validate({
            debug: false,
            errorElement: "em",
            success: function(label) {
    				label.text("").addClass("success");
    		},
    		rules: {

              email:
              {
                required: true,
                email: true
              },
              captcha:
              {
                required: true,
                remote: "captchaprocess.php"
              }

            },
           messages:
              {
                  email:
                  {
                    required: "Vui lòng nhập email",
                    email: "Email sai. Vui lòng nhập lại"
                  },
                  captcha:
                  {
                    required: "Vui lòng nhập mã bảo vệ",
                    remote: "Mã bảo vệ sai"
                  }
              }
	});
});
</script>

</div>
<div class="clear"></div>