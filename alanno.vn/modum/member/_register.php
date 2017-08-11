<?php
$msg2="";
if(isset($URL[1]))
{
$isEdit=(($URL[1]==md5("edit".date("dmYH")))?True:False);
}else
{
  $isEdit=False;
}

if($_SESSION["Free"]==0 && !$isEdit)
{
	$html->redirectURL("account.html");
}
?>
<div class="section_wrapper mcb-section-inner">
<div class="pro_c">
<?php
if($_SESSION["Free"]==0)
{
        $rstgetInfo=$dbf->getDynamic("member","status=1 and id='".$_SESSION["member_id"]."'","");
        $rowgetInfo=$dbf->nextObject($rstgetInfo);

        $email= $rowgetInfo->email;
        $password="";
    	$firstname  = stripslashes($rowgetInfo->firstname);
        $lastname   = stripslashes($rowgetInfo->lastname);
        $phone      = stripslashes($rowgetInfo->phone);
    	$address    = stripslashes($rowgetInfo->address);
        $fax        =   addslashes($rowgetInfo->fax);
        $home_flat_number       =   addslashes($rowgetInfo->home_flat_number);
        $street_number          =   addslashes($rowgetInfo->street_number);
        $street_name            =   addslashes($rowgetInfo->street_name);
        $country_id = stripslashes($rowgetInfo->country_id);
        $state      = stripslashes($rowgetInfo->state);
        $postcode   = stripslashes($rowgetInfo->postcode);

}

