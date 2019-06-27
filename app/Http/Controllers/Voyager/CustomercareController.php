<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CicLog;
use App\User;
use App\UserGroup;
use App\HistoryLog_View;
use App\HistoryLog;
use Mail;
use File;
use App\Mail\SendMailAgencyUser;
use App\LogsStatus;
use App\UserTax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Lender;
use App\LenderCs;
use App\LenderResults;
use App\LenderStatus;
use App\Agencys;
use App\AgencyView;
use App\PartnerReportView;
use Hash;
use App\Exports\ReportExcelMcs;
use App\Exports\ReportExcelMcsAgency;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\CustomerCareView;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;



class CustomercareController extends Controller
{
	public function manager_parrtner_tctd()
	{
		if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;

		$user = User::where('role_id',5)->get();
		$lender_status = LenderStatus::get();
		if ($role_id == 5) {
			$lender = Lender::where('cs_id',$user_id)->paginate(10);
		}
		else
		{
			$lender = Lender::paginate(10);
		}
		// return $lender;
		return view('cs_mcs.manager_user.browse',compact('lender','user','lender_status'));
	}


	public function update_tctd(Request $req)
	{
		$param =  $req->all();
		$data = array(
					'address' => $param['address'],
					'support' => $param['suport'],
					'status' => $param['status'],
					'cs_id' => $param['cs_id']
						);
		$update = Lender::find($param['id'])->update($data);
		if ($update == true) {
			return redirect('admin/manager-partner-cs'); 
		}
	}



	public function add_tctd(Request $req)
	{
		$param = $req->all();
		// return $param;
		$count = Lender::where('name',$param['name'])->orWhere('description',$param['fullname'])->count();
		if ($count > 0) {
			echo "<script>alert('Đã tồn tại tổ chức tín dụng này. Không thêm mới được');</script>";
            return redirect('admin/manager-partner-cs'); 
		}
		else
		{
			$data = array(
					'name' => $param['name'] ,
					'description' => $param['fullname'] ,
					'status' => $param['status_add'] ,
					'api' => $param['api_add'] ,
					'address' => $param['addr'] ,
					'support' => $param['people'] ,
					'ratio_share' => $param['ratio_share'] ,
					'sdt' => $param['phone'] ,
					'email' => $param['email'] ,
					'cs_id' => $param['cs_id'] 
			);
			$data_user = array(
								'role_id' => 9,
								'name' => $param['people'],
								'email' => $param['email'],
								'avatar' => 'users/default.png',
								'password' => Hash::make($param['pass']),
								'active' => 1
							);
			$create = Lender::create($data);
			if ($create == true) {
				if ($this->create_user($data_user) == true) {
					Mail::to($param['email'])->queue(new SendMailAgencyUser($data_user));
					echo "<script>alert('Thành công!');</script>";
            		return redirect('admin/manager-partner-cs'); 
				}
			}
			else
			{
				echo "<script>alert('Thất bại!');</script>";
            	return redirect('admin/manager-partner-cs');
			}
		}
	}

	public function calcu_day($from , $to)
    {
        $created_at = strtotime($from);
        $current_time = strtotime($to);
        $interval  = abs($current_time - $created_at);
        $day  = round($interval / (60*60*24));
        return $day;
    }

