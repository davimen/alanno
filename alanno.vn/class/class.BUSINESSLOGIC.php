<?php
include str_replace('\\', '/', dirname(__FILE__)) . '/class.DBFUNCTION.php';
class BUSINESSLOGIC extends DBFUNCTION {
  /*******************************************************************/
  function checkPage($page) {
    if ($page != 'trang-chu' && $page!='gioi-thieu' && $page!='dich-vu' && $page!='san-pham' && $page!='dat-hang' && $page!='tin-tuc' && $page!='thu-vien-anh' && $page!='dich-vu' && $page!='lien-he' && $page!='tim-kiem' && $page!='sitemap' && $page!='error404')
      $page = 'trang-chu';
    return $page;
  }

function getwords($lang, $arrayLang) {
    $langWords = array();
    $arrayLang = $this->getArray("language", "status=1", "");

    if (is_array($arrayLang))
      list($key, $value) = each($arrayLang);

  switch ($lang) {
      case "vn" :
            $value='1';
            break;
      case "en" :
            $value='2';
            break;
      default :
            $value='1';
   }

    $array = $this->getArray("words", "status=1 and language_id='" . $value. "'", "");
    //print_r($array);
    if (is_array($array))
      foreach ($array as $key => $value)
        $langWords[$value["keyname"]] = $value["value"];
    return $langWords;
    //print_r($langWords);
  }

   function price($price){
        $str = number_format($price,0,",",".");
        return $str;
   }

 function signout() {
      $_SESSION["member_id"]          = "";
      $_SESSION["member_email"]       = "";
      $_SESSION["member_firstname"]   = "";
      $_SESSION["member_lastname"]    = "";	 
      $_SESSION["Free"]=1;	  
      echo "<script type='text/javascript'>window.location='/';</script>";
  }
  /********************************************************************/
  function Button($idName, $arrayOption) {
    return "<table border='0' cellpadding='0' cellspacing='0' ALPHA8>
    					<tr><td class='btnleftSearch'></td>
						<td>" . $this->input($idName, $arrayOption) . "</td>
    					<td class='btnrightSearch'></td>
    			</tr>
    	</table>";
  }
  /********************************************************************/
  function showFlashBanner() {
    $catid_banner=16;
    $rst = $this->getDynamic("banner", "banner_category_id= 16 and status=1", "position asc");
    $str = "<playlist version='1' xmlns='http://xspf.org/ns/0/'>\n";
    $str .= "<trackList>\n";
    while ($row = $this->nextData($rst)) {
      $str .= "<track>\n";
      $str .= "<location>" .HOST.stripslashes($row['picture']) . "</location>\n";
      $str .= "<info>" . stripslashes($row['url']) . "</info>";
      $str .= "\n</track>\n";
    }
    $str .= "</trackList>\n</playlist>";
    return $str;
  }

  /*******************************************************************/
  function takeShortText($longText, $numWords) {
    $ret = "";
    if ($longText != "") {
      $longText = trim($longText);
      $longText = stripslashes($longText);
      $longText = strip_tags($longText);
      if (str_word_count($longText) > $numWords) {
        $arrayText = explode(" ", $longText);
        for ($i = 0; $i < $numWords; $i++) {
          $ret .= $arrayText[$i] . " ";
        }
        $ret = trim($ret) . "... ";
        return $ret;
      }
      else {
        return $longText;
      }
    }
  }


  function make_protect_image($image="",$font="",$protect="",$color="#000000",$size=15) {
        if(eregi("([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})", $color, $ca)) {
			$red = hexdec($ca[1]);
			$green = hexdec($ca[2]);
			$blue = hexdec($ca[3]);
        }
        $pass = "";
		$chars = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
		$count = count($chars) - 1;
		srand((double) microtime() * 1000000);
		for ($i = 0; $i < 5; $i++)
			$pass .= $chars[rand(0, $count)];
		$img = imagecreatefromjpeg($image);
		$text_color = imagecolorallocate($img, $red, $green, $blue);
		$w = 10;
		srand((double) microtime() * 1000000);
		for ($i = 0; $i < strlen($pass); $i++) {
			$a = rand(45, -45);
			$t = substr($pass, $i, 1);
			imagettftext($img,$size,$a,$w,30,$text_color,$font,$t);
			$w = $w + 15;
		}
		imagejpeg($img, $protect);
		@ imagedestroy($img);
		return ($pass);
	}

 /*
  *******************************************************************/
  function getConfig() {
    $result = $this->getDynamic("setting", "", "id asc limit 0,1");
    $info = array();
    $info = $this->nextData($result);
    return $info;
  }

