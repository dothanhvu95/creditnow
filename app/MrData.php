<?php
namespace App;
class MrData
{
	public function __construct()
	{
		
	}
	public function Html($string)
	{
		$string=str_replace('"', '', $string);
		return html_entity_decode($string,null,"UTF-8");
	}
	public static function htmlDecode($string)
	{
		return html_entity_decode($string,null,"UTF-8");
	}
	
	public function ucfirst_lower($string){
		return	ucfirst(mb_strtolower($string, "UTF-8"));
	}
	public function getListDay(){
		$week=array("Sunday"=>"Chủ nhật","Monday"=>"Thứ 2","Tuesday"=>"Thứ 3","Wednesday"=>"Thứ 4","Thursday"=>"Thứ 5","Friday"=>"Thứ 6","Saturday"=>"Thứ 7");
			$result=array();
			$today=date("d/m/Y");
			$result[$today]="Hôm nay ( $today )";
			for($i=1;$i<8;$i++){
				$next_day= date("Y-m-d",strtotime("+".$i." days") );
				$result[$next_day]= $week[ date("l", strtotime($next_day))]."    (".date("d/m/Y",strtotime($next_day)).")";
			}
			
			return $result;
			
	}
	public function getWeek($date){
		$week=array("sun"=>"Chủ nhật","mon"=>"Thứ 2","tue"=>"Thứ 3","wed"=>"Thứ 4","thu"=>"Thứ 5","fri"=>"Thứ 6","sat"=>"Thứ 7");
		$x=date("D",strtotime($date));
		foreach ($week as $key=>$value){
			if(strtolower($x)==strtolower($key)){
				return $value;
			}
		}
		return '';
	}

	
	public function htmlEncode($string)
	{
		return htmlentities($string,null,"UTF-8");
	}
	public static function filterData($string) {
		$string = preg_replace('#<script.*?</script>#s', '', $string);
		$string = preg_replace('#<\?.*?\?>#s', '', $string);
		$string = str_replace("\\'", "", $string);
		$string = str_replace('\"', "", $string);
        
        //$string = str_replace('%', "", $string);
		//$string = str_replace(' ', "", $string);
        //$string = str_replace('$', "", $string);
        //$string = str_replace('&', "", $string);
        //$string = str_replace('*', "", $string);
        //$string = str_replace('#', "", $string);
		
		$string = MrData::inject($string);
		$string = MrData::htmlDecode($string);
		return $string;
	}
	public function asignData($param)
	{
		$data = array();
		
		foreach ($param as $key => $value) {
			if(is_string($value))
			$data[$key] = MrData::filterData($value);
			else 
			{
				$data[$key] = $value;
			}
		}
		return $data;
	}
	public function asignField($field)
	{
		return MrData::filterData(MrData::htmlEncode(MrData::stripTags($field)));
	}
	public static function tranferData($string)
	{
		$trans = array(
					'à'=>'a','á'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a',
					'ă'=>'a','ằ'=>'a','ắ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
					'â'=>'a','ầ'=>'a','ấ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',
					'À'=>'a','Á'=>'a','Ả'=>'a','Ã'=>'a','Ạ'=>'a',
					'Ă'=>'a','Ằ'=>'a','Ắ'=>'a','Ẳ'=>'a','Ẵ'=>'a','Ặ'=>'a',
					'Â'=>'a','Ầ'=>'a','Ấ'=>'a','Ẩ'=>'a','Ẫ'=>'a','Ậ'=>'a',    
					'đ'=>'d','Đ'=>'d',
					'è'=>'e','é'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e',
					'ê'=>'e','ề'=>'e','ế'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
					'È'=>'e','É'=>'e','Ẻ'=>'e','Ẽ'=>'e','Ẹ'=>'e',
					'Ê'=>'e','Ề'=>'e','Ế'=>'e','Ể'=>'e','Ễ'=>'e','Ệ'=>'e',
					'ì'=>'i','í'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',
					'Ì'=>'i','Í'=>'i','Ỉ'=>'i','Ĩ'=>'i','Ị'=>'i',
					'ò'=>'o','ó'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o',
					'ô'=>'o','ồ'=>'o','ố'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
					'ơ'=>'o','ờ'=>'o','ớ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',
					'Ò'=>'o','Ó'=>'o','Ỏ'=>'o','Õ'=>'o','Ọ'=>'o',
					'Ô'=>'o','Ồ'=>'o','Ố'=>'o','Ổ'=>'o','Ỗ'=>'o','Ộ'=>'o',
					'Ơ'=>'o','Ờ'=>'o','Ớ'=>'o','Ở'=>'o','Ỡ'=>'o','Ợ'=>'o',
					'ù'=>'u','ú'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u',
					'ư'=>'u','ừ'=>'u','ứ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
					'Ù'=>'u','Ú'=>'u','Ủ'=>'u','Ũ'=>'u','Ụ'=>'u',
					'Ư'=>'u','Ừ'=>'u','Ứ'=>'u','Ử'=>'u','Ữ'=>'u','Ự'=>'u',
					'ỳ'=>'y','ý'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
					'Y'=>'y','Ỳ'=>'y','Ý'=>'y','Ỷ'=>'y','Ỹ'=>'y','Ỵ'=>'y'
				  );
  		$string = strtr(MrData::htmlDecode($string), $trans);
  		$string = MrData::filterData($string);


  		return $string;		
	}

