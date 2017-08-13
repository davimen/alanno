<div class="section_wrapper mcb-section-inner">
<div class="pro_c">
<?php
if($_SESSION["Free"]==1)
{
	$html->redirectURL("login.html");
}

?>

<?php
    $msg="";
	if(isset($_POST["subchange"]))
	{
        $oldpassword=$_POST["oldpassword"];
		$oldpassword=md5($oldpassword);
		$newpassword=$_POST["newpassword"];
		$newpassword=md5($newpassword);
		$confirmpassword=$_POST["confirmpassword"];
		$confirmpassword=md5($confirmpassword);
		$code=strtoupper($_POST["captcha"]);
		if($code!=$_SESSION["captcha_id"])
		{
			$msg="Mã bảo vệ sai";
		}else
		{
	        $rstcheck=$dbf->getDynamic("member","id='".$_SESSION["member_id"]."' and password='".$oldpassword."'","");
			if($dbf->totalRows($rstcheck)>0)
			{
				$affect=$dbf->updateTable("member",array("password"=>$newpassword),"id='".$_SESSION["member_id"]."'");
                $html->redirectURL("/change-password/success/");
				//$msg="Tài khoản đã được cập nhật";
			}else
			{
			    $html->redirectURL("/change-password/error/");
				//$msg="Tài khoản không tồn tại";
			}
		}
	}

$char = strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'), 0, 4));
$strcaptcha = rand(1, 7) . rand(1, 7) . $char;
if(!isset($_SESSION['captcha_id']) && $_SESSION['captcha_id']=="")
{
	$_SESSION['captcha_id'] = strtoupper($strcaptcha);	
}
?>
<?php
 echo $html->normalForm("frmchange",array("class"=>"","action"=>"","method"=>"post"));
?>  
   <div class="pro_c">   
    <?php
        if($URL[1]=='success')
        {
          echo'<div style="text-align:left;padding-left:15px"><span class="saodo">'.$words["ALERTCHANGEPASSSUCCESS"].'</span></div>';
        }

        if($URL[1]=='error')
        {
           echo'<div style="text-align:left;padding-left:15px"><span class="saodo">'.$words["ALERTCHANGEPASSERROR"].'</span></div>';
        }
    ?>

    <div id="clear"></div>
    <div class="productall1">
    <div class="clear" style="padding-top:5px;"></div>
	<div class="right_row1"><?=$words["PASSWORDOLD"]?></div>
	<div class="right_row2">
	    <input class="full" type="password" maxlength="30" onFocus="this.select()" name="oldpassword" id="oldpassword"/><span class="saodo">*</span>
	</div>
	<div class="clear"></div>
	<div class="right_row1"><?=$words["PASSWORDNEW"]?></div>
	<div class="right_row2">
	    <input class="full" type="password" maxlength="30" onFocus="this.select()" name="newpassword" id="newpassword"/><span class="saodo">*</span>
	</div>
	<div class="clear"></div>
	<div class="right_row1"><?=$words["REPASSWORDNEW"]?></div>
	<div class="right_row2">
	    <input class="full" type="password" maxlength="30" onFocus="this.select()" name="confirmpassword" id="confirmpassword"/><span class="saodo">*</span>
	</div>
    <div class="clear"></div>
    </div>
    <div class="clear"></div>

	<div class="clear" style="padding-top:7px;"></div>    
    <div class="productall1">
	<div class="clear" style="padding-top:5px;"></div>
	<div class="right_row1"><?=$words["SECURITYCODE"]?></div>
	<div class="right_row2">
        <div id="captchaimage"><?php echo $_SESSION['captcha_id'];?></div>
        <input type="text" maxlength="10" name="captcha" id="captcha" onfocus="this.select()" class="inputCode" /><span class="saodo">*</span>
	</div>

	<div class="clear"></div>
        <div class="right_row1"></div>
      	<div class="right_row2">
      	<input type="submit" value="Submit" name="subchange" id="subchange"  />
        <input type="button" value="<?=$words["CANCAL"]?>" name="butback" id="butback"  />
      	</div>

    <div class="clear"></div>
    </div>
 </div>
 <div class='box_bottom_main'></div>

<?php echo $html->closeForm();?>

<script language="javascript">

jQuery("#butback").click( function(){
  window.location.href='/account.html';
});

jQuery().ready(function() {
jQuery("#frmchange").validate({
            debug: false,
            errorElement: "em",
            success: function(label) {
    				label.text("").addClass("success");
    		},
    		rules: {
              oldpassword:
              {
                required: true,
                minlength: 6,
                maxlength: 30
              },
              newpassword:
              {
                required: true,
                minlength: 6,
                maxlength: 30
              },
              confirmpassword:
              {
                required: true,
                minlength: 6,
                maxlength: 30,
                equalTo: "#newpassword"
              },

              captcha:
              {
                required: true,
                remote: "/captchaprocess.php"
              }
    		},

              messages:
              {

                    oldpassword:
                    {
                      required: "Vui lòng nhập mật khẩu cũ",
                      minlength: "Mật khẩu nhiều hơn 6 ký tự",
                      maxlength: "Mật khẩu dài tối đa 30 ký tự"
                    },
                    newpassword:
                    {
                      required: "Vui lòng nhập mật khẩu mới",
                      minlength: "Mật khẩu nhiều hơn 6 ký tự",
                      maxlength: "Mật khẩu dài tối đa 30 ký tự"
                    },
                    confirmpassword:
                    {
                      required: "Vui lòng nhập lại mật khẩu mới",
                      minlength: "Mật khẩu nhiều hơn 6 ký tự",
                      maxlength: "Mật khẩu dài tối đa 30 ký tự",
                      equalTo: "Không trùng khớp với mật khẩu trên"
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