  function showComment($id)
  {
    $str='';
    $rs_comment=$this->getDynamic("product_comment","product_id=".$id." and status=1","datecreated desc");
    $total=$this->totalRows($rs_comment);
    if($total){
    $i=1;
    echo "oke";
    while($row_comment=$this->nextData($rs_comment)){
        $id=stripcslashes($row_comment["id"]);
        $title=stripcslashes($row_comment["title"]);
        $content=stripcslashes($row_comment["content"]);
        $email=stripcslashes($row_comment["email"]);
        $datecreated=date("d-m-Y",$row_comment["datecreated"]);
        $str.='<p style="font-size:11.5px;margin:0px; padding:0px;">'.$content.'</p>';
        $str.='<span style="text-align:right;float:right; padding-right:10px;font-size:11.5px"><b>'.$title.'</b> ('. $datecreated.')</span>';
        $str.='<br class="clear"/>';
        if($i!=$total)
            $str.='<div style="border-bottom:1px solid #D2D4D5;margin-bottom:10px;overflow:hidden;"></div>';
        $i++;
    }
    }
    return $str;
  }

   function showtitle($page,$URL){
             switch($page)
              {
                 case "trang-chu" :
                 case "intro" :
                        break;

                 case "ket-qua-tim-kiem" :
                        echo "Kết quản tìm kiếm | ";
                        break;

                  case "site-map" :
                        echo "Sitemaps | ";
                        break;

                  case "lien-he" :
                        echo "Liên hệ | ";
                        break;

                  case "giam-gia" :
                        echo "Giảm giá | ";
                        break;

                  case "hang-san-xuat" :
                        echo "Hãng sản xuất | ";
                        break;
                  case "alphabet" :
                        echo "Alphabet | ";
                        break;
                  case "login" :
                        echo "Login | ";
                        break;
                  case "register" :
                        echo "Register | ";
                        break;
                  case "account" :
                        echo "Account | ";
                        break;
                  case "change-password":
                        echo "Change password | ";
                        break;
                  case "forgot-password":
                        echo "Forgot password | ";
                        break;

                  case "checkout":
                        echo "Checkout |";
                        break;
                        
                  case "checkout-complete":
                        echo "Checkout complete |";
                        break;

                  case "custormer-checkout":
                        echo "Custormer checkout |";
                        break;

                   case "custormer-checkout-step2":
                        echo "Custormer checkout |";

                        break;




                  default :
                           if(isset($URL[2]) && $URL[2]!='')
                            echo $this->getTitlecat_type("article",$URL[2])." | ";
                          if(isset($URL[1]) && $URL[1]!='')
                            echo $this->getTitlecat_type("article_category",$URL[1])." | ";

                          echo $this->getTitlecat_type("article_category",$page)." | ";
                        break;
              }
          }
  /*
  /*
  *******************************************************************/
  function displaySelect($arrayTarget, $target, $idName, $arrayOption = null) {
    $str = "<select onchange=\"" . $arrayOption["onchange"] . "\" name='$idName' id='$idName' size='" . $arrayOption['size'] . "' class='" . $arrayOption['class'] . "' style=\"" . $arrayOption["style"] . "\">";
    if ($arrayOption["firstText"] != "")
      $str .= "<option value='0' >-- " . $arrayOption["firstText"] . " --</option>";
    foreach ($arrayTarget as $key => $value) {
      $str .= "<option value='" . $key . "' " . (($target == $key) ? "selected" : "") . ">" . $value . "</option>";
    }
    $str .= "</select>";
    return $str;
  }
  /*
  Paging on one table
  *******************************************************************/
  function paging($tablename, $where, $orderby, $url, $PageNo, $PageSize, $Pagenumber, $ModePaging) {
    if ($PageNo == "") {
      $StartRow = 0;
      $PageNo = 1;
    }
    else
      $StartRow = ($PageNo - 1) * $PageSize;
    if ($PageSize < 1 || $PageSize > 50)
      $PageSize = 15;
    if ($PageNo % $Pagenumber == 0)
      $CounterStart = $PageNo - ($Pagenumber - 1);
    else
      $CounterStart = $PageNo - ($PageNo % $Pagenumber) + 1;
    $CounterEnd = $CounterStart + $Pagenumber;
    $TRecord = $this->getArray($tablename, $where, $orderby, "stdObject");
    $RecordCount = count($TRecord);
    $result = $this->getDynamic($tablename, $where, $orderby . " LIMIT " . $StartRow . "," . $PageSize);
    if ($RecordCount % $PageSize == 0)
      $MaxPage = $RecordCount / $PageSize;
    else
      $MaxPage = ceil($RecordCount / $PageSize);
    $gotopage = "";
    switch ($ModePaging) {
      case "Full" :
        $gotopage = '<div class="paging_meneame">';
        if ($MaxPage > 1) {
          if ($PageNo != 1) {
            $PrevStart = $PageNo - 1;
            $gotopage .= ' <a href="' . $url . '&PageNo=1" tile="First page"> &laquo; </a>';
            $gotopage .= ' <a href="' . $url . '&PageNo=' . $PrevStart . '" title="Previous page"> &lsaquo; </a>';
          }
          else {
            $gotopage .= ' <span class="paging_disabled"> &laquo; </span>';
            $gotopage .= ' <span class="paging_disabled"> &lsaquo; </span>';
          }
          $c = 0;
          for ($c = $CounterStart; $c < $CounterEnd;++$c) {
            if ($c <= $MaxPage)
              if ($c == $PageNo)
                $gotopage .= '<span class="paging_current"> ' . $c . ' </span>';
              else
                $gotopage .= ' <a href="' . $url . '&PageNo=' . $c . '" title="Page ' . $c . '"> ' . $c . ' </a>';
          }
          if ($PageNo < $MaxPage) {
            $NextPage = $PageNo + 1;
            $gotopage .= ' <a href="' . $url . '&PageNo=' . $NextPage . '" title="Next page"> &rsaquo; </a>';
          }
          else {
            $gotopage .= ' <span class="paging_disabled"> &rsaquo; </span>';
          }
          if ($PageNo < $MaxPage)
            $gotopage .= ' <a href="' . $url . '&PageNo=' . $MaxPage . '" title="Last page"> &raquo; </a>';
          else
            $gotopage .= ' <span class="paging_disabled"> &raquo; </span>';
        }
        $gotopage .= ' </div>';
        break;
    }
    $arr[0] = $result;
    $arr[1] = $gotopage;
    return $arr;
  }