	public function view_partner_cs($id , Request $req)
	{
		$param = $req->all();
		(isset($param['from_date_tn']) && $param['from_date_tn'] != "") ? $from = $param['from_date_tn'] : $from = date('Y').'-01-01' ;
		(isset($param['to_date_tn']) && $param['to_date_tn'] != "") ? $to = $param['to_date_tn'] : $to = date('Y').'-12-31' ;
		$day =  $this->calcu_day($from,$to);
		// return $day;
		($day > 30 ) ? $date ="date_format(c.ngay_gui_tctd,'%m-%Y')" : $date ="date_format(c.ngay_gui_tctd,'%d-%m-%Y')" ;
		($day > 30 ) ? $date_1 ="date_format(ngay_gui_tctd,'%m-%Y')" : $date_1 ="date_format(ngay_gui_tctd,'%d-%m-%Y')" ;
		($day > 30 ) ? $date_from = date('m-Y', strtotime($from)) : $date_from = date('d-m-Y', strtotime($from)) ;
		($day > 30 ) ? $date_to = date('m-Y', strtotime($to)) : $date_to = date('d-m-Y', strtotime($to)) ;
		$lender = Lender::get();
		$cs = User::where('role_id',5);
		$history  = DB::table('lenders_cs')
						->select([
					        'lenders_cs.id',
					        'lenders_cs.lender_id',
					        'lenders_cs.agenci_id',
					        'lenders_cs.cs_id',
					        'lenders_cs.job',
					        'lenders_cs.contact',
					        'lenders_cs.note',
					        'lenders_cs.file',
					        'lenders_cs.created_at',
					        'lenders_cs.updated_at',
					        'lenders.name',
					        'lenders.description',
					        'lenders.support',
					        'lenders.email',
				    	])
						->leftJoin('lenders', 'lenders_cs.lender_id', '=', 'lenders.id')
						->where('lenders_cs.lender_id',$id)
						->orderBy('lenders_cs.created_at', 'desc')
						->paginate(10);
		// return $history;
		$report =  	DB::select("
								SELECT 
									$date as ngaygui,
									c.tctd_id as tctd_id,
									c.contract as contract,
									c.tctd_name as tctd_name,
									c.description as description,
									(SELECT count(history_log_id) FROM cs_mcs_view WHERE tctd_id = $id AND $date_1 = ngaygui) as tong_lead ,
									(SELECT count(status_tctd) FROM cs_mcs_view WHERE tctd_id = $id AND status_tctd = 2) as tong_giai_ngan ,
									ROUND((
										(SELECT count(status_tctd) FROM cs_mcs_view WHERE tctd_id = $id AND status_tctd = 2)/(SELECT count(history_log_id) FROM cs_mcs_view WHERE tctd_id = $id)
									)* 100 , 1) as tile_thanh_cong,
									(SELECT SUM(tien_giai_ngan) FROM cs_mcs_view WHERE tctd_id = $id AND status_tctd = 2) as so_tien_giai_ngan,
									c.cs_name as nguoi_phu_trach
								FROM cs_mcs_view c
								WHERE 
									c.tctd_id = $id 
								GROUP BY 
								$date 
								");
		// return $report;
		return view('cs_mcs.manager_user.edit-add',compact('report','id','lender','cs','history','from','to'));
	}




	public function save_cs_job($id,Request $req)
	{
		if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
		$param =  $req->all();
		$data = array(
						'lender_id' => $id, 
						'job' => $param['job'],
						'note' => $param['note'],
						'cs_id' => $user_id
						);
		if ($param['id_result'] !== null) {
			if($req->hasFile('photo')){
				$file = $req->file('photo');
				$fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
				$data['file'] = $fileName; 
				// return $data;
				$move = $file->move('public/uploads/', $fileName); 
	      	  	if ($move == true) {
	      	  		LenderCs::find($param['id_result'])->update($data);
	      	  		return redirect('admin/manager-partner-cs/'.$id);
	      	  	}
	       	}
	       	else
	       	{
	       		LenderCs::find($param['id_result'])->update($data);
				return redirect('admin/manager-partner-cs/'.$id);
	       	}
		}
		else
		{
			if($req->hasFile('photo')){
				$file = $req->file('photo');
				$fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
				$data['file'] = $fileName; 
				// return $data;
				$move = $file->move('public/uploads/', $fileName); 
	      	  	if ($move == true) {
	      	  		LenderCs::create($data);
	      	  		return redirect('admin/manager-partner-cs/'.$id);
	      	  	}
	       	}
	       	else
	       	{
	       		LenderCs::create($data);
				return redirect('admin/manager-partner-cs/'.$id);
	       	}
		}
	}




	public function manager_agency(Request $req)
	{
		if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        if ($role_id != 5) {
        	$agency = DB::table('agency_view')
							->select([
									'id_agency',
									DB::raw('GROUP_CONCAT(DISTINCT ref_name SEPARATOR ", " ) AS ref_name'),
									'name_agency',
									DB::raw('GROUP_CONCAT(DISTINCT CONCAT("https://me.creditscore.vn/?ref=", slug, "") SEPARATOR ", ") as slug'),
									'email_u_a AS nguoi_lien_lac_user',
									DB::raw('COUNT(history_log_id) AS total_hsv'),
									DB::raw('GROUP_CONCAT(nhcv SEPARATOR ", " ) AS ngan_hang_cho_vay'),
									'phone_a AS phone_agency',
									'email_a AS nguoi_lien_lac_agency',
									'ratio_share AS ratio_share',
									DB::raw('(SELECT count(trangthai) FROM `agency_view` WHERE `trangthai` = 2 )  hsv_giaingan'),
									DB::raw('SUM(so_tien_giai_ngan) AS so_tien_giai_ngan'),
									DB::raw('SUM(so_tien_giai_ngan_thuc) AS so_tien_giai_ngan_thuc'),
									'cs_id as cs_id'
									])
							->groupBy('id_agency')
							->get();
        }
        else
        {
        	$agency = DB::table('agency_view')
						->select([
								'id_agency',
								DB::raw('GROUP_CONCAT(DISTINCT ref_name SEPARATOR ", " ) AS ref_name'),
								'name_agency',
								DB::raw('GROUP_CONCAT(DISTINCT CONCAT("https://me.creditscore.vn/?ref=", slug, "") SEPARATOR ", ") as slug'),
								'email_u_a AS nguoi_lien_lac_user',
								DB::raw('COUNT(history_log_id) AS total_hsv'),
								DB::raw('GROUP_CONCAT(nhcv SEPARATOR ", " ) AS ngan_hang_cho_vay'),
								'phone_a AS phone_agency',
								'email_a AS nguoi_lien_lac_agency',
								'ratio_share AS ratio_share',
								DB::raw('(SELECT count(trangthai) FROM `agency_view` WHERE `trangthai` = 2 )  hsv_giaingan'),
								DB::raw('SUM(so_tien_giai_ngan) AS so_tien_giai_ngan'),
								DB::raw('SUM(so_tien_giai_ngan_thuc) AS so_tien_giai_ngan_thuc'),
								'cs_id as cs_id'
								])
						->where('cs_id',$user_id)
						->groupBy('id_agency')
						->get();
        }
		
		// return $agency;
		$user = User::where('role_id',5)->get();
		return view('cs_mcs.manager_agency.browse' , compact('agency','user'));
	}



	public function update_agency(Request $req)
	{
		$param = $req->all();	
		// return $param;
		Agencys::where('id',$param['id'])->update(['cs_id' => $param['cs_id']]);
		return redirect('admin/agency');
	}


	public function add_new_agency(Request $req)
	{
		$param = $req->all();
		$data_agency = array(
							'name' => $param['name'] ,
							'support' => $param['name_user'] ,
							'sdt' => $param['phone'] ,
							'email' => $param['email'] ,
							'cs_id' => $param['cs_id'] ,
							'ratio_share' => $param['ratio_share'] ,
							);
		$data_user = array(
							'role_id' => 8,
							'name' => $param['name_user'],
							'email' => $param['email'],
							'avatar' => 'users/default.png',
							'password' => Hash::make($param['pass']),
							'active' => 1
							);
		$check_isset = Agencys::where('name',$param['name'])->count();
		// return $check_isset;
		if ($check_isset > 0) {
			echo "<script>alert('Đã tồn tại partner này! không thể thêm được.');</script>";
            return redirect('admin/agency'); 
		}
		else
		{
			if ($this->create_agency($data_agency) == true) {
				if ($this->create_user($data_user) == true) {
					Mail::to($param['email'])->queue(new SendMailAgencyUser($data_user));
					echo "<script>alert('Thêm thành công');</script>";
            		return redirect('admin/agency');
				}
				else
				{
					Agencys::where('name',$param['name'])->delete();
					echo "<script>alert('Hệ thống gặp lỗi không thể tạo');</script>";
            		return redirect('admin/agency');
				}
			}
			else
			{
				echo "<script>alert('Hệ thống gặp lỗi không thể tạo');</script>";
            	return redirect('admin/agency');
			}
		}
	}
	public function create_agency($data)
	{
		return Agencys::create($data);
	}
	public function create_user($data)
	{
		return User::create($data);
	}



	/**
	 * repor m-cs 
	 */
	public function report()
	{
		return DB::table('partner_report_mcs_view')
						->select([
								'tctd_id as tctd_id',
								'tctd_name as tctd_name',
								'tctd_description as tctd_description',
								'tctd_logo as tctd_logo',
								'status as status',
								'api as api',
								'ratio_share as ratio_share',
								DB::raw('((SUM(so_tien_giai_ngan)*ratio_share)/100) as khoan_phai_tra'),
								'address as address',
								'support as support',
								'sdt as sdt',
								'email as email',
								'cs_name as cs_name',
								DB::raw('count(log_id) as lead'),
								DB::raw('(SELECT count(trangthai) FROM `partner_report_mcs_view` WHERE `trangthai` = 2 ) as success'),
								DB::raw('SUM(khoanvay) as khoanvay'),
								DB::raw('SUM(so_tien_giai_ngan) as so_tien_giai_ngan'),
								DB::raw('SUM(so_tien_giai_ngan_thuc) as so_tien_giai_ngan_thuc')
								])
						// ->where(DB::raw("(DATE_FORMAT(ngay_gui_ho_so,'%Y'))"),$year_s)
						->groupBy('tctd_id')
						->get();
	}


	public function report_mcs_chart($year)
	{
		return DB::table('partner_report_mcs_view')
						->select([
								'tctd_id as tctd_id',
								'tctd_name as tctd_name',
								'tctd_description as tctd_description',
								'tctd_logo as tctd_logo',
								'status as status',
								'api as api',
								'address as address',
								'support as support',
								'sdt as sdt',
								'email as email',
								'cs_name as cs_name',
								DB::raw('count(log_id) as lead'),
								DB::raw('(SELECT count(trangthai) FROM `partner_report_mcs_view` WHERE `trangthai` = 2 ) as success'),
								DB::raw('SUM(khoanvay) as khoanvay'),
								DB::raw('SUM(so_tien_giai_ngan) as so_tien_giai_ngan'),
								DB::raw('SUM(so_tien_giai_ngan_thuc) as so_tien_giai_ngan_thuc')
								])
						->where(DB::raw("(DATE_FORMAT(ngay_gui_ho_so,'%Y'))"),$year)
						->groupBy('tctd_id')
						->get();
	}

	public function report_agnecy()
	{
		return DB::table('agencys_report_mcs_view')
					->select([
						'agency_name as agency_name',
						DB::raw('GROUP_CONCAT(DISTINCT ref_name_r SEPARATOR ", " ) AS ref_name'),
						DB::raw('count(history_log_id) as lead'),
						DB::raw('(SELECT count(history_log_id) FROM `agencys_report_mcs_view` WHERE `status` = 2 ) as success'),
						DB::raw('SUM(so_tien_giai_ngan) as so_tien_giai_ngan'),
						'ratio_share as ratio_share',
						DB::raw('((SUM(so_tien_giai_ngan)*ratio_share)/100) as khoan_phai_tra'),
						'support as support'
					])
					->groupBy('agency_name','ratio_share','support')
					->get();
	}
	public function report_mcs(Request $req)
	{	

		$param = $req->all();
		(isset($param['year']) && $param['year'] !== '') ? $year_s = $param['year'] : $year_s = date('Y');

		$report_partner = $this->report();
		$report_agnecy = $this->report_agnecy();
		$report_partner_1 = $this->report_mcs_chart($year_s);

		// return $report_agnecy;
		return view('cs_mcs.report.browse',compact('report_partner_1','report_partner','year_s','report_agnecy'));
	}

	public function report_partner()
	{
		return Excel::download(new ReportExcelMcs, 'thong_tin_bao_cao_partner.xlsx');
	}
	public function report_agency()
	{
		return Excel::download(new ReportExcelMcsAgency, 'thong_tin_bao_cao_agency.xlsx');
	}



	/**
	 * quản lý user cs
	 */
	public function manager_user_cs($value='')
	{
		# code...
		if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        if ($role_id == 5) {
        	// echo "<script>alert('Bạn không có quyền thực hiện chức năng này');</script>"; 
    		header( 'Location: http://' . $_SERVER['HTTP_HOST'] . '/BackEnd/admin' );
        }
        $group = UserGroup::all();
        $user = DB::table('users')
        				->select([
        					'users.id as id',
        					'users.name as name',
        					'users.email as email',
        					'users.avatar as avatar',
        					'users.phone as phone',
        					'user_group.title as group_name',
        				])
        				->where('role_id',5)
        				->leftJoin('user_group','users.group_id','=','user_group.id')->get();
		return view('cs_mcs.manager_usercs.browse',compact('user','group'));
	}
}

