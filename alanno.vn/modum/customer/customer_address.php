<style type="text/css">
/*<![CDATA[*/

/*]]>*/
</style>
<?php echo $html->normalForm("frmaddress",array("action"=>"custormer-checkout-step2.html","method"=>"post"));?>
<div class="address_colum">
<fieldset style="border: 0">
<legend>
   <b>Địa chỉ thanh toán</b>
</legend>
<div class="clear" style="padding-top:10px"></div>
<div class="right_row1"><?=$words["FIRSTNAME"]?></div>
<div class="right_row2">
  <input class="full" maxlength="200" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["firstname"]?>" name="firstname" id="firstname"  /><span class="txtdo">*</span>
  <div class="clear"></div>
</div>
<div class="clear"></div>
<div class="right_row1"><?=$words["LASTNAME"]?></div>
<div class="right_row2">
  <input class="full" maxlength="200" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["lastname"]?>" name="lastname" id="lastname"  /><span class="txtdo">*</span>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["ADDRESS"]?></div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["address"]?>" name="address" id="address"  />
  <span class="txtdo">*</span>
  <div class="clear"></div>
</div>


<div class="clear"></div>
<div class="right_row1"><?=$words["PHONE"]?></div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["phone"]?>" name="phone" id="phone"  />
  <span class="txtdo">*</span>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1">FAX</div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["fax"]?>" name="fax" id="fax"  />
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1">Quận/Tỉnh</div>
<div class="right_row2">
  <?php
     $rststate=$dbf->getDynamic("shipping_method","status=1","title asc");
      echo'<select name="state" id="state" size="0" class="" style="width:205px !important">
           <option value="">- Chọn Quận/Tỉnh -</option>';
      while($row = $dbf->nextData($rststate))
       {
          echo'<option '.(($member_info["state"]==$row['id'])?"selected":"").' value="'.$row['id'].'">'.$row['title'].'</option>';
       }
          echo'<option '.(($member_info["state"]=='other')?"selected":"").' value="other">Khác</option>';

      echo'</select>';
  ?>
  <span class="txtdo">*</span>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["POSTCODE"]?></div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $member_info["postcode"]?>" name="postcode" id="postcode"  />
  <span class="txtdo">*</span>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["COUNTRY"]?></div>
<div class="right_row2">
<?php
      $rstCountry=$dbf->getDynamic("countries","status=1","countries_name asc");
      echo'<select name="country_id" id="country_id" size="0" class="" style="width:205px !important">
           <option value="">- Chọn đất nước -</option>';
      while($row = $dbf->nextData($rstCountry))
       {

          if(isset($member_info["country_id"])&& $member_info["country_id"]!=0)
          {
          echo'<option '.(($country_id==$row['id'])?"selected":"").' value="'.$row['id'].'">'.$row['countries_name'].'</option>';
          }else
          {
          echo'<option '.(($row['id']==230)?"selected":"").' value="'.$row['id'].'">'.$row['countries_name'].'</option>';
          }
       }

      echo'</select>'
?>
     <span class="txtdo">*</span>
     <div class="clear"></div>
</div>
<div class="clear"></div>
</fieldset>
</div>

<div class="address_colum">
    <fieldset style="border: 0">
<legend>
   <b>Địa chỉ giao hàng</b>
</legend>
    <div id="sa">
        <?php
           $shipping_address = $dbf->getInfoColumShipping("shipping_address",$_SESSION["member_id"]);
        ?>
      <div class="clear" style="padding-top:10px"></div>
<div class="right_row1"><?=$words["FIRSTNAME"]?></div>
<div class="right_row2">
  <input class="full" maxlength="200" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $shipping_address["firstname"]?>" name="shipping_firstname" id="shipping_firstname"  /><span class="txtdo">*</span>
  <div class="clear"></div>
</div>
<div class="clear"></div>
<div class="right_row1"><?=$words["LASTNAME"]?></div>
<div class="right_row2">
  <input class="full" maxlength="200" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $shipping_address["lastname"]?>" name="shipping_lastname" id="shipping_lastname"  /><span class="txtdo">*</span>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["ADDRESS"]?></div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $shipping_address["address"]?>" name="shipping_address" id="shipping_address"  />
   <span class="txtdo">*</span>
  <div class="clear"></div>
