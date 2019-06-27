<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CicLog;
use App\User;
use App\HistoryLog_View;
use App\HistoryLog;
use App\LogsStatus;
use App\UserTax;
use App\Referal;
use App\LenderCs;
use App\Agencys;
use App\Support;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use App\DashboardMcsView;
use Debugbar;




class ReportController extends Controller
{
    /*

    $role_id = 1   admin  
    $role_id = 2   Người vay  
    $role_id = 4   Admins 
    $role_id = 5   Chăm sóc khách hàng
    $role_id = 6   Quản lý chăm sóc khách hàng
    $role_id = 7   Kế toán 
    $role_id = 8   Agencies  
    $role_id = 9   TCTD   

    */
    /**
    *report chart page dashborad : hoang custom
    *@param col_chart : years       || line_chart : today
    *@return $data 12 month in year || data from today to last 30 days
    **/
    public function for_control_report(Request $req){
        if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $u_id = Auth::user()->id;

        $year = $req->input('year');
        $ref = $req->input('referals');
        $agence = $req->input('agencies');
        ($agence !== null) ? $agence_s = "AND user_ref = $agence" : $agence_s = "";
        // return $agence;
        ($ref !== null ) ? $echo = "AND referal = $ref" : $echo = "";
        ($year != "" ) ? $year : $year = date('Y');
        
        $referals = Referal::get();

        //danh sách acc agencies
        $agencies = User::where('role_id',8)->get();

        //total users
        $totaluser = User::get()->count();
        //hồ sơ chấm điểm
        $total_scoring = HistoryLog_View::where([['history_view.history_log_id','=',NULL],['history_view.progress_info','=',5]])
                                    ->get()
                                    ->count();
        
        //Danh sách users mới nhất
        $usernew =  User::where('role_id',2)
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();
        
        //total hồ sơ
        $total_hsv = HistoryLog_View::where('history_log_id','!=',null)->get()->count();

        //danh sach ho so vay moi nhat
        $hschamnew = HistoryLog_View::select('history_view.name as name1',
                            'history_view.phone as phone',
                            'history_view.khoanvay as khoanvay',
                            'history_view.cmnd as cmnd',
                            'history_view.id as id',
                            'history_view.final_score as final_score',
                            'history_view.address1 as address1',
                            'history_view.name as name2',
                            'history_view.cid_customer_name as name3',
                            'history_view.created_at',
                            'history_view.agencies as agencies')
                            ->whereRaw("(history_view.name IS NOT NULL OR history_view.cid_customer_name )")
                            ->orderBy('history_view.created_at', 'desc')
                            ->limit(10)
                            ->get();

        //Danh sach support mới nhất
        $comment = Support::where('content_support', '!=', '')->groupBy('phone')->orderBy('created_at','DESC')->limit(10)->get();
        
        //Total support
        $total_support = Support::where('content_support', '!=', '')->groupBy('phone')->get()->count();     
        
        //Danh sách ugency
        $agency = User::where('role_id',8)->orderBy('created_at', 'desc')->paginate(10);


        if($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7){
            $where_1="WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y $agence_s";
            $where_2="WHERE DATE_FORMAT( created_at, '%d' ) = d AND DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y $agence_s";
            $where_3="WHERE DATE_FORMAT( created_at, '%d' ) = d AND DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND progress_info = 0 $agence_s";
            $where_count="WHERE DATE_FORMAT( ngay_gui_ho_so, '%d' ) = d AND DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y $agence_s";
            $where="WHERE DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( ngay_gui_ho_so, '%Y' ) = y $agence_s";

        }else{

            $where_1="WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = $u_id $agence_s";
            $where_2="WHERE DATE_FORMAT( created_at, '%d' ) = d AND DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = $u_id $agence_s";
            $where_3="WHERE DATE_FORMAT( created_at, '%d' ) = d AND DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND progress_info = 0 AND user_ref = $u_id $agence_s";
            $where_count="WHERE DATE_FORMAT( ngay_gui_ho_so, '%d' ) = d AND DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = $u_id $agence_s";
            $where="WHERE DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( ngay_gui_ho_so, '%Y' ) = y AND user_ref = $u_id $echo $agence_s";
        }

        //input year
        //thống kê theo ngày 30 gần nhất
        $history_line_chart = DB::select("SELECT
                                        y AS Nam,
                                        m AS Thang,
                                        d AS Ngay,
                                        ( SELECT COUNT(DISTINCT id) FROM history_view $where_3) AS lead,
                                        ( SELECT COUNT(DISTINCT id) FROM history_view $where_2 AND history_log_id IS NULL AND progress_info > 0) AS account,
                                        (SELECT COUNT(history_log_id) FROM history_view $where_count) AS ho_so_vay,
                                        (SELECT COUNT(history_log_id) FROM history_view $where_count AND trangthai = 2) AS duocduyet 
                                    FROM (
                                      SELECT y, m, d 
                                      FROM
                                        (SELECT $year y) year,
                                        (SELECT MONTH(CURDATE()) m UNION ALL SELECT MONTH(CURDATE())-1) months,
                                        (SELECT 1 d UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                                          UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
                                          UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 
                                                UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 
                                                UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17  
                                                UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20
                                              UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 
                                              UNION ALL SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 
                                              UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29 UNION ALL SELECT 30 UNION ALL SELECT 31) days) md
                                      LEFT JOIN history_view
                                         ON md.d = DAY(FROM_UNIXTIME(history_view.ngay_gui_ho_so))
                                    WHERE
                                            (m=MONTH(CURDATE()) AND d<=DAY(CURDATE()))
                                           OR
                                        (m<MONTH(CURDATE()) AND d>DAY(CURDATE()))
                                    GROUP BY y, m, d");

        //thống kê theo tháng
        $history_col_chart = DB::select("
                            SELECT  y AS Nam,
                                    m AS Thang ,
                                    ( SELECT COUNT(DISTINCT id ) FROM history_view $where_1 AND progress_info = 0) AS lead,
                                    ( SELECT COUNT(DISTINCT id ) FROM history_view $where_1 AND history_log_id IS NULL AND progress_info > 0) AS account,
                                    (SELECT COUNT(history_log_id) FROM history_view $where) AS ho_so_vay,
                                    (SELECT COUNT(history_log_id) FROM history_view $where AND trangthai = 2) AS duocduyet ,
                                    ROUND(((SELECT COUNT(history_log_id) FROM history_view $where AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $where))*100) AS ti_le,
                                    ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $where AND  `history_view`.`trangthai` = 2 ) AS `giai_ngan` 
                            FROM
                            (
                                SELECT
                                    y, m 
                                FROM
                                    (SELECT $year  y ) years,
                                    (
                                    SELECT 1 m UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL
                                    SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                                    SELECT 12 
                                    ) months 
                                ) ym
                            LEFT  JOIN history_view ON ym.y = YEAR ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) ) 
                                AND ym.m = MONTH ( FROM_UNIXTIME( history_view.ngay_gui_ho_so ) )");

