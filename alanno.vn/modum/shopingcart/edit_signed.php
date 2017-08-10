<?php echo $html->normalForm("frm_edit_signed",array("action"=>"checkout.html","method"=>"post"));?>
<div class="right_row1">E-mail</div>
<div class="right_row2">
  <input class="full" readonly type="text" maxlength="50" style="width:200px" onfocus="this.select();" value="<?php echo $member_info["email"]?>" name="email" id="email"  /><span class="txtdo">*</span>
</div>

<div class="clear" style="padding-top:10px"></div>
<div class="right_row1"><?=$words["FIRSTNAME"]?></div>
<div class="right_row2">
  <input class="full" maxlength="200" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["firstname"]?>" name="firstname" id="firstname"  /><span class="txtdo">*</span>
</div>
<div class="clear"></div>
<div class="right_row1"><?=$words["LASTNAME"]?></div>
<div class="right_row2">
  <input class="full" maxlength="200" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["lastname"]?>" name="lastname" id="lastname"  /><span class="txtdo">*</span>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["PHONE"]?></div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["phone"]?>" name="phone" id="phone"  />
</div>

<div class="clear"></div>

<input type="submit"  value="<?=$words["CONTINUE_CHECKOUT"]?>" name="cmdeditsigned" id="cmdeditsigned" />

<?php
  echo $html->closeForm();
?>

<script type="text/javascript">

$().ready(function() {
$("#frm_edit_signed").validate({
            debug: false,
            errorElement: "em",
            success: function(label) {
    				label.text("").addClass("success");
    		},
    		rules: {


              firstname:
              {
                required: true

              },

              lastname:
              {
                required: true

              }
    		}

	});
});

</script>