</div>


<div class="clear"></div>
<div class="right_row1"><?=$words["PHONE"]?></div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $shipping_address["phone"]?>" name="shipping_phone" id="shipping_phone"  />
  <span class="txtdo">*</span>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1">FAX</div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $shipping_address["fax"]?>" name="shipping_fax" id="shipping_fax"  />
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1">Quận/Tỉnh</div>
<div class="right_row2">

  <?php
     $rststate=$dbf->getDynamic("shipping_method","status=1","title asc");
      echo'<select name="shipping_state" id="shipping_state" size="0" class="" style="width:205px !important">
           <option value="">- Chọn quận/tỉnh -</option>';
      while($row = $dbf->nextData($rststate))
       {
          echo'<option '.(($shipping_address["state"]==$row['id'])?"selected":"").' value="'.$row['id'].'">'.$row['title'].'</option>';
       }
          echo'<option '.(($shipping_address["state"]=='other')?"selected":"").' value="other">Khác</option>';

      echo'</select>';
  ?>
  <span class="txtdo">*</span>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["POSTCODE"]?></div>
<div class="right_row2">
  <input class="full" style="width:200px" type="text" onfocus="this.select();" value="<?php echo $shipping_address["postcode"]?>" name="shipping_postcode" id="shipping_postcode"  />
  <span class="txtdo">*</span>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["COUNTRY"]?></div>

<div class="right_row2">
<?php
      $rstCountry=$dbf->getDynamic("countries","status=1","countries_name asc");
      echo'<select name="shipping_country_id" id="shipping_country_id" size="0" class="" style="width:205px !important">
           <option value="">- Chọn đất nước -</option>';
      while($row = $dbf->nextData($rstCountry))
       {
          if(isset($country_id)&& $country_id!=0)
          {
          echo'<option '.(($country_id==$row['id'])?"selected":"").' value="'.$row['id'].'">'.$row['countries_name'].'</option>';
          }else
          {
          echo'<option '.(($row['id']==230)?"selected":"").' value="'.$row['id'].'">'.$row['countries_name'].'</option>';
          }
       }

      echo'</select>'
?>
     <span class="txtdo">*</span>
     <div class="clear"></div>
</div>
<div class="clear"></div>
    </div>
   </fieldset>
</div>
<div class="clear"></div>
<input type="submit" name="customer_address_checkout" id="customer_address_checkout" value="<?=$words["CONTINUE_CHECKOUT"]?>" />

<?php
  echo $html->closeForm();
?>
<script type="text/javascript">

$().ready(function() {
$("#frmaddress").validate({
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

              },

              address:
              {
                required: true,
              },

               phone:
              {
                required: true,
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


              shipping_firstname:
              {
                required: true

              },

              shipping_lastname:
              {
                required: true

              },

              shipping_address:
              {
                required: true,
              },

              shipping_address:
              {
                required: true,
              },

              shipping_country_id:
              {
                required: true

              },

              shipping_state:
              {
                required: true

              },

              shipping_postcode:
              {
                required: true

              }

        },
            messages:
            {
                firstname:
                {
                  required: "&nbsp;"

                },

                lastname:
                {
                  required: "&nbsp;"

                },

                address:
                {
                  required: "&nbsp;",
                },

                phone:
                {
                  required: "&nbsp;",
                },


                country_id:
                {
                  required: "&nbsp;"

                },

                state:
                {
                  required: "&nbsp;"

                },

                postcode:
                {
                  required: true

                },


                shipping_firstname:
                {
                  required: "&nbsp;"

                },

                shipping_lastname:
                {
                  required: "&nbsp;"

                },

                shipping_address:
                {
                  required: "&nbsp;",
                },

                 shipping_phone:
                  {
                    required: "&nbsp;",
                  },


                shipping_country_id:
                {
                  required: "&nbsp;"

                },

                shipping_state:
                {
                  required: "&nbsp;"

                },
                shipping_postcode:
                {
                  required: true

                }


            }

	});
});

</script>