if(isset($_POST["ok"]) || isset($_POST["email"]) )
{
	$email      =   $_POST["email"];
    $password   =   $_POST['password'];
    $password   =   $utl->stripUnicode($password);
    $temp_pwd   =   $password;
	$firstname  =   addslashes($_POST['firstname']);
    $lastname   =   addslashes($_POST['lastname']);
    $phone      =   addslashes($_POST['phone']);
    $fax        =   addslashes($_POST['fax']);
    $address    =   addslashes($_POST['address']);
    $home_flat_number       =   addslashes($_POST["home_flat_number"]);
    $street_number          =   addslashes($_POST["street_number"]);
    $street_name            =   addslashes($_POST["street_name"]);

    $country_id =   addslashes($_POST["country_id"]);
    $state      =   $_POST["state"];
    $postcode   =   addslashes($_POST["postcode"]);


	$time=time();
	$code=strtoupper($_POST["captcha"]);

	if($code!=$_SESSION["captcha_id"])
	{
        $html->redirectURL("/register/security-code-error.html");
        exit;
	}else
	{
          $password=md5($password);
           if(!$isEdit)
            {
        		$rstcheck=$dbf->getDynamic("member","email='".$email."'","");
        		if($dbf->totalRows($rstcheck)>0)
        		{
                    $html->redirectURL("/register/email-is-register.html");
                    exit;
        		}
                else
                {

                   $array_col=array("email"=>$email,"password"=>$password,"firstname"=>$firstname,"lastname"=>$lastname,
                  "phone"=>$phone,"fax"=>$fax,"address"=>$address,"country_id"=>$country_id,"state"=>$state,
                  "postcode"=>$postcode,"status"=>1,"datecreated"=>$time,"dateupdated"=>$time);
                  //print_r($array_col);
                  $affect=$dbf->insertTable("member",$array_col);
                }

            }else
            {
              $array_col=array("firstname"=>$firstname,"lastname"=>$lastname,
                  "phone"=>$phone,"fax"=>$fax,"address"=>$address,"country_id"=>$country_id,"state"=>$state,
                  "postcode"=>$postcode,"dateupdated"=>$time);

              $affect=$dbf->updateTable("member",$array_col,"id='".$_SESSION['member_id']."'");
            }

                if($affect>0)
				{
                    if($isEdit)
                    {
                    $html->redirectURL("/account.html");
                    exit;
                    }
                    else
                    {
                        $query=$dbf->getDynamic("member","email='".$email."' and status=1","");
    					if($dbf->totalRows($query)>0)
    					{
                            $row = $dbf->nextData($query);
                            $_SESSION["member_id"]          = stripslashes($row["id"]);
    						$_SESSION["member_email"]       = $email;
                            $_SESSION["member_firstname"]   = stripslashes($row["firstname"]);
                            $_SESSION["member_lastname"]    = stripslashes($row["lastname"]);
                            $_SESSION["Free"]=0;

                		}
                        //gui mail
                        $str="<html>
                              <head>
                              <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                              <meta http-equiv='Content-Language' content=\en-us' />
                              <style type='text/css'>
                               body{font-family: Arial;font-size: 12px; color:#333333;}
                              .tableSendEmail .tdParent{ border-bottom: 1px solid #EBF1F6; height: 25px; text-align: left; vertical-align: middle; padding-left:10px;}
                              .tableSendEmail .tdParent div{ clear:both; text-align: left; padding-left:20px; background: url(../Images/bullet_sanpham.jpg) left center no-repeat; text-transform: uppercase; font-weight: bold;}
                              .tableSendEmail .tdParent div .link{ text-decoration: none;  color: #333333;}
                              .tableSendEmail .tdParent div .link:hover{ color: #F5841E; text-decoration: none;}
                              .tableSendEmail .tdItem{ border-bottom: 1px solid #EBF1F6; text-align: left; vertical-align: middle; padding:5px;}
                              .tableSendEmail .tdItem_right{ border-bottom: 1px solid #EBF1F6; text-align: left; vertical-align: middle; padding:5px; border-right:1px solid #B4CDD7;}
                              .tableSendEmail .tdItem_right .linkTitle{ font-size:13px; font-weight:bold; color:#333333; text-decoration: none;}
                              .tableSendEmail .tdItem_right .linkTitle:hover{ font-size:13px; font-weight:bold; color:#F5841E; text-decoration: none;}
                              </style>
                              </head>
                              <body>
                              <div class='aroundEmail' style='clear:both; text-align:left;'>
                              <table width='100%' height='100%' cellpadding='1' cellspacing='1' bgcolor='#C1C9DB'>

                                <tr style='background:#FFFFFF'><td colspan=\"2\" height:30px></td></tr>
                                <tr style='background:#FFFFFF'>
                                <td colspan=\"2\">
                                    Welcome : <b>".$firstname."&nbsp;".$lastname."</b><br/>
                                    Congratulations you have become a member webiste  <a href='".$host."'>".$host." </a><br/>To start using these utilities for membership, please click on: <a href='".$host."'>".$host." </a>

                                    <br/><br/>Email:<b>".$email."</b>
                                    <br/>Password:<b> ".$_POST['password']."</b>
                                </td>
                                </tr>
                              </table>
                              </div>
                              </body></html>";

                                $Subject  =  "A confirmation email members";
                                require("/modum/class.phpmailer.php");
                                $mail = new PHPMailer();
                                $SMTP_Host = $arraySMTPSERVER["host"];
                                $SMTP_Port = 25;
                                $SMTP_UserName = $arraySMTPSERVER["user"];
                                $SMTP_Password = $arraySMTPSERVER["password"];

                                $from = $SMTP_UserName;
                                $fromName = $info["TITLE"];
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
                                $mail->WordWrap = 50;
                                $mail->IsHTML(true);
                                $mail->Subject  =  $Subject;
                                $mail->Body     =  $str;
                                $mail->AltBody  =  "This is the text-only body";


                              if($mail->Send())	{
                                 $html->redirectURL("/account.html");
                              }
                              else
                              {
                                 $html->redirectURL("/account.html");
                              }
                     }
				}
                else
                {
                  $html->redirectURL("/register/error.html");
                }

	}
}


if(isset($URL[1]))
{
	switch ($URL[1]) {
		  case "error" :
				$msg=$words["ALERTERRORREGISTER"];
				break;
		  case "email-is-register" :
				$msg=$words["ALERTEMAILISREGISTER"];
				break;
		   case "security-code-error" :
				$msg=$words["ALERTSECURITYCODEERROR"];
				break;
	}
}

require 'captcharand.php';
$_SESSION['captcha_id'] = strtoupper($strcaptcha);
?>


<form id="frm" name="frm" method="post" action="" enctype="application/x-www-form-urlencoded">
<div class="pro_c">
<div  style="text-align:left;padding-left:15px"><span class="saodo"><?php echo $msg;?></span></div>

<fieldset style="border:0px">
    <legend><b><?php echo $words["INFOLOGIN"];?></b></legend>

<div class="saodo"><h3 style="padding-left:10px;color:#c00;font-size:12px;margin:0px"><?php echo $msg2?></h3></div>

<div class="clear" style="padding-top:10px"></div>
<div class="clear"></div>
<div class="right_row1">E-mail</div>
<div class="right_row2">
  <input class="full" <?php echo (($isEdit)?"readonly":"")?> type="text" maxlength="50"  onfocus="this.select();" value="<?php echo $email?>" name="email" id="email"  /><span class="txtdo">*</span>
</div>

<?php
if(!$isEdit)
{
?>
<!--password-->
<div class="clear"></div>
<div class="right_row1"><?php echo $words["PASSWORD"];?></div>
<div class="right_row2">
  <input class="full" type="password" maxlength="50"  onfocus="this.select();" value="<?php echo $temp_pwd;?>"   name="password" id="password"  /><span class="txtdo">*</span>
</div>
<div class="clear"></div>
<?php
}
?>
<!--Email-->

</fieldset>
<div class="clear" style="padding-top:10px"></div>
<fieldset style="border:0px;">
<legend>
   <b><?php echo $words["CONTACTINFORMATION"];?></b>
</legend>
<div class="clear" style="padding-top:10px"></div>
<div class="right_row1"><?php echo $words["FIRSTNAME"];?></div>
<div class="right_row2">
  <input class="full" maxlength="200"  type="text" onfocus="this.select();" value="<?php echo $firstname;?>" name="firstname" id="firstname"  /><span class="txtdo">*</span>
</div>
<div class="clear"></div>
<div class="right_row1"><?php echo $words["LASTNAME"];?></div>
<div class="right_row2">
  <input class="full" maxlength="200"  type="text" onfocus="this.select();" value="<?php echo $lastname;?>" name="lastname" id="lastname"  /><span class="txtdo">*</span>
</div>

<div class="clear"></div>
<div class="right_row1">Địa chỉ</div>
<div class="right_row2">
  <input class="full" maxlength="200"  type="text" onfocus="this.select();" value="<?php echo $address;?>" name="address" id="address"  /><span class="txtdo">*</span>
</div>

<div class="clear"></div>
<div class="right_row1"><?php echo $words["PHONE"];?></div>
<div class="right_row2">
  <input class="full"  type="text" onfocus="this.select();" value="<?php echo $phone;?>" name="phone" id="phone"  />
  <span class="txtdo">*</span>
</div>

<div class="clear"></div>
<div class="right_row1">FAX</div>
<div class="right_row2">
  <input class="full"  type="text" onfocus="this.select();" value="<?php echo $fax;?>" name="fax" id="fax"  />
</div>

<div class="clear"></div>
<div class="right_row1">Quận/tỉnh</div>
<div class="right_row2">
  <?php    
      $rststate=$dbf->getDynamic("shipping_method","status=1","title asc");
      echo'<select name="state" id="state" size="0" class="">
           <option value="">- Chọn quận/tỉnh -</option>';
      while($row = $dbf->nextData($rststate))
       {
          echo'<option '.(($state==$row['id'])?"selected":"").' value="'.$row['id'].'">'.$row['title'].'</option>';
       }
          echo'<option '.(($state=='other')?"selected":"").' value="other">Khác</option>';

      echo'</select>';	  

  ?>
  <span class="txtdo">*</span>
</div>

<div class="clear"></div>
<div class="right_row1"><?php echo $words["POSTCODE"];?></div>
<div class="right_row2">
  <input class="full"  type="text" onfocus="this.select();" value="<?php echo $postcode;?>" name="postcode" id="postcode"  />
  <span class="txtdo">*</span>
</div>

<div class="clear"></div>
<div class="right_row1"><?php echo $words["COUNTRY"];?></div>
<div class="right_row2">
<?php
     
      $rstCountry=$dbf->getDynamic("countries","status=1","countries_name asc");
      echo'<select name="country_id" id="country_id" size="0" class="">
           <option value="">- Chọn đất nước -</option>';
      while($row = $dbf->nextData($rstCountry))
       {
          if($isEdit)
          {
          echo'<option '.(($country_id==$row['id'])?"selected":"").' value="'.$row['id'].'">'.$row['countries_name'].'</option>';
          }else
          {
          echo'<option '.(($row['id']==230)?"selected":"").' value="'.$row['id'].'">'.$row['countries_name'].'</option>';
          }
       }
      echo'</select>';
	 
?>
     <span class="txtdo">*</span>
</div>
</fieldset>
<fieldset style="border: 0">
<legend></legend>

<?php
    echo "<div class='title'></div>";
?>

<div class="right_row1"><?php echo $words["SECURITYCODE"];?></div>
<div class="right_row2">     
	 <?php echo $_SESSION['captcha_id'];?><br/>
     <input type="text" maxlength="10" name="captcha" id="captcha" onfocus="this.select()" class="inputCode" />
</div>

</fieldset>

<div class="clear"></div>

<fieldset style="border: 0">
<div class="right_row1"></div>
<div class="right_row2">
<?php
if($isEdit)
{
?>
<input type="submit"  value="<?php echo $words["UPDATE"];?>" name="ok" id="ok" />
<input type="button"  onclick="window.location='/account.html';" value="<?php echo $words["CANCAL"];?>" name="retype" id="retype" />

<?php
}
else
{
?>
<input type="submit"  value="<?php echo $words["REGISTER"];?>" name="ok" id="ok"  />
<input type="reset" value="<?php echo $words["RESET"];?>" name="retype" id="retype" />
<?php
}
?>
</div>
</fieldset>
</div>

<!--check form-->
</form>


<script type="text/javascript">
jQuery().ready(function() {
jQuery("#frm").validate({
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

              password:
              {
                required: true,
                minlength: 6,
                maxlength: 30
              },

              firstname:
              {
                required: true

              },
              lastname:
              {
                required: true

              },

              address:
              {
                required: true

              },

              phone:
              {
                required: true

              },

              country_id:
              {
                required: true

              },

              state:
              {
                required: true

              },

              postcode:
              {
                required: true

              },


              captcha:
              {
                required: true,
                remote: "/captchaprocess.php"
              }
    		},
            messages:
            {
               email:
              {
                required: "Vui lòng nhập địa chỉ E-mail",
                email: "Địa chỉ E-mail sai định dạng"
              },

              password:
              {
                required: "Vui lòng nhập mật khẩu",
                minlength: "Mật khẩu nhập tối  thiểu 6 ký tự",
                maxlength: "Mật khẩu nhập tôi đa 30 ký tự"
              },

              firstname:
              {
                required: "Vui lòng nhập họ"

              },

               lastname:
              {
                required: "Vui lòng nhập tên"

              },

              address:
              {
                required: "Vui lòng nhập địa chỉ"

              },

              phone:
              {
                required: "Vui lòng nhập số điện thoại"

              },

              country_id:
              {
                required: "Vui lòng chọn quốc gia"

              },

              state:
              {
                required: "Vui lòng chọn Quận/Tỉnh"

              },

              postcode:
              {
                required: "Vui lòng nhập tên tỉnh"

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
</div>