	public  function formod($a)
	{
		
		if(!empty($a)){
			$strong=substr($a, 0,1);
			for($i=0;$i<strlen($a);$i++){
				if($i!=0)
				$strong=$strong."0";
			}
			return $strong;
		}else{
			return '';
		}
	}
	public static function tranferData2($string)
	{
		$trans = array(
					'à'=>'a','á'=>'a','á'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a',
					'ă'=>'a','ằ'=>'a','ắ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
					'â'=>'a','ầ'=>'a','ấ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',
					'À'=>'a','Á'=>'a','Ả'=>'a','Ã'=>'a','Ạ'=>'a',
					'Ă'=>'a','Ằ'=>'a','Ắ'=>'a','Ẳ'=>'a','Ẵ'=>'a','Ặ'=>'a',
					'Â'=>'a','Ầ'=>'a','Ấ'=>'a','Ẩ'=>'a','Ẫ'=>'a','Ậ'=>'a',    
					'đ'=>'d','Đ'=>'d',
					'è'=>'e','é'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e',
					'ê'=>'e','ề'=>'e','ế'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
					'È'=>'e','É'=>'e','Ẻ'=>'e','Ẽ'=>'e','Ẹ'=>'e',
					'Ê'=>'e','Ề'=>'e','Ế'=>'e','Ể'=>'e','Ễ'=>'e','Ệ'=>'e',
					'ì'=>'i','í'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',
					'Ì'=>'i','Í'=>'i','Ỉ'=>'i','Ĩ'=>'i','Ị'=>'i',
					'ò'=>'o','ó'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o',
					'ô'=>'o','ồ'=>'o','ố'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
					'ơ'=>'o','ờ'=>'o','ớ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',
					'Ò'=>'o','Ó'=>'o','Ỏ'=>'o','Õ'=>'o','Ọ'=>'o',
					'Ô'=>'o','Ồ'=>'o','Ố'=>'o','Ổ'=>'o','Ỗ'=>'o','Ộ'=>'o',
					'Ơ'=>'o','Ờ'=>'o','Ớ'=>'o','Ở'=>'o','Ỡ'=>'o','Ợ'=>'o',
					'ù'=>'u','ú'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u',
					'ư'=>'u','ừ'=>'u','ứ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
					'Ù'=>'u','Ú'=>'u','Ủ'=>'u','Ũ'=>'u','Ụ'=>'u',
					'Ư'=>'u','Ừ'=>'u','Ứ'=>'u','Ử'=>'u','Ữ'=>'u','Ự'=>'u',
					'ỳ'=>'y','ý'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
					'Y'=>'y','Ỳ'=>'y','Ý'=>'y','Ỷ'=>'y','Ỹ'=>'y','Ỵ'=>'y'
				  );
  		$string = strtr(html_entity_decode($string,null,"UTF-8"), $trans);
  		return $string;		
	}
	