        /**************dashboard cho cs & manager cs**************/
        if ($role_id == 5) {
           $count_kh= DB::table('dashboard_cs_mcs_view')
                            ->select([
                                    'title as title',
                                    'u_id',
                                    'u_name',
                                    'email',
                                    DB::raw('COUNT(DISTINCT lender_id) as tong_lender'),
                                    DB::raw('COUNT(DISTINCT his_id) as tong_khachhang'),
                                    DB::raw('COUNT(DISTINCT agency_id) as tong_agency'),
                                    ])
                            ->where('u_id',$u_id)
                            ->groupBy('title','u_id','u_name','email')
                            ->get();
        }
        if ($role_id == 6) {
            $count_kh = DB::table('dashboard_cs_mcs_view')
                            ->select([
                                    'title as title',
                                    'u_id',
                                    'u_name',
                                    'email',
                                    DB::raw('COUNT(DISTINCT lender_id) as tong_lender'),
                                    DB::raw('COUNT(DISTINCT his_id) as tong_khachhang'),
                                    DB::raw('COUNT(DISTINCT agency_id) as tong_agency'),
                                    ])
                            ->groupBy('title','u_id','u_name','email')
                            ->get();
        }

        Debugbar::info("Hello World !");
        //Bắt đầu
        Debugbar::startMeasure('render','Time for rendering');
        //Kết thúc
        Debugbar::stopMeasure('render');
        Debugbar::addMeasure('now', LARAVEL_START, microtime(true));
        Debugbar::measure('My long operation', function() {
            // Do something…
        });

        /********************************************************/
        return view('vendor.voyager.index',compact('history_col_chart','total_support','history_line_chart','referals','ref','year','agencies','agence','count_kh','usernew','total_hsv','hschamnew','totaluser','agency','total_scoring','comment'));
    }

    /**
    *reports đối soát -> hoang custom
    *@param years change 
    *@return $data of 12 month in years
    **/
    public function for_control(Request $req){
        $year = $req->input('year');
        $agence_input = $req->input('agency');
        // return $agence_input;
        $agence = User::where('role_id',8)->get();

        ($year != "" ) ? $year : $year = date('Y');
        if(!Auth::user()){
           $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $u_id = Auth::user()->id;
        ($agence_input != "") ? $search_agence = "AND user_ref = $agence_input" : $search_agence = "";
        $ref = $req->input('referals');
        ($ref !== null ) ? $echo = "AND referal = $ref" : $echo = "";

        $referals = Referal::get();

        $logsstatus = LogsStatus::get();
        $where_1 = "";
        ($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7) ? $where_1="WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y $search_agence": $where_1="WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = $u_id $echo $search_agence";
        $where_2 = "";
        ($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7) ? $where_2="WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND progress_info = 0  $search_agence": $where_2="WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND progress_info = 0 AND user_ref = $u_id $echo $search_agence";
        $where = "";
        ($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7) ? $where="WHERE DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( ngay_gui_ho_so, '%Y' ) = y  $search_agence": $where="WHERE DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( ngay_gui_ho_so, '%Y' ) = y AND user_ref = $u_id $echo  $search_agence";
        $history = DB::select("
                        SELECT  y AS Nam,
                                m AS Thang ,
                                ( SELECT COUNT(*) FROM history_view $where_1) AS total,
                                ( SELECT COUNT(DISTINCT id) FROM history_view $where_2 ) AS lead,
                                ( SELECT COUNT(DISTINCT id) FROM history_view $where_1 AND history_log_id IS NULL AND progress_info > 0) AS account,
                                (SELECT COUNT(history_log_id) FROM history_view $where) AS ho_so_vay,
                                (SELECT COUNT(history_log_id) FROM history_view $where AND trangthai = 2) AS duocduyet ,
                                ROUND(((SELECT COUNT(history_log_id) FROM history_view $where AND trangthai = 2)/(SELECT COUNT(history_log_id) FROM history_view $where))*100) AS ti_le,
                                ( SELECT sum( `history_view`.`so_tien_giai_ngan` ) FROM `history_view` $where AND trangthai = 2 ) AS `giai_ngan` 
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

        /*******************************************/



        return view('admin.report.browse',compact('history','logsstatus','referals','ref','year','agence'));
    }



}