  function getidCat($table,$value){
    $result=$this->getDynamic($table,"id=".$value,"");
       if($this->totalRows($result))
       {
         $row=$this->nextData($result);
         return $row["parentid"];
       }
  }

  function getInfoColum($table,$value){
    $result=$this->getDynamic($table,"id=".$value,"");
       if($this->totalRows($result))
       {
         $row=$this->nextData($result);
         return $row;
       }
  }

  function getInfoColumType($table,$value){
    $result=$this->getDynamic($table,"title_rewrite='".$value."'","");
       if($this->totalRows($result))
       {
         $row=$this->nextData($result);
         return $row;
       }
  }

  function getidCat_type($table,$value){
    $result=$this->getDynamic($table,"title_rewrite='".$value."' and parentid=0","");
       if($this->totalRows($result)>0)
       {
         $row=$this->nextData($result);
         return $row["id"];
       }else
       {
         return -1;
       }
  }

  function getTitlecat($table,$id){
      $result=$this->getDynamic($table,"id=".$id,"");
       if($this->totalRows($result))
       {
         $row=$this->nextData($result);
         return $row["title"];
       }
  }



  function getInfoColumShipping($table,$id){
      $result=$this->getDynamic($table,"member_id='".$id."'","");
       if($this->totalRows($result))
       {
         $row=$this->nextData($result);
         return $row;
       }
  }

  function getidcatPro($id){
       $result=$this->getDynamic("article_category","parentid=".$id,"");
       $mangid='';
       if($this->totalRows($result))
       {
         while($row=$this->nextData($result)){
             $mangid.=$row['id'].',';
             //lay cap 2
             $result2=$this->getDynamic("article_category","parentid=".$row['id'],"");
             if($this->totalRows($result2))
             {
               while($row2=$this->nextData($result2)){
                   $mangid.=$row2['id'].',';
                   // lay cap 3
                   $result3=$this->getDynamic("article_category","parentid=".$row2['id'],"");
                   if($this->totalRows($result3))
                   {
                     while($row3=$this->nextData($result3)){
                         $mangid.=$row3['id'].',';
                         // lay cap 4
                         $result4=$this->getDynamic("article_category","parentid=".$row3['id'],"");
                         if($this->totalRows($result4))
                         {
                           while($row4=$this->nextData($result4)){
                               $mangid.=$row4['id'].',';
                           }
                         }
                     }
                   }
               }
             }
       }
       return $mangid;
  }
  }

