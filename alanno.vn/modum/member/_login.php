<?php
    if(isset($_POST["submitlogin"]))
    {
      	$email=$_POST["email"];
        $password=$_POST["password"];
        $password=$dbf->escapeStr($password);
    	if(isset($_POST["password"])&&($_POST["password"]!=""))
    	{
    		$password=md5($password);
    		$query=$dbf->getDynamic("member","email='".$email."' and password='".$password."' and status=1","");
    		if($dbf->totalRows($query)>0)
    		{
    		        $row=$dbf->nextData($query);
                    $_SESSION["member_id"]          = stripslashes($row["id"]);
                    $_SESSION["member_email"]       = $email;
                    $_SESSION["member_firstname"]   = stripslashes($row["firstname"]);
                    $_SESSION["member_lastname"]    = stripslashes($row["lastname"]);
                    $_SESSION["Free"]=0;
                    $html->redirectURL("account.html");
    		}
    		else
    		{
    				$msg="Email or mật khẩu sai. Vui lòng đăng nhập lại";
    		}
    	}
    }
?>
<?php
if($_SESSION["Free"]==0)
{
	$html->redirectURL("account.html");
}
?>
<div class="section_wrapper mcb-section-inner">
<div class="pro_c">
      <form name="frmLogin" id="frmLogin" action="/login.html" method="post">
      <div style="text-align:left">
            <div class="lblError">
            <?php if(isset($msg))
                  {
                    echo $msg;
                  }
            ?>
            </div>
            <div style="font-size:12px;text-align:left;width:260px;padding:5px"><span align="left" style="padding-left:0px;" id="lblError" class="saodo"></span></div>
            <div id="clear"></div>
            <div id="labelLogin">Email:</div>
            <div id="fieldLogin">
            <input type="text" onfocus="this.select();" onkeypress="document.frmLogin.password.value='';document.getElementById('lblError').innerHTML='';" class="email" id="email" name="email">
            <span class="lblError" id="lblemail"></span></div>

            <div class="clear" style="padding-top:2px"></div>
            <div id="labelLogin">Mật khẩu</div>
            <div id="fieldLogin">
            <input id="password" class="username" type="password" onfocus="this.select();" maxlength="30" name="password"><span class="lblError" id="lblpassword"></span>
            </div>
            <div id="clear" style="padding-top:2px"></div>
            <div id="labelLogin"></div>
            <div style="float:left">
                <input type="submit" name="submitlogin" id="submit" value="Đăng nhập" />
            </div>
            <div id="clear"></div>
            <div style="text-align:left;padding-top:8px">
                <a class="itempathhome" href="forgot-password.html">Quên mật khẩu?</a>
            </div>
            <div style="border-bottom:2px dotted #1a8aca;height:15px"></div>
            <div style="text-align:left;padding-top:15px"></div>
            <div style="text-align:left;padding-top:5px">Bạn chưa có tài khoản <a class="itempathhome" href="register.html">Đăng ký</a> thành viên miễn phí</div>

      </div>
      </form>      
</div>
</div>
<script language="javascript">
jQuery().ready(function() {

  jQuery("#frmLogin").validate({
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
                      required: true
                    }
          		},
                messages:
                {
                    email:
                    {
                      required: "Vui lòng nhập địa chỉ E-mail",
                      email: "Email sai. Vui lòng nhập lại địa chỉ email khác"
                    },
                    password:
                    {
                      required: "Vui lòng nhập mật khẩu"
                    }
                }
      	});
});
</script>
