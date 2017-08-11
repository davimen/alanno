<div class="section_wrapper mcb-section-inner">
<div class="pro_c">
<?php

if( $_SESSION["Free"]==1)
{
  $html->redirectURL("/login.html");

}else
{

        $rstgetInfo=$dbf->getDynamic("member","status=1 and id='".$_SESSION["member_id"]."'","");
        $rowgetInfo=$dbf->nextObject($rstgetInfo);

        $email=$rowgetInfo->email;
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
        if($state!='other')
        {
            $infostate = $dbf->getInfoColum("shipping_method",$state);
            $state_name = $infostate["title"];
        }else
        {
            $state_name = $state;
        }
        $postcode   = stripslashes($rowgetInfo->postcode);

?>
<form action="/register/<?php echo md5("edit".date("dmYH"))?>" method="post" class="jNice" name="frminfo" id="frminfo">

<div class="pro_c">
<div class="contentMain_info">

<div class="right_row1">Email</div>
<div class="right_row2">
    <?php echo $email?>
</div>
<div class="clear"></div>

<div class="right_row1"><?=$words["PASSWORD"]?></div>
<div class="right_row2">
     <a href="change-password.html"> <img src="style/images/schnap/edit.gif" width="16" height="16" alt="Change password" border="0" /></a>
</div>
<div class="clear"></div>

<div class="right_row1"><?=$words["FIRSTNAME"]?></div>
<div class="right_row2">
    <?php echo $firstname?>
</div>
<div class="clear"></div>
<div class="right_row1"><?=$words["LASTNAME"]?></div>
<div class="right_row2">
  <?php echo $lastname?>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["ADDRESS"]?></div>
<div class="right_row2">
  <?php echo $address?>
</div>


<div class="clear"></div>
<div class="right_row1"><?=$words["PHONE"]?></div>
<div class="right_row2">
  <?php echo $phone?>
</div>

<div class="clear"></div>
<div class="right_row1">FAX</div>
<div class="right_row2">
  <?php echo $fax?>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["STATE"]?></div>
<div class="right_row2">
  <?php echo $state_name?>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["POSTCODE"]?></div>
<div class="right_row2">
  <?php echo $postcode?>
</div>

<div class="clear"></div>
<div class="right_row1"><?=$words["COUNTRY"]?></div>
<div class="right_row2">
<?php
      $infocountry = $dbf->getInfoColum("countries",$country_id);
      echo $infocountry["countries_name"];
?>
</div>

</div>

<div class="clear" style="padding-top:5px"></div>

<div class="contentMain">
<div class="right_row2">
    <input type="submit" value="<?=$words["EDITINFOACCOUNT"]?>" name="cmdeditaccout" />
</div>
</div>

<div class="clear"></div>
<div id="clear" style="padding-top:10px"></div>
</div>
<div class='box_bottom_main'></div>
</form>
<?php
}
?>
</div>