  function getTitlecat_type($table,$type){
       $result=$this->getDynamic($table,"title_rewrite='".$type."'","");
       if($this->totalRows($result))
       {
         $row=$this->nextData($result);
         return $row["title"];
       }
  }

  function getcatTitle_rewrite($table,$id){
      $result=$this->getDynamic($table,"id=".$id,"");
       if($this->totalRows($result))
       {
         $row=$this->nextData($result);
         return stripcslashes($row["title_rewrite"]);
       }
  }

  function getDescriptioncat($table,$id){
      $result=$this->getDynamic($table,"id=".$id,"");
       if($this->totalRows($result))
       {
         $row=$this->nextData($result);
         return stripcslashes($row["description"]);
       }
  }


    // Get: Ip
            function get_ip()
            {
            if(isset($_SERVER['X_FORWARDED_FOR'])){
            if(strpos($_SERVER['X_FORWARDED_FOR'], ',') === false){
            return $_SERVER['X_FORWARDED_FOR'];
            }
            return trim(reset(explode(',', $_SERVER['X_FORWARDED_FOR'])));
            }
            return $_SERVER['REMOTE_ADDR'];
            }
    /****************************************************/

            // Get: Ban List
          function banlist(){
          global $page, $arrLayout, $banlist;
          if(!isset($banlist)) {
          $banlist = 0;
          $filepath = 'modum/banlist.txt';
          $find = '/'.@file_get_contents($filepath).'|^$/i';
          $condition = $page.$this->get_ip().$_SERVER['HTTP_USER_AGENT'];
          if(preg_match($find,$condition))
                $banlist = 1;
          else $banlist= 0;
          }
          return $banlist;
          }

        function insertTable_Request($tbName,$arrayValue=array()) {
              $arrayValue = array_merge($_SESSION,$_REQUEST,$arrayValue);
              $Meta = $this->getMetaData($tbName);
              $Field = $Meta['Field'];
              foreach($Field as $field){
              $temp[] = $field['Field'];
              }
              $Field=$temp;

              foreach($arrayValue as $key => $value){
              if(!in_array($key,$Field)) unset($arrayValue[$key]);
              }
              unset($arrayValue['id']);
              return $this->updateTable($tbName,$arrayValue,'','INSERT');
        }

