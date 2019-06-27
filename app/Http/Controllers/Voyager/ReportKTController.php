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
use App\Lender;
use App\AcounttantNote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;



class ReportKTController extends Controller
{

    public function report_customer_care( Request $req )
    {
        $year_input = $req->input('year');
        $customercare_input = $req->input('customercare');        

        $customercare = User::where('role_id',5)->get();

        ($year_input != null || $year_input != "") ? $year = $year_input : $year = date('Y');

        ($customercare_input != null || $customercare_input != "") ? $search = 'WHERE YEAR( h.ngay_gui_ho_so ) = '.$year.' AND h.cs_id_history = '.$customercare_input.' AND h.cs_id_history IS NOT NULL AND h.cs_name_history IS NOT NULL' : $search = 'WHERE YEAR( h.ngay_gui_ho_so ) = '.$year.' AND h.cs_id_history IS NOT NULL AND h.cs_name_history IS NOT NULL';

        $history = DB::select("SELECT MONTH
                                ( h.ngay_gui_ho_so ) AS thang,
                                YEAR ( h.ngay_gui_ho_so ) AS nam,
                                h.cs_name_history AS cs_name_history,
                                h.cs_id_history AS id_cs,
                                u.role_id AS role_id,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info = 0 
                                    AND cs_id_history = id_cs 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS tong_lead,
                                (
                                SELECT
                                    count( id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND history_log_id IS NULL 
                                    AND cs_id_history = id_cs 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS account,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND cs_id_history = id_cs 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS ho_so_vay,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND trangthai = 2 
                                    AND cs_id_history = id_cs 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS duocduyet,
                                ((
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND cs_id_history = id_cs 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                )/(
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND trangthai = 2 
                                    AND cs_id_history = id_cs 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ))*100 AS tile,
                                ( SELECT SUM( khoanvay ) FROM history_view WHERE cs_id_history = id_cs AND MONTH ( ngay_gui_ho_so ) = thang AND YEAR ( ngay_gui_ho_so ) = nam ) AS khoan_vay,
                                ( SELECT SUM( so_tien_giai_ngan ) FROM history_view WHERE cs_id_history = id_cs AND MONTH ( ngay_gui_ho_so ) = thang AND YEAR ( ngay_gui_ho_so ) = nam ) AS giai_ngan,
                                ( SELECT SUM( so_tien_giai_ngan_thuc ) FROM history_view WHERE cs_id_history = id_cs AND MONTH ( ngay_gui_ho_so ) = thang AND YEAR ( ngay_gui_ho_so ) = nam ) AS giai_ngan_thuc,
                                a.comment AS note
                            FROM
                                history_view h
                                LEFT JOIN users u ON u.id = h.cs_id_history 
                                LEFT JOIN accountant_note a ON a.cs_id = h.cs_id_history 
                            $search
                            GROUP BY
                                h.cs_name_history,
                                h.cs_id_history,
                                u.role_id,
                                a.comment,
                                MONTH ( h.ngay_gui_ho_so ),
                                YEAR ( h.ngay_gui_ho_so )");
        // return $history;
        return view('accountant.report_cs.browse' , compact('history','customercare','year','customercare_input'));
    }
    public function save_note_kt(Request $req)
    {
        $param = $req->all();
        $check = AcounttantNote::where([['date_note',$param['date_note']],['user_id',$param['user_id']],['role_id',$param['role_id']]])->count();
        if ($check > 0) {
            if ( $param['note'] != "") {
               $update = AcounttantNote::where([['date_note',$param['date_note']],['user_id',$param['user_id']]])->update(['comment' => $param['note']]);
                if ($update = true) {
                    return redirect('admin/report-customer-care');
                }
            }
            else
            {
                echo "<script>alert('Không được dể trống trường ghi chú')</script>";
                return redirect('admin/report-customer-care');
            }
        }
        else
        {
            if ($param['note'] != "") {
                $data = array('date_note' => $param['date_note'],
                                'comment' => $param['note'],
                                'user_id' => $param['user_id'],
                                'role_id' => $param['role_id']
                                 );
                $create = AcounttantNote::create($data);
                if ($create = true) {
                     return redirect('admin/report-customer-care');
                }
            }
            else
            {
                echo "<script>alert('Không được dể trống trường ghi chú')</script>";
                return redirect('admin/report-customer-care');
            }
        }
    }



    /**
    *report cho kế toán của agencies
    *@param 
    *@return $data
    **/

    public function report_agencies( Request $req)
    {
        // return $req->all();
        $year_input = $req->input('year');
        $agencies_input = $req->input('agencies');
        $agencies = User::where('role_id',8)->get();
        // return $agencies;
        ($year_input != null || $year_input != "") ? $year = $year_input : $year = date('Y');

        ($agencies_input != null || $agencies_input != "") ? $search = 'WHERE YEAR( h.ngay_gui_ho_so ) = '.$year.' AND h.user_ref = '.$agencies_input.'' : $search = 'WHERE YEAR( h.ngay_gui_ho_so ) = '.$year.'';


        $history = DB::select("SELECT 
                                MONTH ( h.ngay_gui_ho_so ) AS thang,
                                YEAR ( h.ngay_gui_ho_so ) AS nam,
                                h.agencies AS agencies,
                                h.user_ref AS user_ref_id,
                                u.role_id AS role_id,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info = 0 
                                    AND user_ref = user_ref_id 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS tong_lead,
                                (
                                SELECT
                                    count( id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND history_log_id IS NULL 
                                    AND user_ref = user_ref_id 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS account,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND user_ref = user_ref_id 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS ho_so_vay,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND trangthai = 2 
                                    AND user_ref = user_ref_id 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS duocduyet,
                                (
                                    (
                                    SELECT
                                        count( history_log_id ) 
                                    FROM
                                        history_view 
                                    WHERE
                                        progress_info > 0 
                                        AND user_ref = user_ref_id 
                                        AND MONTH ( ngay_gui_ho_so ) = thang 
                                        AND YEAR ( ngay_gui_ho_so ) = nam 
                                    )/
                                    (
                                    SELECT
                                        count( history_log_id ) 
                                    FROM
                                        history_view 
                                    WHERE
                                        progress_info > 0 
                                        AND trangthai = 2 
                                        AND user_ref = user_ref_id 
                                        AND MONTH ( ngay_gui_ho_so ) = thang 
                                        AND YEAR ( ngay_gui_ho_so ) = nam 
                                    )*100
                                ) AS tile,
                                ( SELECT SUM( khoanvay ) FROM history_view WHERE user_ref = user_ref_id AND MONTH ( ngay_gui_ho_so ) = thang AND YEAR ( ngay_gui_ho_so ) = nam ) AS khoan_vay,
                                ( SELECT SUM( so_tien_giai_ngan ) FROM history_view WHERE user_ref = user_ref_id AND MONTH ( ngay_gui_ho_so ) = thang AND YEAR ( ngay_gui_ho_so ) = nam ) AS giai_ngan,
                                ( SELECT SUM( so_tien_giai_ngan_thuc ) FROM history_view WHERE user_ref = user_ref_id AND MONTH ( ngay_gui_ho_so ) = thang AND YEAR ( ngay_gui_ho_so ) = nam ) AS giai_ngan_thuc,
                                a.comment AS note 
                            FROM
                                history_view h
                                LEFT JOIN users u ON u.id = h.user_ref
                                LEFT JOIN accountant_note a ON a.agenci_id = h.user_ref 
                            $search
                            GROUP BY
                                h.agencies,
                                h.user_ref,
                                u.role_id,
                                a.comment,
                                MONTH ( h.ngay_gui_ho_so ),
                                YEAR ( h.ngay_gui_ho_so )"); 
        // return $history;
        return view('accountant.report_agenci.browse' , compact('history','agencies','agencies_input','year'));
    }

    public function save_note_kt_agenci(Request $req)
    {
        $param = $req->all();
        $check = AcounttantNote::where([['date_note',$param['date_note']],['agenci_id',$param['user_id']],['role_id',$param['role_id']]])->count();
        // return $check;
        if ($check > 0) {
            if ( $param['note'] != "") {
               $update = AcounttantNote::where([['date_note',$param['date_note']],['agenci_id',$param['user_id']]])->update(['comment' => $param['note']]);
                if ($update = true) {
                    return redirect('admin/report-agencies');
                }
            }
            else
            {
                echo "<script>alert('Không được dể trống trường ghi chú')</script>";
                return redirect('admin/report-agencies');
            }
        }
        else
        {
            if ($param['note'] != "") {
                $data = array('date_note' => $param['date_note'],
                                'comment' => $param['note'],
                                'agenci_id' => $param['user_id'],
                                'role_id' => $param['role_id']
                                 );
                $create = AcounttantNote::create($data);
                if ($create = true) {
                     return redirect('admin/report-agencies');
                }
            }
            else
            {
                echo "<script>alert('Không được dể trống trường ghi chú')</script>";
                return redirect('admin/report-agencies');
            }
        }
    }


     /**
    *report cho kế toán của agencies
    *@param 
    *@return $data
    **/

    public function report_tctd( Request $req )
    {
        $year_input = $req->input('year');
        $tctd_input = $req->input('tctd');        

        $tctd = lender::get();
        // return $tctd;
        ($year_input != null || $year_input != "") ? $year = $year_input : $year = date('Y');

        ($tctd_input != null || $tctd_input != "") ? $search = 'AND YEAR( h.ngay_gui_ho_so ) = '.$year.' AND l.id = '.$tctd_input.'' : $search = 'AND YEAR( h.ngay_gui_ho_so ) = '.$year.'';
        $history = DB::select("SELECT MONTH
                                ( h.ngay_gui_ho_so ) AS thang,
                                YEAR ( h.ngay_gui_ho_so ) AS nam,
                                h.tctd_id_hist AS id_tctd,
                                
                                l.name AS name_tctd,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info = 0 
                                    AND tctd_id_hist = id_tctd 
                                    AND tctd_id_hist IS NOT NULL 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS tong_lead,
                                (
                                SELECT
                                    count( id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND history_log_id IS NULL 
                                    AND tctd_id_hist = id_tctd 
                                    AND tctd_id_hist IS NULL 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS account,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND tctd_id_hist = id_tctd 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS ho_so_vay ,
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    trangthai = 2 
                                    AND tctd_id_hist = id_tctd 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS duocduyet,
                                ((
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    progress_info > 0 
                                    AND tctd_id_hist = id_tctd 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                )/
                                (
                                SELECT
                                    count( history_log_id ) 
                                FROM
                                    history_view 
                                WHERE
                                    trangthai = 2 
                                    AND tctd_id_hist = id_tctd 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ))*100 AS tile,
                                (
                                SELECT
                                    SUM( khoanvay ) 
                                FROM
                                    history_view 
                                WHERE
                                    tctd_id_hist = id_tctd 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND tctd_id_hist IS NOT NULL 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS khoan_vay,
                                (
                                SELECT
                                    SUM( so_tien_giai_ngan ) 
                                FROM
                                    history_view 
                                WHERE
                                    tctd_id_hist = id_tctd 
                                    AND tctd_id_hist IS NOT NULL 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS giai_ngan,
                                (
                                SELECT
                                    SUM( so_tien_giai_ngan_thuc ) 
                                FROM
                                    history_view 
                                WHERE
                                    tctd_id_hist = id_tctd 
                                    AND tctd_id_hist IS NOT NULL 
                                    AND MONTH ( ngay_gui_ho_so ) = thang 
                                    AND YEAR ( ngay_gui_ho_so ) = nam 
                                ) AS giai_ngan_thuc,
                                a.comment AS note
                            FROM
                                history_view h
                                LEFT JOIN lenders l ON l.id = h.tctd_id_hist
                                LEFT JOIN accountant_note a ON a.tctd_id = h.tctd_id_hist 
                            WHERE
                                h.tctd_id_hist IS NOT NULL 
                                AND h.tctd_id_hist != ''
                                $search
                            GROUP BY
                                h.tctd_id_hist,l.name, a.comment,
                                MONTH ( h.ngay_gui_ho_so ),
                                YEAR ( h.ngay_gui_ho_so )"); 
        // return $history;
        return view('accountant.report_tctd.browse' , compact('history','tctd','tctd_input','year'));
    }
    public function save_note_kt_tctd(Request $req)
    {
        $param = $req->all();
        // return $param;
        $check = AcounttantNote::where([['date_note',$param['date_note']],['tctd_id',$param['tctd_id']],['role_id',$param['role_id']]])->count();
        // return $check;
        if ($check > 0) {
            if ( $param['note'] != "") {
               $update = AcounttantNote::where([['date_note',$param['date_note']],['tctd_id',$param['tctd_id']]])->update(['comment' => $param['note']]);
                if ($update = true) {
                    return redirect('admin/report-credit-institutions');
                }
            }
            else
            {
                echo "<script>alert('Không được dể trống trường ghi chú')</script>";
                return redirect('admin/report-credit-institutions');
            }
        }
        else
        {
            if ($param['note'] != "") {
                $data = array('date_note' => $param['date_note'],
                                'comment' => $param['note'],
                                'tctd_id' => $param['tctd_id'],
                                'role_id' => $param['role_id']
                                 );
                $create = AcounttantNote::create($data);
                if ($create = true) {
                     return redirect('admin/report-credit-institutions');
                }
            }
            else
            {
                echo "<script>alert('Không được dể trống trường ghi chú')</script>";
                return redirect('admin/report-credit-institutions');
            }
        }
    }

}
