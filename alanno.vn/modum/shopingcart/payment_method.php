
<table cellpadding="0" cellspacing="0" border="0" id="list_payment_methods" width="100%">
        <tbody>
         <?php
             $rst_payment_method=$dbf->getDynamic("payment_method","status=1","");

             while($row = $dbf->nextData($rst_payment_method))
             {
                $id_payment          = $row["id"];
                $title_payment       = stripcslashes($row["title"]);
                $description_payment = stripcslashes($row["description"]);

         ?>
        <tr>
			<td>
				<input type="radio" class="radio" name="payment_id" value="<?=$id_payment?>" <?=(($_SESSION["payment_id"]==$id_payment)?"checked='checked'":"")?>>
			</td>
			<td><label for="payment_method_12" class="strong"><?=$title_payment?></label></td>
			<td>&nbsp;</td>
			<td><?=$description_payment?></td>
		</tr>
        <?php } ?>


</tbody>
</table>
<?php

if(isset($URL[1]) && $URL[1]=='place-order')
{
?>

 <script language="JavaScript" type="text/javascript">
          /*<![CDATA[*/
           function kiemtraphieugiamgia(){
                  var maphieu = $('#magiamgia').val()
              	if(maphieu!='')
          		{
                      var url = "/phieugiamgia.php";
                      url+='?maphieu='+maphieu;
                      initRequest(url);
                      xmlRequest.open("GET", url, true);
                      xmlRequest.onreadystatechange = callback2;
                      xmlRequest.send(null);
          		}
                  else
                  {
                     $("#msg_maphieu").html("Vui lòng nhập mã phiếu giảm giá");
                  }
              }

             function callback2(){
          		if (xmlRequest.readyState == 4) {
          			if (xmlRequest.status == 200) {
                          var data = xmlRequest.responseText;
                          $("#msg_maphieu").html(data);
                          //alert(data);
                          //location.reload();
          			 } else if (xmlRequest.status == 204){
          				alert("Error! Phiếu khuyễn mãi không hợp lệ");
          			}
          		}

          	}
          /*]]>*/
          </script>

<fieldset style="padding: 10px 10px 10px; margin: 20px 0px 20px; border: 2px solid #F1F4F6">
    <legend style="font-size:14px;"><h2>Phiếu giảm giá</h2></legend>
    <div class="error" style="color: red;padding:0px 0px 5px" id="msg_maphieu"></div>
    <b>Mã phiếu</b>:<input type="text" name="magiamgia" id="magiamgia" />
    <input type="button" name="bt_kiemtra" value="Kiểm tra" onclick="kiemtraphieugiamgia()" />
</fieldset>   

 <input type="submit" name="cmdupdate_payment_method" id="cmdupdate_payment_method" value="<?=$words["CONTINUE_CHECKOUT"]?>" />
<?php
}

?>