  /* 	Visitor *******************************************************************/
		function visitors() {

            $_SESSION['current_layout_id'] = 50;
			$exc =$this->doSQL("SELECT * FROM log_counter WHERE layout_id=".$_SESSION['current_layout_id']);
			$_exits = $this->totalRows($exc);
			if($_exits <= 0) {
				$ins = $this->insertTable('log_counter',array('hit_counterall'	=>	0,
															   'hit_coutermonth'=>	0,
															   'hit_counterweek'=>	0,
															   'hit_coutertoday'=>	0,
															   'hit_day'		=>	date("j"),
                                                               'layout_id'		=>	$_SESSION['current_layout_id']
														));
			}

			if (empty($_SESSION['hit']) && !$this->banlist())
			{
                $counter_date=date("j");
				$xet_date = $this->getValueOfQuery('SELECT hit_day FROM log_counter WHERE layout_id='.$_SESSION['current_layout_id']);

                if($counter_date==$xet_date) {
					$sql_visitor_counter = "update log_counter set hit_counterall = hit_counterall+1,
															  hit_coutermonth=hit_coutermonth+1,
															  hit_counterweek=hit_counterweek+1,
															  hit_coutertoday=hit_coutertoday+1,
															  hit_day='".$counter_date."' where layout_id=".$_SESSION['current_layout_id'];
				} else {

					if($counter_date == 1) {
						$sql_visitor_counters = "update log_counter set hit_coutermonth=1 where layout_id=".$_SESSION['current_layout_id'];
                        $result = $this->doSQL($sql_visitor_counters);
					}
                    $t= date('N');

					if(date('N')==1) {
						$sql_visitor_counters = "update log_counter set hit_counterweek=1,hit_coutertoday=1 where layout_id=".$_SESSION['current_layout_id'];
                        $result = $this->doSQL($sql_visitor_counters);
					}


					$chuyen_date = $this->getValueOfQuery('select hit_coutertoday from log_counter where layout_id='.$_SESSION['current_layout_id']);
					$sql_visitor_counter = "update log_counter set hit_counterall = hit_counterall+1,
															  hit_coutermonth=hit_coutermonth+1,
															  hit_counterweek=hit_counterweek+1,
															  hit_couteryesterday=".$chuyen_date.",
															  hit_coutertoday=1,
															  hit_day='".$counter_date."' where layout_id=".$_SESSION['current_layout_id'];
				}


				$result = $this->doSQL($sql_visitor_counter);
				$_SESSION['hit'] = "done";
			}

			$visitor['today']=$this->getValueOfQuery('select hit_coutertoday from log_counter where layout_id='.$_SESSION['current_layout_id']);
			$visitor['yesterday']=$this->getValueOfQuery('select hit_couteryesterday from log_counter where layout_id='.$_SESSION['current_layout_id']);
			$visitor['week']=$this->getValueOfQuery('select hit_counterweek from log_counter where layout_id='.$_SESSION['current_layout_id']);
			$visitor['month']=$this->getValueOfQuery('select hit_coutermonth from log_counter where layout_id='.$_SESSION['current_layout_id']);
			$visitor['number']=$this->getValueOfQuery('select hit_counterall from log_counter where layout_id='.$_SESSION['current_layout_id']);

		   /* $table = "log_visitorsonline";	// Your Table of choice, ex. "online_users"

			if ($Session_name == "")
			{
				session_name("$Session_name");
				session_start("$Session_name");
			}

			$SID = session_id();
			$time = time();
			$dag = date("z");
			$nu = time() - 300;
			$ip = $_SERVER['REMOTE_ADDR'];
			$layout_id = $_SESSION['current_layout_id'];
			if(!$this->banlist()) {
				 // Check to see if the session_id is already registerd
				 $sidcheck = $this->doSQL("SELECT ip_address FROM $table WHERE ip_address='$ip' AND layout_id=".$_SESSION['current_layout_id']);
				 $sid_check = intval($this->totalRows($sidcheck));

				 // If not, the session_id will be stored in MySQL
				 if($sid_check == 0)
				 {
					$this->doSQL("INSERT INTO $table(SID,otime,oDay,ip_address,layout_id) VALUES ('$SID',$time,$dag,'$ip',$layout_id)");
				 }
				 else 	// If it is, it will register a new time to the session.
				 {
					$this->doSQL("UPDATE $table SET oTime=$time WHERE ip_address='$ip' AND layout_id = $layout_id");
				 }
			}
			 // This is it, this counts the users currently online
			 $exc =$this->doSQL("SELECT ip_address FROM $table WHERE oTime > $nu AND oDay = $dag AND layout_id = $layout_id GROUP BY ip_address");
			 $_online = $this->totalRows($exc);
			 // This deletes old ids, so your db will not get overloaded.
			 $this->doSQL("DELETE FROM $table WHERE oTime < $nu AND layout_id = $layout_id");
			 $this->doSQL("DELETE FROM $table WHERE oDay != $dag AND layout_id = $layout_id");
			 $visitor['online'] = ($_online>0)?$_online:1;
             */
			 return $visitor;
		}

         function getTypeArticle($id){
               $result=$this->getDynamic("artical_type","id=".$id,"");
                 if($this->totalRows($result))
                 {
                   $row=$this->nextData($result);
                   return $row["type"];
                 }
            }

            function getID_typePage($page){
               $result=$this->getDynamic("article_category","title_rewrite='".$page."'","");
                 if($this->totalRows($result))
                 {
                   $row=$this->nextData($result);
                   return $row["id"];
                 }
                 else
                 {
                   return -1;
                 }
            }

            function getTypeArticle_id($id){
               $result=$this->getDynamic("article_category","id=".$id,"");
                 if($this->totalRows($result))
                 {
                   $row=$this->nextData($result);
                   return $row["type_id"];
                 }
            }

            function getRewriteParent($id){
               $result=$this->getDynamic("article_category","id=".$id,"");
                 if($this->totalRows($result))
                 {
                   $row=$this->nextData($result);
                   return $row["title_rewrite"];
                 }
            }

         function getDanhmucpage2($value){
             $result=$this->getDynamic("article_category","id=".$value."","");
             if($this->totalRows($result)>0)
             {
               $row=$this->nextData($result);
               $parentid = $row["parentid"];
               if($parentid==0)
               {
                 return $row["id"];
               }
               else
               {
                   return $this->getDanhmucpage2($row["parentid"]);
               }
             }else
             {
               return -1;
             }
         }

         function getDanhmucpage($value){
             $result=$this->getDynamic("article_category","title_rewrite='".$value."'","");
             if($this->totalRows($result)>0)
             {
               $row=$this->nextData($result);
               $parentid = $row["parentid"];
               if($parentid==0)
               {
                 return $row["id"];
               }
               else
               {
                   return $this->getDanhmucpage2($row["parentid"]);
               }
             }else
             {
               return -1;
             }
         }
}
?>