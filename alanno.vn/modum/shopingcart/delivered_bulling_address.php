<?php

//kiem tra toi tai Shipping address chua
$rstcheck_billing=$dbf->getDynamic("shipping_address","member_id=".$_SESSION["member_id"]."","");
$total_check_billing = $dbf->totalRows($rstcheck_billing);
if($total_check_billing==0)
{
  $array_col=array("member_id"=>$_SESSION["member_id"],"address"=>$member_info["address"],"firstname"=>$member_info["firstname"],"lastname"=>$member_info["lastname"],"phone"=>$member_info["phone"],"fax"=>$member_info["fax"],"home_flat_number"=>$member_info["home_flat_number"],"street_number"=>$member_info["street_number"],"street_name"=>$member_info["street_name"],"country_id"=>$member_info["country_id"],"state"=>$member_info["state"],
  "postcode"=>$member_info["postcode"],"status"=>1,"datecreated"=>time(),"dateupdated"=>$time);
   //print_r($array_col);
  $dbf->insertTable("shipping_address",$array_col);
 ?>
                    <p><b><?=$member_info["firstname"]?>&nbsp;<?=$member_info["lastname"]?></b><br/> 
                    <?=$words["ADDRESS"]?>: <b><?=$member_info["address"]?></b> <br/>
                    <?=$words["PHONE"]?>: <b><?=$member_info["phone"]?></b> <br/>
                    Fax: <b><?=$member_info["fax"]?></b> <br/>
                    <?=$words["STATE"]?>: <b><?=$state_info["title"]?></b> <br/>
                    <?=$words["POSTCODE"]?>: <b><?=$member_info["postcode"]?></b> <br/>
                    <?=$words["COUNTRY"]?>: <b><?=$country_info["countries_name"]?></b> <br/>
                    </p>
                    <br class="clear" />
 <?php
}
else
{
                    $shipping_address_delivered = $dbf->getInfoColumShipping("shipping_address",$_SESSION["member_id"]);
                    if($shipping_address_delivered["state"]!='other')
                     {
                        $shipping_address_state_info = $dbf->getInfoColum("shipping_method",$shipping_address_delivered["state"]);
                     }else
                     {
                        $shipping_address_state_info["title"]="Other";
                     }
                    $shipping_address_country_info = $dbf->getInfoColum("countries",$shipping_address_delivered["country_id"]);
   /*$array_col=array("firstname"=>$member_info["firstname"],"lastname"=>$member_info["lastname"],"phone"=>$member_info["phone"],"fax"=>$member_info["fax"],"home_flat_number"=>$member_info["home_flat_number"],"street_number"=>$member_info["street_number"],"street_name"=>$member_info["street_name"],"country_id"=>$member_info["country_id"],"state"=>$member_info["state"],
"postcode"=>$member_info["postcode"],"status"=>1,"dateupdated"=>time());
   //print_r($array_col);
   //$dbf->updateTable("shipping_address",$array_col,"member_id='".$_SESSION['member_id']."'");
   */
?>
                   <p><b><?=$shipping_address_delivered["firstname"]?>&nbsp;<?=$shipping_address_delivered["lastname"]?></b><br/>

                    <?=$words["ADDRESS"]?>: <b><?=$shipping_address_delivered["address"]?></b> <br/>

                    <?=$words["PHONE"]?>: <b><?=$shipping_address_delivered["phone"]?></b> <br/>
                    Fax: <b><?=$member_info["fax"]?></b> <br/>
                    <?=$words["STATE"]?>: <b><?=$shipping_address_state_info["title"]?></b> <br/>
                    <?=$words["POSTCODE"]?>: <b><?=$shipping_address_delivered["postcode"]?></b> <br/>
                    <?=$words["COUNTRY"]?>: <b><?=$shipping_address_country_info["countries_name"]?></b> <br/>
                    </p>
                    <br class="clear" />
<?php
}
?>