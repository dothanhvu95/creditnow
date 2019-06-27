<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\AcounttantNote;
use App\CicLog;
use App\User;
use Excel;
use DB;

class ControlController extends Controller
{
	protected $View=[];
    /**
    * Đối soát ADPIA 
    * @param $request
    * @return list
    */
    public function adpiaList(Request $request){
    	if(!Auth::user()){
           $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $u_id = Auth::user()->id;
        $year = $request->input('year');

        $search = [];
        $year=array(
        		"2018"=>"2018",
                "2019"=>"2019",
                "2020"=>"2020",
                "2021"=>"2021",
             	"2022"=>"2022"
        );
        $this->View['year'] = $year;
        if($request->isMethod("get")){
            $search['year']   = $request->input('year',date('Y'));
            if(!empty($search['year'])){
                $year = $search['year'];
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = 98 AND status =1";
            }
  
        }
       
        $this->View['search']=$search;
        $this->View['history'] =DB::select( "SELECT  y AS Nam,
                                m AS Thang ,
                                ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100) AS ti_le,
                                ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan` ,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan_thuc` 
                        FROM
                        (
                            SELECT
                                y, m 
                            FROM
                                ( SELECT $year y ) years,
                                (
                                SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                SELECT 12 
                                ) months 
                            ) ym
                        LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                            AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) "); 
        
        
    	return view('admin.control.adpialist',$this->View);
    }
     /**
    * Đối soát Access Trade 
    * @param $request
    * @return list
    */
    public function accesstradeList(Request $request){
        if(!Auth::user()){
           $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $u_id = Auth::user()->id;
        $year = $request->input('year');

        $search = [];
        $year=array(
                "2018"=>"2018",
                "2019"=>"2019",
                "2020"=>"2020",
                "2021"=>"2021",
                "2022"=>"2022"
        );
        $this->View['year'] = $year;
        if($request->isMethod("get")){
            $search['year']   = $request->input('year',date('Y'));
            if(!empty($search['year'])){
                $year = $search['year'];
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = 245 AND status =1";
            }
  
        }
       
        $this->View['search']=$search;
        $this->View['history'] =DB::select( "SELECT  y AS Nam,
                                m AS Thang ,
                                ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100) AS ti_le,
                                ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan` ,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan_thuc` 
                        FROM
                        (
                            SELECT
                                y, m 
                            FROM
                                ( SELECT $year y ) years,
                                (
                                SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                SELECT 12 
                                ) months 
                            ) ym
                        LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                            AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) "); 
        
        
        return view('admin.control.accesstradelist',$this->View);
    }
     /**
    * Đối soát Swifi 
    * @param $request
    * @return list
    */
    public function swifiList(Request $request){
        if(!Auth::user()){
           $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $u_id = Auth::user()->id;
        $year = $request->input('year');

        $search = [];
        $year=array(
                "2018"=>"2018",
                "2019"=>"2019",
                "2020"=>"2020",
                "2021"=>"2021",
                "2022"=>"2022"
        );
        $this->View['year'] = $year;
        if($request->isMethod("get")){
            $search['year']   = $request->input('year',date('Y'));
            if(!empty($search['year'])){
                $year = $search['year'];
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = 833 AND status =1";
            }
  
        }
       
        $this->View['search']=$search;
        $this->View['history'] =DB::select( "SELECT  y AS Nam,
                                m AS Thang ,
                                ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100) AS ti_le,
                                ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan` ,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan_thuc` 
                        FROM
                        (
                            SELECT
                                y, m 
                            FROM
                                ( SELECT $year y ) years,
                                (
                                SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                SELECT 12 
                                ) months 
                            ) ym
                        LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                            AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) "); 
        
        
        return view('admin.control.swifilist',$this->View);
    }
     /**
    * Đối soát TCTD OCB
    * @param $request
    * @return list
    */
    public function ocbList(Request $request){
        if(!Auth::user()){
           $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $u_id = Auth::user()->id;
        $year = $request->input('year');

        $search = [];
        $year=array(
                "2018"=>"2018",
                "2019"=>"2019",
                "2020"=>"2020",
                "2021"=>"2021",
                "2022"=>"2022"
        );
        $this->View['year'] = $year;
        if($request->isMethod("get")){
            $search['year']   = $request->input('year',date('Y'));
            if(!empty($search['year'])){
                $year = $search['year'];
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND tctd_id_tt = 1 AND status =1";
            }
  
        }
       
        $this->View['search']=$search;
        $this->View['history'] =DB::select("SELECT  y AS Nam,
                                m AS Thang ,
                                ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 1) AS choduyet ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 6) AS hstrung ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 7) AS tuchoi ,
                                ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100) AS ti_le,
                                ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan` ,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan_thuc` 
                        FROM
                        (
                            SELECT
                                y, m 
                            FROM
                                ( SELECT $year y ) years,
                                (
                                SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                SELECT 12 
                                ) months 
                            ) ym
                        LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                            AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) ");
                        // ); 
       
        
        return view('admin.control.ocblist',$this->View);


    }
    /**
    * Đối soát TCTD MIRAEASSET
    * @param $request
    * @return list
    */
    public function miraeassetList(Request $request){
        if(!Auth::user()){
           $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $u_id = Auth::user()->id;
        $year = $request->input('year');

        $search = [];
        $year=array(
                "2018"=>"2018",
                "2019"=>"2019",
                "2020"=>"2020",
                "2021"=>"2021",
                "2022"=>"2022"
        );
        $this->View['year'] = $year;
        if($request->isMethod("get")){
            $search['year']   = $request->input('year',date('Y'));
            if(!empty($search['year'])){
                $year = $search['year'];
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND tctd_id_tt = 2 AND status =1";
            }
  
        }
       
        $this->View['search']=$search;
        $this->View['history'] =DB::select("SELECT  y AS Nam,
                                m AS Thang ,
                                ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 1) AS choduyet ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 5) AS pending ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 6) AS hstrung ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 7) AS giaingan ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 8) AS guidi ,
                                ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 7)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100,1) AS ti_le,
                                ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 7 ) AS `giai_ngan` ,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 7 ) AS `giai_ngan_thuc` 
                        FROM
                        (
                            SELECT
                                y, m 
                            FROM
                                ( SELECT $year y ) years,
                                (
                                SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                SELECT 12 
                                ) months 
                            ) ym
                        LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                            AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) ");
                        // ); 
       
        
        return view('admin.control.miraeassetlist',$this->View); 
    }
    /**
     * [adpiaExport description]
     * @param  Request $request [year]
     * @return [excel]           [description]
     */
    public function adpiaExport(Request $request){
       
        
        $name_excel="Adpia_".date("d-m-Y");
        Excel::create($name_excel,function($excel) use ($request){
            $excel->sheet('Danh sách', function($sheet) use ($request) {
               

                $result[]=["Tháng", "Agencies","Tổng","Số hồ sơ chấm điểm","Số hồ sơ vay","Được duyệt","Tỷ lệ thành công","Khoản vay","Tổng tiền giải ngân"];
                $year = $request->input('year',2019);
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = 98 AND status =1";
                $TData = DB::select( "SELECT  y AS Nam,
                                        m AS Thang ,
                                        ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                        ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                        (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                        (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                        ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100) AS ti_le,
                                        ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                        ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan` ,
                                        ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan_thuc` 
                                FROM
                                (
                                    SELECT
                                        y, m 
                                    FROM
                                        ( SELECT $year y ) years,
                                        (
                                        SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                        SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                        SELECT 12 
                                        ) months 
                                    ) ym
                                LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                                    AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) "); 
                
                foreach($TData as $k => $v) {
                    
                    $value=(array)$v;
                    if($value['total'] > 0){ $total = $value['total'];}else{ $total = '0';}
                    if($value['hsv'] > 0){ $hsv = $value['hsv'];}else{ $hsv = '0';}
                    if($value['hscd'] > 0){ $hscd = $value['hscd'];}else{ $hscd = '0';}
                    if($value['duocduyet'] > 0){ $duocduyet = $value['duocduyet'];}else{ $duocduyet = '0';}
                    if($value['ti_le'] > 0){ $ti_le = $value['ti_le'];}else{ $ti_le = '0';}

                    $result[]=array(
                          $value['Thang'].'/'.$value['Nam'],
                          'Partner ADPIA',
                          $total,
                          $hscd,
                          $hsv,
                          $duocduyet,
                          $ti_le,
                          isset($value['khoan_vay']) ? $value['khoan_vay'] : '0',
                          isset($value['giai_ngan']) ? $value['giai_ngan'] : '0'
                    );

                }
                $sheet->fromArray($result, null, 'A1', false, false);
                },'UTF-8');

        })->download('xlsx');

    }

    /**
     * [accesstradeExport description]
     * @param  Request $request [year]
     * @return [excel]           [description]
     */
    public function accesstradeExport(Request $request){

        $name_excel="Access_Trade_".date("d-m-Y");
        Excel::create($name_excel,function($excel) use ($request){
            $excel->sheet('Danh sách', function($sheet) use ($request) {
               

                $result[]=["Tháng", "Agencies","Tổng","Số hồ sơ chấm điểm","Số hồ sơ vay","Được duyệt","Tỷ lệ thành công","Khoản vay","Tổng tiền giải ngân"];
                $year = $request->input('year',2019);
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = 245 AND status =1";
                $TData = DB::select( "SELECT  y AS Nam,
                                        m AS Thang ,
                                        ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                        ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                        (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                        (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                        ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100) AS ti_le,
                                        ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                        ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan` ,
                                        ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan_thuc` 
                                FROM
                                (
                                    SELECT
                                        y, m 
                                    FROM
                                        ( SELECT $year y ) years,
                                        (
                                        SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                        SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                        SELECT 12 
                                        ) months 
                                    ) ym
                                LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                                    AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) "); 
                
                foreach($TData as $k => $v) {
                    
                    $value=(array)$v;
                    if($value['total'] > 0){ $total = $value['total'];}else{ $total = '0';}
                    if($value['hsv'] > 0){ $hsv = $value['hsv'];}else{ $hsv = '0';}
                    if($value['hscd'] > 0){ $hscd = $value['hscd'];}else{ $hscd = '0';}
                    if($value['duocduyet'] > 0){ $duocduyet = $value['duocduyet'];}else{ $duocduyet = '0';}
                    if($value['ti_le'] > 0){ $ti_le = $value['ti_le'];}else{ $ti_le = '0';}


                    $result[]=array(
                          $value['Thang'].'/'.$value['Nam'],
                          'Access Trade',
                          $total,
                          $hscd,
                          $hsv,
                          $duocduyet,
                          $ti_le,
                          isset($value['khoan_vay']) ? $value['khoan_vay'] : '0',
                          isset($value['giai_ngan']) ? $value['giai_ngan'] : '0'
                    );

                }
                $sheet->fromArray($result, null, 'A1', false, false);
                },'UTF-8');

        })->download('xlsx');
    }
    /**
     * [swifiExport description]
     * @param  Request $request [year]
     * @return [excel]           [description]
     */
    public function swifiExport(Request $request){
        $name_excel="S_wifi_".date("d-m-Y");
        Excel::create($name_excel,function($excel) use ($request){
            $excel->sheet('Danh sách', function($sheet) use ($request) {
               

                $result[]=["Tháng", "Agencies","Tổng","Số hồ sơ chấm điểm","Số hồ sơ vay","Được duyệt","Tỷ lệ thành công","Khoản vay","Tổng tiền giải ngân"];
                $year = $request->input('year',2019);
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = 833 AND status = 1";
                $TData = DB::select( "SELECT  y AS Nam,
                                        m AS Thang ,
                                        ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                        ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                        (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                        (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                        ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100) AS ti_le,
                                        ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                        ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan` ,
                                        ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan_thuc` 
                                FROM
                                (
                                    SELECT
                                        y, m 
                                    FROM
                                        ( SELECT $year y ) years,
                                        (
                                        SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                        SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                        SELECT 12 
                                        ) months 
                                    ) ym
                                LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                                    AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) "); 
                
                foreach($TData as $k => $v) {
                    
                    $value=(array)$v;
                    if($value['total'] > 0){ $total = $value['total'];}else{ $total = '0';}
                    if($value['hsv'] > 0){ $hsv = $value['hsv'];}else{ $hsv = '0';}
                    if($value['hscd'] > 0){ $hscd = $value['hscd'];}else{ $hscd = '0';}
                    if($value['duocduyet'] > 0){ $duocduyet = $value['duocduyet'];}else{ $duocduyet = '0';}
                    if($value['ti_le'] > 0){ $ti_le = $value['ti_le'];}else{ $ti_le = '0';}

                    $result[]=array(
                          $value['Thang'].'/'.$value['Nam'],
                          'S-Wifi',
                          $total,
                          $hscd,
                          $hsv,
                          $duocduyet,
                          $ti_le,
                          isset($value['khoan_vay']) ? $value['khoan_vay'] : '0',
                          isset($value['giai_ngan']) ? $value['giai_ngan'] : '0'
                    );

                }
                $sheet->fromArray($result, null, 'A1', false, false);
                },'UTF-8');

        })->download('xlsx');
    }
    /**
     * [miraeassetExport description]
     * @param  Request $request [year]
     * @return [excel]           [description]
     */
    public function miraeassetExport(Request $request){
        $name_excel="Mirae_Asset".date("d-m-Y");
        Excel::create($name_excel,function($excel) use ($request){
            $excel->sheet('Danh sách', function($sheet) use ($request) {
               

                $result[]=["Tháng", "TCTD","Tổng","Số hồ sơ vay","Chờ duyệt","Được duyệt","Hồ sơ trùng","Từ chối","Tỷ lệ thành công","Khoản vay","Tổng tiền giải ngân","Chiết Khấu"];
                $year = $request->input('year',2019);
                $sql = "WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND tctd_id_tt = 2 AND status = 1";
                $TData = DB::select("SELECT  y AS Nam,
                                m AS Thang ,
                                ( SELECT COUNT(*) FROM history_view $sql) AS total,
                                ( SELECT COUNT(DISTINCT id) FROM history_view $sql AND  history_log_id IS NULL AND progress_info = 5) AS hscd,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND history_log_id IS NOT NULL ) AS hsv,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 1) AS choduyet ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2) AS duocduyet ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 6) AS hstrung ,
                                (SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 7) AS tuchoi ,
                                ROUND(((SELECT COUNT(history_log_id) FROM history_view $sql AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $sql))*100) AS ti_le,
                                ( SELECT SUM(`history_view`.`khoanvay`  ) FROM history_view $sql ) AS khoan_vay,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan` ,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan_thuc` ) FROM `history_view` $sql AND trangthai = 2 ) AS `giai_ngan_thuc` 
                        FROM
                        (
                            SELECT
                                y, m 
                            FROM
                                ( SELECT $year y ) years,
                                (
                                SELECT 01 m UNION ALL SELECT 02 UNION ALL SELECT 03 UNION ALL SELECT 04 UNION ALL SELECT 05 UNION ALL
                                SELECT 06 UNION ALL SELECT 07 UNION ALL SELECT 08 UNION ALL SELECT 09 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                SELECT 12 
                                ) months 
                            ) ym
                        LEFT JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                            AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) ");
                
                foreach($TData as $k => $v) {
                    
                    $value=(array)$v;
                    if($value['total'] > 0){ $total = $value['total'];}else{ $total = '0';}
                    if($value['hsv'] > 0){ $hsv = $value['hsv'];}else{ $hsv = '0';}
                    if($value['choduyet'] > 0){ $choduyet = $value['choduyet'];}else{ $choduyet = '0';}
                    if($value['duocduyet'] > 0){ $duocduyet = $value['duocduyet'];}else{ $duocduyet = '0';}
                    if($value['hstrung'] > 0){ $hstrung = $value['hstrung'];}else{ $hstrung = '0';}
                    if($value['tuchoi'] > 0){ $tuchoi = $value['tuchoi'];}else{ $tuchoi = '0';}
                    if($value['ti_le'] > 0){ $ti_le = $value['ti_le'];}else{ $ti_le = '0';}
                    $result[]=array(
                          $value['Thang'].'/'.$value['Nam'],
                          'Mirae Asset',
                          $total,
                          $hsv,
                          $choduyet,
                          $duocduyet,
                          $hstrung,
                          $tuchoi,
                          $ti_le,
                          isset($value['khoan_vay']) ? $value['khoan_vay'] : '0',
                          isset($value['giai_ngan']) ? $value['giai_ngan'] : '0',
                          ''
                    );

                }

                $sheet->fromArray($result, null, 'A1', false, false);
                $sheet->setBorder('A1:F10', 'thin');
                },'UTF-8');

        })->download('xlsx');
    }
}