	public function stripTags($string, $allowed_tags = null, $allowed_attributes = null)
	{
		
		$string = MrData::filterData($string);
		$html_filter = new Zend_Filter_StripTags($allowed_tags, $allowed_attributes);
		$string = $html_filter->filter($string);
		$string = trim($string, " \n\t");
		return $string;
	}
	public function stripTagSeo($string, $allowed_tags = array("a","p","img","h3","strong"), $allowed_attributes = array("alt","src","title","href","data-original","class"))
	{
		//$string = MrData::filterData($string);
		$html_filter = new Zend_Filter_StripTags($allowed_tags, $allowed_attributes);
		$string = $html_filter->filter($string);
		$string=MrData::emptyTag($string);
		$string = trim($string, " \n\t");
		return $string;
	}
	public  function emptyTag( $content ) {
		  
			  
			    return preg_replace("/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $content);
   

		}

	public static function toLower($string, $encode = "UTF-8")
	{
		return mb_strtolower($string, $encode);
	}
	
	public static function toLower2($string, $encode = "UTF-8")
	{
		return mb_strtolower($string, $encode);
	}
	public function toUpper($string, $encode = "UTF-8")
	{
		return mb_strtoupper($string, $encode);
	}
	public static function toUpper2($string, $encode = "UTF-8")
	{
		return mb_strtoupper($string, $encode);
	}
	public static function Percent($price,$sale){
		$sale=(int)$sale;
		$price=(int)$price;
		if((int)($sale) > (int)($price)){
			$ab= round(100-($price*100)/$sale);
			if($ab>5){
				return $ab;
				
			}
			return 0;

		}elseif($sale==$price){
			return 0;
		}
		return 0;
	}
	public static function toAlias($string)
	{
		$tmp = array("~","`","!","@","#","$","%","^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">",".","?","/");
		
		$string = MrData::tranferData($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp,"",$string);
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".$key;
		}
		return MrData::toLower(substr($string, 1));
	}
	
	public static  function toAlias2($string)
	{
		$tmp = array("”","–","~","`","!","@","#","$","%",'%',"^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">",".","?");
		$string=mb_strtolower($string,"UTF-8");
		$string = MrData::tranferData2($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp,"",$string);
		
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".preg_replace('/[^A-Za-z0-9\-]/', '', $key);
		}
		$string = str_replace("-–-","",$string);
	
		return MrData::toLower2(substr($string, 1));
	}
	public static  function toAlias3($string)
	{
		$tmp = array("”","–","~","`","!","@","#","$","%",'%',"^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">",".","?","/");
		$string=mb_strtolower($string,"UTF-8");
		$string = MrData::tranferData2($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp,"",$string);
		$string=substr($string,0,15);
		
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".preg_replace('/[^A-Za-z0-9\-]/', '', $key);
		}
		$string = str_replace("-–-","",$string);
	
		return MrData::toLower2(substr($string, 1));
	}

	public function toAliasImages($string)
	{
		$tmp = array("~","`","!","@","#","$","%","^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">","?","/");
		
		$string = MrData::tranferData($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp," ",$string);
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".$key;
		}
		return MrData::toLower(substr($string, 1));
	}
	public static function checkEmailType($email_address, $requied = false)
	{
		if(empty($email_address))
		{
			return $requied;
		}
		else 
		{
			 return preg_match('/^[^@]*@[^@]*\.[^@]*$/', $email_address);
		}
		
	}
	public static function checkPhone($phone){
			if(empty($phone)){
				return false;
			}
				if(preg_match("/^([1]-)?[0-9]{4}[0-9]{3}[0-9]{3}$/i", $phone) || preg_match("/^([1]-)?[0-9]{4} [0-9]{3} [0-9]{3}$/i", $phone) || preg_match("/^([1]-)?[0-9]{4}-[0-9]{3}-[0-9]{3}$/i", $phone) ) {
				   return true;
				}
				return false;
	}
	
	public	function checkDate($string)
	{
		$check = explode('/',$string);
		
		if(count($check)!=3)
		{
			return false;
		}
		if($check[1]<1 || $check[1]>12)
		{
			return false;
		}
		else 
		{
			$check[1] = (int) $check[1];
		}

		if ($check[2]<1)
		{
			return false;
		}
		else 
		{
			$check[2] = (int) $check[2]%100;
		}
		
		$thang = array();
		$thang[1] = 31;
		$thang[2] =28;
		$thang[3] =31;
		$thang[4] =30;
		$thang[5] =31;
		$thang[6] =30;
		$thang[7] =31;
		$thang[8] =31;
		$thang[9] =30;
		$thang[10] =31;
		$thang[11] =28;
		$thang[12] =31;
		if($check[2]%4 == 0)
		{
			$thang[2] =29;
		}
		if($check[0]<1)
		{
			return false;
		}
		else
		{	
			$check[0] = (int) $check[0];
			if($check[0]<=$thang[$check[1]])
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		return false;
	}
	
	public function check_File($file_name,$extent_file){
		$extent_file = strtolower($extent_file);//"jpg|gif";	
		$file_name = strtolower($file_name);
		if(!preg_match("/\\.(" . $extent_file . ")$/",$file_name)){ 
    		 return false;
		}
	 		return true;
	}
	public static function check_File_2($file_name,$extent_file){
		$extent_file = strtolower($extent_file);//"jpg|gif";
		$file_name = strtolower($file_name);
		if(!preg_match("/\\.(" . $extent_file . ")$/",$file_name)){
			return false;
		}
		return true;
	}
	public static function delete_directory($dirname) {
	   if (is_dir($dirname))
	      $dir_handle = opendir($dirname);
	   if (!$dir_handle)
	      return false;
	   while($file = readdir($dir_handle)) {
	      if ($file != "." && $file != "..") {
	         if (!is_dir($dirname."/".$file))
	            unlink($dirname."/".$file);
	         else
	            MrData::delete_directory($dirname.'/'.$file);    
	      }
	   }
	   closedir($dir_handle);
	   rmdir($dirname);
	   return true;
	}
	public static function recurse_copy($src,$dst,$id=1,$id_old=1) {
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					MrData::recurse_copy($src . '/' . $file,$dst . '/' .$file ,$id, $id_old);
				}
				else {
					$file_x=str_replace("product", "color", str_replace($id_old, $id, $file));
					copy($src . '/' . $file,$dst . '/' . $file_x );
				}
			}
		}
		closedir($dir);
		MrData::delete_directory($dir);
		return true;
	}
	public static function cleare_directory($dirname) {
	   if (is_dir($dirname))
	      $dir_handle = opendir($dirname);
	   if (!$dir_handle)
	      return false;
	   while($file = readdir($dir_handle)) {
	      if ($file != "." && $file != "..") {
	         if (!is_dir($dirname."/".$file))
	            unlink($dirname."/".$file);
	         else
	            MrData::delete_directory($dirname.'/'.$file);    
	      }
	   }
	   closedir($dir_handle);
	   return true;
	}
	public static function createFolder($dir){
		if(!file_exists($dir)){
			mkdir($dir, 0777,true);
		}
		chmod($dir, 0777);
		return true;
	}
	
	public static function checkLength($string, $min, $max){
		$len = mb_strlen($string,"UTF-8");
		if($len<$min||$len>$max){
			return false;
		}
		return true;
	}
	
	public static function checkRegexp($string, $regexp){
		
		return preg_match($regexp, $string);
		
	}
	
	public static function comPare($str1, $str2){
		if($str1!=$str2){
			return false;
		}
		return true;
	}
	public static function showImage($id, $noimgpath = "noimg.gif?"){
		if(is_file(PICTURE_PATH."user/avatar_aff/".$id.".png")){
			return PICTURE_URL."user/avatar_aff/".$id.".png";
		}
		else{
			return PICTURE_URL."user/user.png";
		}
	}
	
	public static function checkEmpty($val){
		return empty($val);
	}
	public static function checkEmptyArray($arr=array()){
		foreach($arr as $a){
			return empty($a);
		}
	}
	
	public static function showTime($stringDate){
		
				$date=time()-strtotime($stringDate)*1;
				$result="";
				$seconds= $date; //( (strtotime(Date("Y-m-d H:i:s"))*1-time() ) -$date)/1000;
				//return $date;
				$inverval=$seconds/31536000;

				if($inverval > 1){
					$result = $result.round($inverval)." năm ";

				}
				$inverval=$seconds/2592000;

				if($inverval > 1){
					$result = $result.round($inverval)." tháng ";
					$seconds=$seconds - round($inverval)*2592000;
				}

				$inverval=$seconds/86400;

				if($inverval > 1){
					$result = $result.round($inverval)." ngày ";
					$seconds=$seconds - round($inverval)*86400;
				}
				$inverval=$seconds/3600;

				if(strpos($result,"tháng" )==false){


						if($inverval > 1){
							$result =$result.round($inverval)." giờ ";
							$seconds=$seconds - round($inverval)*3600;
						}

						$inverval=$seconds/60;

						if($inverval > 1){
							$result = $result. round($inverval)." phút ";
							$seconds=$seconds - round($inverval)*60;
						}
						$inverval=$seconds;
						if($inverval > 1){
							$result = $result. round($inverval)." giây ";
						}
				}
				return $result." trước";
		/*
		$restr = "";
		$endDate = explode(" ", $stringDate);
		$nowDate = explode(" ", date("Y-m-d H:i:s"));
		$arrendDate = explode("-", $endDate[0]);
		$arrendH = explode(":", $endDate[1]);
		$arrnowDate = explode("-", $nowDate[0]);
		$arrnowH = explode(":", $nowDate[1]);
		if($endDate[0] == $nowDate[0]){
			$time = mktime($arrnowH[0],$arrnowH[1],$arrnowH[2],$arrnowDate[1],$arrnowDate[2],$arrnowDate[0]) - mktime($arrendH[0],$arrendH[1],$arrendH[2],$arrnowDate[1],$arrnowDate[2],$arrnowDate[0]);

			$h = (int)($time/3600);
			$i = number_format(($time-($h*3600))/60);
			if(!$h){
				
				$i = $i==0?1:$i;
				$restr.=$i." phút trước";
			}
			else{
				$restr.=$h." giờ ".$i." phút trước";
			}
		}
		else{

			$restr = ($nowDate[0]*1-$endDate[0])." Ngày ".$arrendH[0]." giờ  trước";//.$arrendDate[2]."/".$arrendDate[1]."/".$arrendDate[0];
		}
		return $restr;*/
	}
	public static function showThumb($dir){
		$arr = scandir(USERDATA_PATH.$dir."/thumbs/");
		$c = count($arr);
		if($c>2){
			return USERDATA_URL.$dir."/thumbs/".$arr[rand(2, $c-1)];
		}
		else{
			return "/public/noimg.gif?";
		}
	}
	
	public static function dateToNumber($date){
		$arr = explode(" ", $date);
		$arr1 = explode("-", $arr[0]);
		$arr2 = explode(":", $arr[1]);
		return mktime($arr2[0],$arr2[1],$arr2[2],$arr1[1],$arr1[2],$arr1[0]);
	}
	public static function subString($string,$len){
		$string = html_entity_decode(strip_tags($string),null,"UTF-8");
		if(mb_strlen($string,"UTF-8")<=$len){
			return $string;
		}
		else{
			return mb_substr($string, 0,($len-3),"UTF-8")."...";
		}
	}
	public static function sub($string,$len){
		$string = html_entity_decode(strip_tags($string),null,"UTF-8");

		if(mb_strlen($string,"UTF-8")<=$len){
			return $string;
		}
		else{
			return mb_substr($string, 0,$len,"UTF-8");
		}
	}
	public static function subTitle($string,$len=65){
		$string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
		$string = ucfirst( strtolower( html_entity_decode(strip_tags($string)) ) );
		if(mb_strlen($string,"UTF-8")<=$len){
			return $string;
		}
		else{
			return mb_substr($string, 0,($len-3),"UTF-8");
		}
	}
	public function randd($option=12){
		 $int = rand(0,10);
	    $a_z = "01234567891";
	    $rand_letter = $a_z[$int];
	    for($i=0;$i<$option;$i++){
	    	 $int1 = rand(0,10);
	    	$rand_letter.= $a_z[$int1];
	    }
	    return $rand_letter;
	}
	public static function randomNumber($option=17){
		 $int = rand(0,51);
	    $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $rand_letter = $a_z[$int];
	    for($i=0;$i<$option;$i++){
	    	 $int1 = rand(0,51);
	    	$rand_letter.= $a_z[$int1];
	    }
	    return $rand_letter;
	}
	public static function toPicePrivate($pice) {
		if(is_numeric($pice) && $pice > 0){
			return number_format($pice,0,".",".")." VNĐ";
		}
		else{
			return "0 VNĐ";
		}
	}
	
	public static function toPrice($pice) {
		if(is_numeric($pice) && $pice > 0){
			if(strlen($pice) >= 4){
				return number_format($pice,0,".",".")." Tỷ";	
			}else{
				return number_format($pice,0,".",".")." Triệu";	
			}
			
		}
		else{
			return "0 Triệu";
		}
	}
	
	public static  function uploadProduct($source,$filename){
		move_uploaded_file($source, PRODUCTS_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(PRODUCTS_PATH.$filename);
		$thumnalbig->resize(230,230);
		$thumnalbig->save(PRODUCTS_PATH."big/".$filename);
		
		$thumnalsmall=$mr->create(PRODUCTS_PATH.$filename);
		$thumnalsmall->resize(100,100);
		$thumnalsmall->save(PRODUCTS_PATH."small/".$filename);
		return true;
		
	}
	
	public static function get_image_product_lcd_cate($cache,$id_product,$option=''){
	
		
	
	
		if(empty($name)){
			$name = "product";
		}
		if($option==''){
			$path=$name.$id_product;
		}else{
			$path=$name.$id_product."/{$option}";
		}
	
		if(is_file(PRODUCTS_PATH.$path."/{$name}_{$id_product}_1.png")){
			$value=PRODUCTS_PATH.$path."/{$name}_{$id_product}_1.png";
			return $cache->cache($value);
	
	
	
		}
		return "/public/noimg.gif?";
			
	}
	public static function get_image_product_all_lcd($id,$path=null,$name=null,$opt=null){
	
		$data=array();
		
        if(empty($path)){
            $path = PRODUCTS_PATH;
        }
        if(empty($opt)){
            $opt = "small"; 
        }
        if(empty($name)){
            $name = "product";
        }
		@$arr = scandir($path.$name.$id."/{$opt}/");
		$c = count($arr);
		
		if($c>=2){
			foreach($arr as $a){
				if (MrData::check_File_2($a, "png") ) {
					$data[]=$a;
				}
			}
			return $data;
		}
		return false;
			
	}
	public static function get_image_color_all_lcd($id_product){
	
		$data=array();
	
		@$arr = scandir(COLOR_PATH."color".$id_product."/small");
		$c = count($arr);
	
	
		if($c>2){
			foreach($arr as $a){
				if (MrData::check_File_2($a, "png") ) {
					$data[]=$a;
				}
			}
			return $data;
		}
		return false;
			
	}
	public static function getAll_Color_Supplier($id_color){
	
		$data=array();
	
		@$arr = scandir(SUPPLIER_COLOR_PATH."product".$id_color."/small/");
		$c = count($arr);
	
	
		if($c>2){
			foreach($arr as $a){
				if (MrData::check_File_2($a, "png") ) {
					$data[]=$a;
				}
			}
			return $data;
		}
		return false;
			
	}
	public static  function uploadCate($source,$filename){
		move_uploaded_file($source, CATE_PATH.$filename);
		return true;
	}
	public static  function uploadBanner($source,$filename){
		move_uploaded_file($source, BANNER_PATH.$filename);
		return true;
	}
	public static function uploadCustomer($source,$filename){
		move_uploaded_file($source, CUSTOMER_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(CUSTOMER_PATH.$filename);
		$thumnalbig->resize(230,230);
		$thumnalbig->save(CUSTOMER_PATH."big/".$filename);		
		$thumnalsmall=$mr->create(CUSTOMER_PATH.$filename);
		$thumnalsmall->resize(49,49);
		$thumnalsmall->save(CUSTOMER_PATH."small/".$filename);
		return true;
	}
	public static function removeCustomer($filename){
		unlink(CUSTOMER_PATH.$filename);
		unlink(CUSTOMER_PATH."big/".$filename);
		unlink(CUSTOMER_PATH."small/".$filename);
	}
	public static function removeProduct($filename){
		unlink(PRODUCTS_PATH.$filename);
		unlink(PRODUCTS_PATH."big/".$filename);
		unlink(PRODUCTS_PATH."small/".$filename);
	}
	public static function uploadSlideshow($filename,$tmp){
		$mr=new Mr_ThumbLib();
		$thum=$mr->create($tmp);
		$thum->resize(800,273);
		$thum->save(SLIDE_PATH.$filename);
	}

	public static function loadCache($hour=3600){
		$frontendOptions = array('lifetime' => $hour, 'automatic_serialization' => true);// cache lifetime of 1 hours
		$backendOptions = array("cache_dir"=>"tmp/");
		return Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
	}
	function getIP()
	{
		//get ip
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
 	public static  function uploadSlideShows($source,$filename,$path){
 	  
    }    
    
	public static  function uploadNews($source,$filename){
		move_uploaded_file($source, NEWS_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(NEWS_PATH.$filename);
		$thumnalbig->resize(276,182);
		$thumnalbig->save(NEWS_PATH."big/".$filename);
		
		$thumnalsmall=$mr->create(NEWS_PATH.$filename);
		$thumnalsmall->resize(85,57);
		$thumnalsmall->save(NEWS_PATH."small/".$filename);
        
        $thumnalsmall2=$mr->create(NEWS_PATH.$filename);
		$thumnalsmall2->resize(271,113);
		$thumnalsmall2->save(NEWS_PATH."small2/".$filename);        
		return true;		
	}
    public static  function uploadPopup($source,$filename){
		move_uploaded_file($source, POPUP_PATH.$filename);
		return true;		
	}
    
    public static  function upload($source,$filename,$path,$path2=0,$path3=0){
		move_uploaded_file($source, $path.$filename);
        if(!empty($path2)) move_uploaded_file($source, $path2.$filename);
        if(!empty($path3)) move_uploaded_file($source, $path3.$filename);
		return true;		
	}
    public static function remove_uploadPopup($id_product){
		unlink(POPUP_PATH."/dienmay_{$id_product}.png");
	}
    public static function remove_banner($id_product){
		unlink(BANNER_PATH."/image1_{$id_product}.png");
        unlink(BANNER_PATH."/image2_{$id_product}.png");
        unlink(BANNER_PATH."/image3_{$id_product}.png");
        unlink(BANNER_PATH."/image4_{$id_product}.png");
	}
    public static function remove_upload($path=0,$path2=0,$path3=0,$id_product,$path4=0){
        if(!empty($path)) unlink($path."/dienmay_{$id_product}.png");
		if(!empty($path2)) unlink($path2."/dienmay_{$id_product}.png");
        if(!empty($path3)) unlink($path3."/dienmay_{$id_product}.png");
        if(!empty($path4)) unlink($path4."/icon_{$id_product}.png");
	}
    public static  function uploadStore($source,$filename){
		move_uploaded_file($source, STORE_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(STORE_PATH.$filename);
		$thumnalbig->resize(230,172);
		$thumnalbig->save(STORE_PATH."big/".$filename);
		
		$thumnalsmall=$mr->create(STORE_PATH.$filename);
		$thumnalsmall->resize(49,36);
		$thumnalsmall->save(STORE_PATH."small/".$filename);
		return true;		
	}
    public static  function uploadbarner($source,$filename, $w_thumb, $h_thumb, $w_thumb_small, $h_thumb_small){
		move_uploaded_file($source, BARNER_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(BARNER_PATH.$filename);
		$thumnalbig->resize($w_thumb,$h_thumb);
		$thumnalbig->save(BARNER_PATH."big/".$filename);
		
		$thumnalsmall=$mr->create(BARNER_PATH.$filename);
		$thumnalsmall->resize($w_thumb_small,$h_thumb_small);
		$thumnalsmall->save(BARNER_PATH."small/".$filename);
		return true;
	}
    
     public static  function uploadfull($path,$source,$filename, $w_thumb, $h_thumb, $w_thumb_small, $h_thumb_small){
		move_uploaded_file($source, $path.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create($path.$filename);
		$thumnalbig->resize($w_thumb,$h_thumb);
		$thumnalbig->save($path."big/".$filename);
		
		$thumnalsmall=$mr->create($path.$filename);
		$thumnalsmall->resize($w_thumb_small,$h_thumb_small);
		$thumnalsmall->save($path."small/".$filename);
		return true;
	}    
    
    public function getRate($rate,$litmi){
    	if($rate==$litmi){
    		return "checked='checked'";
    	}
    	return '';
    	
    }
    public static function uploadbank_promotion($source,$id_product,$filename){
        if(empty($pathIn)){
            $pathIn = BANKPROMOTION_PATH;
        }
		MrData::createFolder($pathIn."bankpro".$id_product);
		
		$path=$pathIn."bankpro".$id_product;                
        move_uploaded_file($source, $path.'/'.$filename);

		return true;
		
	}
	//sql injection
	

	public static function inject($string){
		//[ \ ^ $ . | ? * + ( )
		
		$check= array("`","!","^","+","{","[","]","}","|",";","update","delete","from","where");

		$string=strtolower($string);
		return str_replace($check,"",$string);
	}
	public static function injectSql($string){
	
		$string = preg_replace('#<script.*?</script>#s', '', $string);
		$check= array("`","!","^","+","{","[","]","}","|",";","update","delete","from","where");
		return str_replace($check,"",$string);
	}
}
?>