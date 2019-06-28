<?php
namespace App\Helper ;
class History
{
	public function __construct()
	{
		
	}
	
	public static function qlead(){
		$lead = array(
            '' => 'Chọn',
            '0' => 'Un QLead',
            '1' => 'QLead'
        );
        return $lead;
	}
	public static function typehs(){
		$type=array(
                ""=>"Tất cả hồ sơ",
                "1"=>"Hồ sơ vay ",
                "2"=>"Hồ sơ chấm điểm"
             
        );
        return $type;
	}
	public static function point(){
		$point = array(
                ""=>"Chọn",
                "1"=>"Điểm từ thấp đến cao ",
                "2"=>"Điểm từ cao đến thấp",
                "3"=>"Khoản vay từ thấp đến cao ",
                "4"=>"Khoản vay từ cao đến thấp"
        );
		return $point;
	}

	public static function sort_order(){
		$show = array(
            "50"    => '50',
            "100"   => '100',
            "200"   => '200',
            "300"   => '300'
        );
        return $show;
	} 
}
?>