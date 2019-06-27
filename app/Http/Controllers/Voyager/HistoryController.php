<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CicLog;
use App\User;
use App\HistoryLog_View;
use App\HistoryLog;
use Mail;
use App\Mail\SendMail;
use App\Mail\SendMailToMafc;
use App\LogsStatus;
use App\UserTax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Lender;
use App\Provincial;
use App\LenderResults;
use App\UserGroup;
use App\Referal;
use Excel;
use App\Support;
use DB;


class HistoryController extends Controller
{

    protected $View = [];

    /**
     * Manager contract customers
     * @param $request
     * @return array
     */
    public function index(Request $request){
        if(!Auth::user()) $this->redirect(); 

        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        $search = [];

        $this->View['agence'] = User::whereIn('role_id',[8,1,10])->get()->pluck('name','id');
        $this->View['agence'][0]="Chọn Agencies";
        
        $this->View['team_cs'] = $this->team_cs();
        $this->View['tctd'] = Lender::where('status',1)->get()->pluck('name','id');
        $this->View['tctd'][0]="Chọn tổ chức tín dụng";
        
        $this->View['logsstatus'] = LogsStatus::get();
        $this->View['zzz']=LogsStatus::where("status","1")->get()->pluck("name","id");
        $this->View['zzz'][0]="Chọn trạng thái";

        $search['zzz'] = $search['agence'] = $search['tctd'] = 0;
        $lead = array(
            '' => 'Chọn',
            '0' => 'Un QLead',
            '1' => 'QLead'
        );

        $this->View['qlead'] = $lead;
        $xxx=array(
                ""=>"Tất cả hồ sơ",
                "1"=>"Hồ sơ vay ",
                "2"=>"Hồ sơ chấm điểm"
             
        );

        $this->View['xxx'] = $xxx;
        $diem = array(
                ""=>"Chọn",
                "1"=>"Điểm từ thấp đến cao ",
                "2"=>"Điểm từ cao đến thấp",
                "3"=>"Khoản vay từ thấp đến cao ",
                "4"=>"Khoản vay từ cao đến thấp"
        );

        $this->View['point'] = $diem;
         $show = array(
            "50"    => '50',
            "100"   => '100',
            "200"   => '200',
            "300"   => '300'
        );

        $this->View['show'] = $show;
        
        
        $sql = 'status = 1';
        $sort = ['created_at','desc'];
        if($request->isMethod("get")){
            $search['agence']       = $request->input('agence',0);
            $search['tctd']         = $request->input('tctd',0);
            $search['xxx']          = $request->input('xxx',0);

            $search['zzz']          = $request->input('zzz',0);
            $search['key']          = $request->input('key','');
            $search['from_date_tn'] = $request->input('from_date_tn','');
            $search['to_date_tn']   = $request->input('to_date_tn','');
            $search['point']        = $request->input('point','');
            $search['show']         = $request->input('show',50);
            $search['qlead']        = $request->input('qlead','');


            if(!empty($search['xxx'])){
                if($search['xxx'] === '1'){
                    $sql= $sql." AND history_log_id IS NOT NULL"; 
                }
                if($search['xxx'] === '2' ){
                    $sql= $sql." AND history_log_id IS NULL AND progress_info = 5"; 
                } 
            }
            if(!empty($search['tctd'])){
                $sql = $sql." AND tctd_id_hist = '{$search['tctd']}'";
            }
            if(!empty($search['agence'])){
                $sql = $sql." AND user_ref = '{$search['agence']}'";
            }
            if(!empty($search['zzz'])){
                $sql = $sql." AND trangthai = {$search['zzz']}";   
            }
            if(!empty($search['key'] )){
                $sql = $sql." AND (cmnd LIKE '%{$search['key']}%' OR phone LIKE '%{$search['key']}%' OR name LIKE '%{$search['key']}%' )";
            }
            if(!empty($search['from_date_tn'])){
                $sql = $sql." AND created_at >=  '{$search['from_date_tn']} 00:00:00' ";
            }
            if(!empty($search['to_date_tn'])){
                $sql = $sql." AND created_at <=  '{$search['to_date_tn']} 23:59:59' ";
            }
            if(!empty($search['qlead'])){
                $sql = $sql." AND qlead = {$search['qlead']}";
            }
            if(!empty($search['point'])){
                if($search['point'] === '1'){
                    $sort = ['final_score','asc'];
                }
                if($search['point'] === '2' ){
                    $sort = ['final_score','desc'];
                } 
                if($search['point'] === '3'){
                    $sort = ['khoanvay','asc'];
                }
                if($search['point'] === '4' ){
                    $sort = ['khoanvay','desc'];
                }
            }
            
            
        }

        $this->View['search']=$search;
        // end search


        if ($role_id == 7) {
            $this->View['history'] = HistoryLog_View::where('history_log_id','!=',null)->orderBy($sort)->paginate(50);
           

        }
        else if($role_id !== 1 && $role_id !== 4 && $role_id !== 5 && $role_id !== 6)
        {
            $this->View['history'] = HistoryLog_View::whereRaw($sql)->where('user_ref',$user_id)->orderBy($sort[0],$sort[1])->paginate($search['show']);
            $this->View['total_chichamdiem'] = HistoryLog_View::whereRaw($sql)->where([['progress_info','=',5],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $this->View['total_gui'] = HistoryLog_View::whereRaw($sql)->where([['history_log_id','!=',null],['user_ref','=',$user_id]])->count();
        }
        else
        {
            // role Admin
            $this->View['history'] = HistoryLog_View::whereRaw($sql)->orderBy($sort[0],$sort[1])->paginate($search['show']);
            $this->View['total_chichamdiem'] = HistoryLog_View::whereRaw($sql)->where([['progress_info','=',5],['history_log_id','=',null],['status','=',1]])->distinct('cmnd')->count();
            $this->View['total_gui'] = HistoryLog_View::whereRaw($sql)->where([['progress_info','>',0],['history_log_id','!=',null],['status','=',1]])->distinct('cmnd')->count();
        }
        // return $history;
        
        return view('admin.history.browse',$this->View);
        
    }


    public function getExportPhone(Request $request){
        
        $name_excel="report-swifi-".date("d-m-Y");
        Excel::create($name_excel,function($excel) use ($request){
            $excel->sheet('Danh sách', function($sheet) use ($request) {
               
               
                $result[]=["No.","PHONE"];
                $TData = Support::distinct('phone')->where("ref_name",'S-wifi')->get()->toArray();
                
                
                $i =1;
                foreach($TData as $k => $v) {
                    $value=(array)$v;
                    $check = HistoryLog_View::where('phone',$v['phone'])->first();
                    if(empty($check)){
                       
                        $result[]=array(
                                $i++,
                                $value['phone']
                                

                        ); 
                    }
                    
                    
                }
                $sheet->fromArray($result);
                },'UTF-8');

        })->download('xlsx');
    }
    public function lender_results_create($tctd_id,$content,$status,$log_id)
    {
        $create_lender = array(
                    'tctd_id' =>  $tctd_id,
                    'content' => $content, 
                    'status' => $status,
                    'log_id' => $log_id
                );
        $create = LenderResults::create($create_lender);
        if ($create) {
            return true;
        }
        else
        {
            return false;
        }
    }
                                                
    public function getImportList(Request $request){
         
        if($request->isMethod("post")){
            if($request->hasFile('excel')){
               
                $path = $request->file('excel')->getRealPath();
                
                $data = \Excel::load($path)->get();
                $tctd_id = $request->input('tctd');

                if($data->count() > 0){
                    
                    if($tctd_id == 2){

                        foreach ($data as $key => $value) {
                                    
                            $id_cic = CicLog::where('cmnd',$value->cmt)->first();
                            if(!empty($id_cic)){
                                $history_id = HistoryLog::where('log_id',$id_cic->id)->first();

                                if(!empty($history_id->id)){
                                    $check = LenderResults::where('log_id',$history_id->id)->where('tctd_id',$tctd_id)->first();
                                    switch (true) {
                                        // hsv đã gửi qua TCTD => trạng thái = 8 (hsv đã gửi qua TCTD)
                                        case ($value->result == 'ok' || $value->result == "" ) :
                                            if(empty($check)){
                                                $create_lender = array(
                                                    'tctd_id' =>  $tctd_id,
                                                    'content' => 'OK',
                                                    'status' => 8,
                                                    'log_id' => $history_id->id
                                                );
                                           
                                                $this->create_lender_results($create_lender);    
                                            }else{
                                                $update_lender = LenderResults::where('log_id',$history_id->id)->where('tctd_id',$tctd_id)->update(['content'=>"OK",'status'=>8]);
                                            }
                                            $update_status = HistoryLog::where('log_id',$id_cic->id)->update(['status'=>8,'tctd_id'=>$tctd_id]);
                                        break;
                                        // hsv bị trùng => trạng thái = 6 (hsv bị trùng)
                                        case ($value->result == 'Cleansing' || $value->result == 'cleansing' ):
                                            if(empty($check)){
                                                $create_lender = array(
                                                    'tctd_id' =>  $tctd_id,
                                                    'content' => $value->cleansing, 
                                                    'status' => 6,
                                                    'log_id' => $history_id->id
                                                );
                                                
                                                $this->create_lender_results($create_lender);    
                                            }else{
                                                $update_lender = LenderResults::where('log_id',$history_id->id)->where('tctd_id',$tctd_id)->update(['content'=>$value->cleansing,'status'=>6]);
                                            }
                                            $update_status = HistoryLog::where('log_id',$id_cic->id)->update(['status'=>6,'tctd_id'=>$tctd_id]);
                                        break;

                                        
                                    }

                                }
                                
                            }    
                        
                        }
                    }
                    if($tctd_id == 7){
                        DB::beginTransaction();
                        foreach ($data as $key => $value) {
                            // hồ sơ trùng 
                            if($value->ket_qua_import){
                                try {
                                    $id_cic = CicLog::where('cmnd',$value->cmnd)->first();
                                    if(!empty($id_cic)){
                                        $history_id = HistoryLog::where('log_id',$id_cic->id)->first();
                                        
                                        if(!empty($history_id->id)){
                                            $check = LenderResults::where('log_id',$history_id->id)->where('tctd_id',$tctd_id)->first();
                                            if(empty($check)){
                                                $create_lender = array(
                                                    'tctd_id' =>  $tctd_id,
                                                    'content' => $value->ket_qua_import, 
                                                    'status' => 6,
                                                    'log_id' => $history_id->id
                                                );
                                                
                                                DB::table('lender_results')->insert($create_lender);    
                                            }else{
                                                DB::table('lender_results')->where('log_id',$history_id->id)->where('tctd_id',$tctd_id)->update(['content'=>$value->ket_qua_import,'status'=>6]);
                                            } 
                                        }
                                            DB::table('history_log')->where('log_id',$id_cic->id)->update(['status'=>6,'tctd_id'=>$tctd_id]);


                                    }
                                    
                                    DB::commit();
                                } catch (Exception $e) {
                                    DB::rollBack();
                                    
                                    throw new Exception($e->getMessage());
                                }
                            }else{
                                $result = $this->statusdetail($value->last_tel_status_detail);
                                $id_cic = CicLog::where('cmnd',$value->id_card)->first();
                                if(!empty($id_cic)){

                                    $history_id = HistoryLog::where('log_id',$id_cic->id)->first();
                                    
                                    if(!empty($history_id->id)){

                                        $check = LenderResults::where('log_id',$history_id->id)->where('tctd_id',$tctd_id)->first();
                                        switch ($result) {
                                            case 'Đang xử lý':
                                                if(empty($check)){
                                                    $this->lender_results_create($tctd_id,$value->last_tel_status_detail,8,$history_id->id);    
                                                }else{
                                                    LenderResults::where([['log_id',$history_id->id],['tctd_id',$tctd_id]])->update(['content'=>$value->last_tel_status_detail,'status'=>8]);
                                                }
                                                DB::table('history_log')->where('log_id',$id_cic->id)->update(['status'=>8,'tctd_id'=>$tctd_id]);
                                                break;

                                            case 'Từ chối':
                                                if(empty($check)){
                                                    $this->lender_results_create($tctd_id,$value->last_tel_status_detail,9,$history_id->id);   
                                                }else{
                                                    LenderResults::where([['log_id',$history_id->id],['tctd_id',$tctd_id]])->update(['content'=>$value->last_tel_status_detail,'status'=>9]);
                                                }
                                                DB::table('history_log')->where('log_id',$id_cic->id)->update(['status'=>9,'tctd_id'=>$tctd_id]);
                                                break;

                                            case 'Không thỏa khu vực, sản phẩm':
                                                if(empty($check)){
                                                    $this->lender_results_create($tctd_id,$value->last_tel_status_detail,3,$history_id->id);    
                                                }else{
                                                    LenderResults::where([['log_id',$history_id->id],['tctd_id',$tctd_id]])->update(['content'=>$value->last_tel_status_detail,'status'=>3]);
                                                }
                                                DB::table('history_log')->where('log_id',$id_cic->id)->update(['status'=>9,'tctd_id'=>$tctd_id]);
                                                break;
                                            
                                            case 'Cic xấu':
                                                if(empty($check)){
                                                    $this->lender_results_create($tctd_id,$value->last_tel_status_detail,4,$history_id->id);    
                                                }else{
                                                    LenderResults::where([['log_id',$history_id->id],['tctd_id',$tctd_id]])->update(['content'=>$value->last_tel_status_detail,'status'=>4]);
                                                }
                                                DB::table('history_log')->where('log_id',$id_cic->id)->update(['status'=>4,'tctd_id'=>$tctd_id]);
                                                break;

                                            case 'Đã giải ngân':
                                                if(empty($check)){
                                                    $this->lender_results_create($tctd_id,$value->last_tel_status_detail,7,$history_id->id);    
                                                }else{
                                                    LenderResults::where([['log_id',$history_id->id],['tctd_id',$tctd_id]])->update(['content'=>$value->last_tel_status_detail,'status'=>7]);
                                                }
                                                DB::table('history_log')->where('log_id',$id_cic->id)->update(['status'=>7,'tctd_id'=>$tctd_id]);
                                                break;

                                            case 'Có khoản vay':
                                                DB::table('cic_logs')->where('cmnd',$id_cic->cmnd)->update(['debt'=>'SHB']);
                                                break;
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }

                    echo "<script>alert('Import thành công !');</script>";
                    return redirect()->back(); 
                }else{
                    echo "<script>alert('File excel không không có dữ liệu!');</script>";
                    return redirect()->back();  
                }
                
            }else{
                echo "<script>alert('File excel không hợp lệ!');</script>";
                return redirect()->back(); 
            }

        }
    }
    public function statusdetail($status){
        $content = array(
            'Khách hàng không có nhu cầu' => 'Từ chối',
            'Không có nhu cầu - sản phẩm không đáp ứng' => 'Không thỏa khu vực, sản phẩm',
            'Không có nhu cầu - từ chối trước khi sales tư vấn SP' => 'Từ chối',
            'Từ chối sau khi sales đã tư vấn SP' => 'Không thỏa khu vực, sản phẩm',
            'KH không thuộc khu vực hỗ trợ' => 'Không thỏa khu vực, sản phẩm',
            'Khách hàng chưa đủ tuổi /quá tuổi quy định' => 'Không thỏa khu vực, sản phẩm',
            'Khách hàng có nợ từ 3 tổ chức tín dụng trở lên' => 'Từ chối',
            'Khách hàng đang có khoản vay với SHB Finance' => 'Có khoản vay',
            'Khách hàng không biết chữ - không đọc & viết được' => 'Từ chối',
            'Khách hàng không thỏa điều kiện sản phẩm' => 'Không thỏa khu vực, sản phẩm',
            'Khách hàng thuộc Black list /CIC xấu' => 'Cic xấu',
            'Khách hàng phàn nàn' => 'Từ chối',
            'Khách hàng yêu cầu không gọi lại' => 'Từ chối',
            'Chờ khách hàng chuẩn bị giấy tờ' => 'Đang xử lý',
            'Đang tiến hành với sales khác' => 'Đang xử lý',
            'Hủy Lead' => 'Từ chối',
            'Khách hàng chuẩn bị hồ sơ xong - chuyển CR' => 'Đang xử lý',
            'Cần so sánh SP các công ty khác' => 'Đang xử lý',
            'Cần tham khảo với gia đình' => 'Đang xử lý',
            'Cần thời gian cân nhắc ' => 'Đang xử lý',
            'Chưa kết nối được thuê bao' => 'Đang xử lý',
            'Hủy do không kết nối được' => 'Từ chối',
            'Khách hàng không bắt máy' => 'Từ chối',
            'Số điện thoại không có thực' => 'Từ chối',
            'Khách hàng yêu cầu gọi lại sau - đang họp /đang đi đường' => 'Đang xử lý',
            'Khách hàng yêu cầu gọi lại sau - khi có chương trình khuyến mại' => 'Đang xử lý',
            'Người khác bắt máy - yêu cầu gọi lại khách hàng sau' => 'Đang xử lý',
            'Courier bổ sung hồ sơ' => 'Đang xử lý',
            'Courier xử lý' => 'Đang xử lý',
            'Đã tạo App' => 'Đang xử lý',
            'Hồ sơ trả lại sale' => 'Đang xử lý',
            'Courier đã đặt lịch hẹn với KH' => 'Đang xử lý',
            'CR đang hoàn thành App' => 'Đang xử lý',
            'KH chưa chuẩn bị hồ sơ' => 'Đang xử lý',
            'KH chưa sắp xếp được thời gian gặp Courier trong thời gian quy định' => 'Đang xử lý',
            'KH hủy khoản vay' => 'Từ chối',
            'KH không nghe máy' => 'Đang xử lý',
            'Nhờ Telesales tư vấn lại' => 'Đang xử lý',
            'AF_PROCESSING' => 'Đang xử lý',
            'AF_REJECT' => 'Từ chối',
            'AP_REJECT' => 'Từ chối',
            'CA_PROCESSING' => 'Đang xử lý',
            'CB_PROCESSING' => 'Đang xử lý',
            'CB_REJECT' => 'Từ chối',
            'DC_PROCESSING' => 'Đang xử lý',
            'DC_REJECT' => 'Từ chối',
            'DE_LEAD_PROCESSING' => 'Đang xử lý',
            'DE_PROCESSING' => 'Đang xử lý',
            'DONE' => 'Đã giải ngân',
            'FA_PROCESSING' => 'Đang xử lý',
            'OP1_PROCESSING' => 'Đang xử lý',
            'SALE_ABORT' => 'Từ chối',
            'SALE_PROCESSING' => 'Đang xử lý',
            'SYSTEM_ABORT' => 'Từ chối'
        );

        $income = isset($content[$status]) ? $content[$status] : '';
        return  $income;
    }                                   
    
    /**
    * Trash HSV
    * @param $id
    * @return update status HSV
    */

    public function trash_history($id){

        $status = HistoryLog_View::where('id',$id)->first();
        $lender = LenderResults::where('log_id',$id)->first();
        //check điều kiện
        if($status['trangthai'] == 2){
            echo "<script>alert('Hồ sơ đã được duyệt . Không được remove!');</script>";
            return redirect('admin/history');
        }
        else if(!empty($lender)){
            echo "<script>alert('Hồ sơ đã được chuyển qua TCTD!');</script>";
            return redirect('admin/history');
        }
        else{
            //update status
            $update_status = CicLog::where('id',$id)->update(['status'=>0]);
            echo "<script>alert('Hồ sơ vay đã được di chuyển tới thùng rác');</script>";
            return redirect('admin/history');
        }
        
    }

    /**
    * Trash list HSV
    * @param request
    * @return list trash HSV (status = 1)
    */
    public function trash_list(Request $request){
        if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        $this->View['agence'] = User::where('role_id',8)->get()->pluck('name','id');
        $this->View['agence'][0]="Chọn Agencies";
        $this->View['team_cs'] = $this->team_cs();
        $this->View['tctd'] = Lender::get();
        $this->View['logsstatus'] = LogsStatus::get();
        $this->View['zzz']=LogsStatus::where("status","1")->get()->pluck("name","id");
        $this->View['zzz'][0]="Chọn trạng thái";
        $search['zzz'] = $search['agence'] = 0;

        $xxx=array(
                ""=>"Tất cả hồ sơ",
                "1"=>"Hồ sơ vay ",
                "2"=>"Hồ sơ chấm điểm"
             
        );
        $this->View['xxx'] = $xxx;
        $diem = array(
                ""=>"Chọn",
                "1"=>"Điểm từ thấp đến cao ",
                "2"=>"Điểm từ cao đến thấp",
                "3"=>"Khoản vay từ thấp đến cao ",
                "4"=>"Khoản vay từ cao đến thấp"
        );
        $this->View['point'] = $diem;
        
        // start search
        $search = [];
        $sql = ' status = 0 ';
        $sort = ['ngay_gui_ho_so','created_at','desc'];
        if($request->isMethod("get")){
            $search['agence']       = $request->input('agence',0);
            $search['zzz']          = $request->input('zzz',0);
            $search['key']          = $request->input('key','');
            $search['from_date_tn'] = $request->input('from_date_tn','');
            $search['to_date_tn']   = $request->input('to_date_tn','');
            $search['point']        = $request->input('point','');
           

            if(!empty($search['zzz'])){
                $sql = $sql." AND trangthai = {$search['zzz']}";   
            }
            if(!empty($search['key'] )){
                $sql = $sql." AND (cmnd LIKE '%{$search['key']}%' OR phone LIKE '%{$search['key']}%' OR name LIKE '%{$search['key']}%' )";
            }
            if(!empty($search['from_date_tn'])){
                $sql = $sql." AND created_at >=  '{$search['from_date_tn']} 00:00:00' ";
            }
            if(!empty($search['to_date_tn'])){
                $sql = $sql." AND created_at <=  '{$search['to_date_tn']} 23:59:59' ";
            }
            if(!empty($search['point'])){
                if($search['point'] === '1'){
                    $sort = ['final_score','asc'];
                }
                if($search['point'] === '2' ){
                    $sort = ['final_score','desc'];
                } 
                if($search['point'] === '3'){
                    $sort = ['khoanvay','asc'];
                }
                if($search['point'] === '4' ){
                    $sort = ['khoanvay','desc'];
                }
            }
            
            
        }

        $this->View['search']=$search;
        // end search


        if ($role_id == 7) {
            $this->View['history'] = HistoryLog_View::where('history_log_id','!=',null)->orderBy($sort)->paginate(15);
           

        }
        else if($role_id !== 1 && $role_id !== 4 && $role_id !== 5 && $role_id !== 6)
        {
            $this->View['history'] = HistoryLog_View::where('user_ref',$user_id)->orderBy('ngay_gui_ho_so','created_at','desc')->paginate(15);
            $this->View['total_chichamdiem'] = HistoryLog_View::whereRaw($sql)->where([['progress_info','=',0],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $this->View['total_gui'] = HistoryLog_View::whereRaw($sql)->where([['history_log_id','!=',null],['user_ref','=',$user_id]])->count();
        }
        else
        {
            $this->View['history'] = HistoryLog_View::whereRaw($sql)->orderBy($sort[0],$sort[1])->paginate(15);
            $this->View['total_chichamdiem'] = HistoryLog_View::whereRaw($sql)->where([['progress_info','=',0],['history_log_id','=',null]])->count();
            $this->View['total_gui'] = HistoryLog_View::whereRaw($sql)->where([['progress_info','>',0],['history_log_id','!=',null]])->count();
           
        }
        return view('admin.trash.browse',$this->View);
    }
    /**
     * @param $id
     * @return [delete hs]
     */
    public function deleteTrash($id){
        
        if(!empty($id)){
            $cic_delete = CicLog::find($id)->delete();
            $history_delete = HistoryLog::where('log_id',$id)->delete();
        }
        return true;
    }



    /**
    * @param $id
    * @return restore status HSV (status = 1)
    */
    public function restore_history($id){
        if(!empty($id)){
            $restore = CicLog::where('id',$id)->update(['status'=>1]);
        }
    }

     /**
     * @param  Request
     * @return restore multi hsv
     */
    public function restore_multi(Request $request){
        $id = $request->input('restoreid');
        $id   = explode(",", $id);
        if(!empty($id)){
            $restore = CicLog::whereIn('id',$id)->update(['status'=>1]);
            echo "<script>alert('Hồ sơ vay đã được phục hồi');</script>";
            return redirect('/admin/trashlist/list');
            
        }
    }
     /**
    * Export excel
    * seteptep 1 -> get hsv limit 1000
    * loop goi toi api check lead
    * if lead ok thi add mang moi
    * export excel 
    * @param $request
    * @return array

    */


    public function getExport(Request $request){

        $name_excel="Mirae_".date("d-m-Y");
        Excel::create($name_excel,function($excel) use ($request){
            $excel->sheet('Danh sách', function($sheet) use ($request) {
               // get id
                $idxx = $request->input('excelid');
                $id   = explode(",", $idxx);
                // api
                $phone = rand(1000000000,9999999999);
                $timestamp = strtotime(date('Y-m-d H:m:s'));
                $vendorid = 10;

                $result[]=["TransactionID","Họ tên","Số điện thoại 1","Điện thoại 2","CMT","Địa chỉ","Năm sinh","Mức thu nhập","Nguồn thu nhập","Nhu Cầu Vay","Email","Agencies","point","Date"];
                $TData = HistoryLog_View::select('cmnd','address1','income','age','history_log_id','name','phone','agencies','final_score','khoanvay','created_at')
                                        ->whereIn('history_log_id',$id)->orderBy('created_at','DESC')->get()->toArray(); 
                
                $provinve = Provincial::pluck('name','matp');
                $loan = array(
                    "Từ 0 Tới 1 Triệu VNĐ" => "1000000",
                    "Từ 1 Tới 2 Triệu VNĐ" => "2000000",
                    "Từ 2 Tới 3 Triệu VNĐ" => "3000000",
                    "Từ 3 Tới 4 Triệu VNĐ" => "4000000",
                    "Từ 4 Tới 5 Triệu VNĐ" => "5000000",
                    "Từ 5 Tới 6 Triệu VNĐ" => "6000000",
                    "Từ 6 Tới 7 Triệu VNĐ" => "7000000",
                    "Từ 7 Tới 8 Triệu VNĐ" => "8000000",
                    "Từ 8 Tới 9 Triệu VNĐ" => "9000000",
                    "Từ 9 Tới 10 Triệu VNĐ" => "10000000",
                    "Từ 10 Tới 12 Triệu VNĐ" => "12000000",
                    "Từ 12 Tới 15 Triệu VNĐ" => "15000000",
                    "Từ 15 đến 20 Triệu VNĐ" => "20000000",
                    "Từ 20 Tới 30 Triệu VNĐ" => "30000000",
                    "Từ 30 Tới 40 Triệu VNĐ" => "40000000",
                    "Từ 40 Tới 60 Triệu VNĐ" => "60000000",
                    "Từ 60 Tới 100 Triệu VNĐ" => "100000000",
                    "Lớn hơn 100 Triệu VNĐ" => " 100000000"
                );
                foreach($TData as $k => $v) {
                   
                    $value=(array)$v;
                   

                    $addr = unserialize($v['address1']);
                    $add = $addr['matp_address'];
                    $address = isset($provinve[$add]) ? $provinve[$add] :'';
                    $incom = isset($loan[$v['income']]) ? $loan[$v['income']] :'';
                    $newDate = date("Y", strtotime($v['age']));
                    if($newDate == '1970'){
                        $newDate = '' ;
                    }
                    if($value['final_score'] > 0){
                        $point = $value['final_score'];
                    }else{
                        $point = '0';
                    }
                    

                    $result[]=array(
                          $value['history_log_id'],
                          $value['name'],
                          $value['phone'],
                          '', 
                          $value['cmnd'] ,
                          $address,
                          $newDate,
                          $incom,
                          '',
                          $value['khoanvay'],
                          '',
                          $value['agencies'],
                          $point,
                          $value['created_at']

                    );

                }
                $sheet->fromArray($result, null, 'A1', false, false);
                },'UTF-8');

        })->download('xlsx');
        
    }





    public function team_cs()
    {
        return UserGroup::get();
    }
    /**
    *Update cic note admin
    *@param $form_date1 , $to_date1 , $form_date1 , $to_date1 , $status
    *@return $data
    **/
    public function search_data_history_month(Request $request,$from , $to)
    {
        if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;

        $this->View['logsstatus'] = LogsStatus::get();
        $this->View['team_cs'] = $this->team_cs();

        $xxx=array(
                ""=>"Tất cả hồ sơ",
                "1"=>"Hồ sơ vay ",
                "2"=>"Hồ sơ chấm điểm"
        );
        $this->View['xxx'] = $xxx;
        $this->View['zzz']=LogsStatus::where("status","1")->get()->pluck("name","id");
        $this->View['zzz'][0]="Chọn trạng thái";
        $diem = array(
                ""=>"Chọn",
                "1"=>"Điểm từ thấp đến cao ",
                "2"=>"Điểm từ cao đến thấp",
                "3"=>"Khoản vay từ thấp đến cao ",
                "4"=>"Khoản vay từ cao đến thấp"
        );
        $this->View['point'] = $diem;
        //search
        $search = [];
        $sql = "created_at >=  '{$from} 00:00:00' AND created_at <=  '{$to} 23:59:59' ";
        $sort = ['ngay_gui_ho_so','created_at','desc'];
        if($request->isMethod("get")){
            $search['xxx']          = $request->input('xxx');
            $search['zzz']          = $request->input('zzz','');
            $search['key']          = $request->input('key','');
            $search['from_date_tn'] = $request->input('from_date_tn','');
            $search['to_date_tn']   = $request->input('to_date_tn','');
            $search['point']        = $request->input('point','');
            if(!empty($search['xxx'])){
                if($search['xxx'] === '1'){
                    $sql= $sql." AND history_log_id IS NOT NULL"; 
                }
                if($search['xxx'] === '2' ){
                    $sql= $sql." AND history_log_id IS NULL AND progress_info = 5"; 
                } 
            }
            
            if(!empty($search['zzz'])){
                $sql = $sql." AND trangthai = {$search['zzz']}";   
            }
            if(!empty($search['key'] )){
                $sql = $sql." AND (cmnd LIKE '%{$search['key']}%' OR phone LIKE '%{$search['key']}%' OR name LIKE '%{$search['key']}%' )";
            }
            if(!empty($search['from_date_tn'])){
                $sql = $sql." AND created_at >=  '{$search['from_date_tn']} 00:00:00' ";
            }
            if(!empty($search['to_date_tn'])){
                $sql = $sql." AND created_at <=  '{$search['to_date_tn']} 23:59:59' ";
            }
            if(!empty($search['point'])){
                if($search['point'] === '1'){
                    $sort = ['final_score','asc'];
                }
                if($search['point'] === '2' ){
                    $sort = ['final_score','desc'];
                } 
                if($search['point'] === '3'){
                    $sort = ['khoanvay','asc'];
                }
                if($search['point'] === '4' ){
                    $sort = ['khoanvay','desc'];
                }
            }
            
        }

        $this->View['search']=$search;
        //end search 

        if ($role_id !== 1 && $role_id !== 4) {
            $this->View['total_chichamdiem'] = HistoryLog_View::whereRaw($sql)->where([['progress_info','=',5],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $this->View['total_gui'] = HistoryLog_View::whereRaw($sql)->where([['history_log_id','!=',null],['user_ref','=',$user_id]])->count();
            $this->View['history'] = HistoryLog_View::whereRaw($sql)->orderBy('ngay_gui_ho_so','created_at','desc')->paginate(20);
        }
        else
        {
            $this->View['total_chichamdiem'] = HistoryLog_View::where([['progress_info','=',5],['history_log_id','=',null]])->where([['created_at','>=',$from],['created_at','<=',$to]])->count();
            $this->View['total_gui'] = HistoryLog_View::whereRaw($sql)->where([['progress_info','>',0],['history_log_id','!=',null]])->count();
            $this->View['history'] = HistoryLog_View::whereRaw($sql)->orderBy('ngay_gui_ho_so','created_at','desc')->paginate(20);
        }
        
        return view('admin.history.browse',$this->View);
    }


    
    /**
    *Update cic note admin
    *@param $id , $cmnd
    *@return data [user_tax,logsstatus,history_log]
    */
    public function view_history_log($id,$cmnd)
    {   
        $lender_results = LenderResults::where('log_id','=',$id)->orderBy('created_at','desc')->get();
        $user_tax = UserTax::where('cmnd','=',$cmnd)->orWhere('cccd',$cmnd)->limit(1)->get();
        $logsstatus = LogsStatus::get();
        $tctd = Lender::get();
        $history_log = HistoryLog_View::where([['history_log_id','=',$id],['cmnd','=',$cmnd]])->orWhere([['id','=',$id],['cmnd','=',$cmnd]])->limit(1)->get();
        // return $lender_results[];
        return view('admin.history.edit-add',compact('history_log','logsstatus','user_tax', 'tctd','lender_results'));
    }

    /**
    *Update cic note admin || hoang custom
    *@param log , $cic , $id ,data ,email
    *@return true || false
    */ 
    public function update_log($log , $cic , $id ,$data , $email , $data_send_mail)
    {
        if ($log !== null) {
            $save = HistoryLog::find($id)->update($data);
            if ($save === true) {
                if ($email != "") {
                    Mail::to($email)->queue(new SendMail($data_send_mail));
                } 
                return true;
            }
            else
            {
                return false;
            }
        }
        elseif($cic !== null)
        {
            $save_c = CicLog::find($id)->update($data);
            if ($save_c === true) {
                if ($email != "") {
                    Mail::to($email)->queue(new SendMail($data_send_mail));
                }
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    /**
    *Update cic note admin || hoang custom
    *@param Request , $id , $cmnd
    *@return save data to history_log or cic_log
    */ 
    public function update_note_admin(Request $req , $id , $cm)
    {

        if(!Auth::user()){
            $this->redirect();    
        }
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $param = $req->all();
        $chk = $req->input('to_chuc_tin_dung');
        ($chk != "") ?  $tctd_id = $chk : $tctd_id = "";

        $log = HistoryLog::find($id);
        $cic = CicLog::find($id);

        $data = ['hoten' => $param['hoten'],
                 'email'=> $param['email'],
                 'cmnd' => $param['cmnd'],
                 'phone' => $param['sdt'],
                 'status' => $param['status'],
                 'note' => $param['admin_note']
                ];
        // return $data;
        $update_log = array(
                 'tctd_id' => $tctd_id,
                 'notes'=> $param['admin_note'],
                 'status' => $param['status'],
                 'cs_id' => $user_id,
                 'cs_name' => $user_name
                    );
        $count = LenderResults::where('log_id',$id)->count();
        $data_1 = LenderResults::where('log_id',$id)->orderBy('created_at','desc')->get();

        if ($param['status'] == 2 ) {
            if ($count == 0) {
                echo "<script>alert('Hồ sơ này chưa được gửi đến TCTD nào! Thao tác này không thực hiện được');</script>";
                return redirect('admin/history/view-edit/'.$id.'/'.$param['cmnd'].'');  
            }
            if ($count > 0 && $data_1[0]['status'] !== 2) {
                echo "<script>alert('Hồ sơ này chưa được TCTD duyệt! Thao tác này không thực hiện được');</script>";
                return redirect('admin/history/view-edit/'.$id.'/'.$param['cmnd'].'');  
            }
            if ($count > 0 && $data_1[0]['status'] === 2) {
                if ($this->update_log($log,$cic,$id,$update_log,$param['email'],$data)) {
                    return redirect('admin/history');  
                }
                else
                {
                    echo "<script>alert('Không thành công !');</script>";
                    return redirect('admin/history/view-edit/'.$id.'/'.$param['cmnd'].''); 
                }
            }
        }
        else
        {
            if ($this->update_log($log,$cic,$id,$update_log,$param['email'],$data)) {
                return redirect('admin/history');  
            }
            else
            {
                echo "<script>alert('Không thành công !');</script>";
                return redirect('admin/history/view-edit/'.$id.'/'.$param['cmnd'].''); 
            }
        }

    }


    /**
    *Update cic note admin || do vu dev
    *@param log , $cic , $id ,data ,email
    *@return true || false
    */ 
    public function update_log_multi($log , $cic , $id ,$data , $email , $data_send_mail,$status)
    {
        if ($log !== null) {
            
            // $save = HistoryLog::find($id)->update($data);
            $save = HistoryLog::whereIn('id',$id)->update($data);
            
            if ($save !== null) {
                if($status === 2){
                    if ($email != "") {
                        Mail::to($email)->queue(new SendMail($data_send_mail));
                    }
                    return true;    
                }
                 
                return true;
            }
            else
            {
               
                return false;
            }
        }
        elseif($cic !== null)
        {
            // $save_c = CicLog::find($id)->update($data);
            $save_c = CicLog::whereIn('id',$id)->update($data);
            if ($save_c === null) {
                if($status === 2){
                    if ($email != "") {
                        Mail::to($email)->queue(new SendMail($data_send_mail));
                    }
                    return true;    
                }
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    /**
    *Update cic note multi admin || dovu  dev
    *@param Request , $id , $cmnd
    *@return save data to history_log or cic_log 
    */ 
    public function update_note_admin_multi(Request $request){
        if(!Auth::user()){
            $this->redirect();    
        }
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $log_id =  $request->input('approve_id');
        $status = $request->input('status');
        $pieces = explode(",", $log_id);
        $isset_history = HistoryLog_View::whereIn('history_log_id',$pieces)->get()->toArray();
        $data = array();
        $update_log = array();
        foreach ($isset_history as $key => $v) {
            $value=(array)$v;
            $log = HistoryLog::find($v['history_log_id']);
            $cic = CicLog::find($v['history_log_id']);
       
            $count = LenderResults::where('log_id',$v['history_log_id'])->count();
            $data_1 = LenderResults::where('log_id',$v['history_log_id'])->orderBy('created_at','desc')->get();

           
            $data=array(
                    'hoten'  => $value['name'],
                    'email'  => $value['email'],
                    'cmnd'   => $value['cmnd'],
                    'phone'  => $value['phone'],
                    'status' => $request->input('status'),
                    'note'   => $request->input('admin_note')
                );
            
            $update_log = array(
                     'tctd_id'  => '',
                     'notes'    => $request->input('admin_note'),
                     'status'   => $request->input('status'),
                     'cs_id'    => $user_id,
                     'cs_name'  => $user_name
                    );
            
            if($request->input('status') == 2 ) {
                if ($count == 0) {
                    echo "<script>alert('Hồ sơ này chưa được gửi đến TCTD nào! Thao tác này không thực hiện được');</script>";
                    return redirect('admin/history');  
                }
                if ($count > 0 && $data_1[0]['status'] !== 2) {
                    echo "<script>alert('Hồ sơ này chưa được TCTD duyệt! Thao tác này không thực hiện được');</script>";
                    return redirect('admin/history');  
                }
                if ($count > 0 && $data_1[0]['status'] === 2) {
                    if ($this->update_log_multi($log,$cic,$pieces,$update_log,$value['email'],$data,$status)) {
                        return redirect('admin/history');  
                    }
                    else
                    {
                        echo "<script>alert('Không thành công !');</script>";
                        return redirect('admin/history'); 
                    }
                }
            }
            else
            {
                if ($this->update_log_multi($log,$cic,$pieces,$update_log,$value['email'],$data,$status)) {
                    echo "<script>alert('Thành công !');</script>";
                    return redirect('admin/history');  
                }
                else
                {
                    echo "<script>alert('Không thành công !');</script>";
                    return redirect('admin/history/'); 
                }
            }

        }
    }




    /******************************************************************/
    /******************************************************************/
    /****************Function get data API - GET ********************/
    /******************************************************************/
    /******************************************************************/
    public function curl_invoke_tax_get($url,$header)
    {

        $ch = curl_init ($url);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 80 );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );

        $str = curl_exec ( $ch );
        curl_close ( $ch );
        $results = json_decode($str,true);

        return $results;
    }
    /******************************************************************/
    /******************************************************************/
    /****************Function get data API - POST ********************/
    /******************************************************************/
    /******************************************************************/
    public function curl_invoke_tax($url,$data){
        // return $this->build_post_fields($data);
        // return $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // SSL important
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($response,true);

        return $results;
    }


    /******************************************************************************/
    /******************************************************************************/
    /*******************Function gửi dữ liệu đến api tctd  ************************/
    /******************************************************************************/
    /***************************************************************************
    1   OCB
    2   Mirae Asset
    3   Home Credit
    4   FE Credit
    5   VPBank
    */
    public function get_data_api_partner($id_tctd,$data)
    {
        $url_1 = 'http://creditnow.vn/api/api/v2/sendLead';
        $url_2 = '';
        $url_3 = '';
        $url_4 = '';
        $url_5 = '';
        $url_6 = '';

        if ($id_tctd == 1) {
            $data = $this->curl_invoke_tax($url_1,$data);
            if ($data['Code'] !== 200) {
                return $data['Message'];
            }
            else
            {
                return $data['Data'];
            }
        }
    }    
    /******************************************************************/
    /******************************************************************/
    /*******************Function TÍNH TUỔI age  ************************/
    /******************************************************************/
    /******************************************************************/
    public function calcu_age($data)
    {
        $created_at = strtotime($data);
        $current_time = strtotime(date('Y-m-d H:i:s'));
        $interval  = abs($current_time - $created_at);
        $age  = round($interval / ((60*60*24*30*7) + (60*60*24*31*5)));  
        return $age;
    }

    /******************************************************************/
    /******************************************************************/
    /*******************Function CHECK ĐỊA CHỈ TCTD HT*******************/
    /******************************************************************/
    /******************************************************************/
    public function check_addr($code, $data)
    {
        for ($i=0; $i < count($data); $i++) { 
            if($code == $data[$i])
            {
               return false;
            }
            else
            {
                return true;
            }
        }
    }


    /******************************************************************/
    /******************************************************************/
    /*******************Function tính ngày*******************/
    /******************************************************************/
    /******************************************************************/
    public function calcu_day($date)
    {
        $created_at = strtotime($date);
        $current_time = strtotime(date('Y-m-d H:i:s'));
        $interval  = abs($current_time - $created_at);
        $day  = round($interval / (60*60*24));
        return $day;
    }
    /**
    *Update cic note admin || hoang custom
    *@param Request
    *@return save data to table LenderResults
    */ 
    public function send_credit_inst(Request $req)
    {
        $param   =  $req->all();
        // return $param;
        $province = Provincial::where('matp',$param['addr_id_send'])->get();
        (isset($province[0]['province_id']) && $province[0]['province_id']) ? $code = $province[0]['province_id'] : $code ="";

        $tctd_id =  $param['tctd_send'] ;
        $log_id  =  $param['log_send'];
        $log_qr = $param['log_id'];
        $cmnd    =  $param['cmnd_send'];
        $phone    =  $param['phone_send'];
        $name    =  $param['name_send'];
        $loan = str_replace(',','',$param['loan']);

        $data_check_dk = CicLog::where('id',$log_qr)->orderBy('created_at','desc')->get();
        /*Tính tuổi người vay*/
        if ($data_check_dk[0]['age'] !== '1770-01-01 00:00:00') {
            $age = $this->calcu_age($data_check_dk[0]['age']);
        }
        else
        {
            $age = 0;
        }
        $results = substr($data_check_dk[0]['cid_result'], 0,-11);  

        /***********************dữ liệu gửi đến tctd************************/
        if ((int)$tctd_id == 1) {
            $data  = array(
                        'User' => 'VE70000028',
                        'Pass' => 'Lemon@123!',
                        'Phonenumber'   => $phone,
                        'Source'   => 'SMS',
                        'NationalId'   => $cmnd,
                        'FullName'   => '',
                        'Province'   => $code,
                        'CampaignCode' => 'OCB',
                        'MetaData' => json_encode(array(
                                            'RequestLoanAmount' => $loan
                                        ))
                    );
            $data_send = array(
                                'cmnd' => $cmnd,
                                'results' => $results,
                                'code' => $code,
                                'age' => $age,
                                'data' => $data,
                                'tctd_id' => $tctd_id,
                                'log_id' => $log_id
                            );
        }
        if ((int)$tctd_id == 2) {
            $data_m_a = array(
                            'cmnd' => $cmnd,
                            'phone' => $phone,
                            'cmnd' => $cmnd,
                            'tctd_id' => $tctd_id,
                            'log_id' => $log_id
                        );
            $data_mail = array(
                                'hoten' => $name ,
                                'cmnd' => $cmnd ,
                                'phone' => $phone ,
                                'khoan_vay' => $param['loan'],
                                'addr' => $param['addr_send_u'],
                                'age' => $this->calcu_age($param['age_send']),
                                'thunhap' => $param['thunhap_send']
                            );
        }
        // return $data_mail;
        /********************************************************************/
        $count = LenderResults::where([['log_id',$log_id],['tctd_id',$tctd_id]])->count();
        $data_check_iset = LenderResults::where('log_id',$log_id)->orderBy('created_at','desc')->get();
        $api_tctd = Lender::select('api')->where('id',$tctd_id)->limit(1)->get();
        $isset_history = HistoryLog_View::where([['history_log_id',$log_id],['cmnd',$cmnd]])->count();

        if ($api_tctd[0]['api'] == 0) { /*check nếu tctd chưa kết nối api thì thông báo */
            echo "<script>alert('Hiện tại chưa kết nối API với TCTD này.');</script>";
            return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
        }
        else /*nếu đã kết nối thì tiếp tục*/
        {
            if ($isset_history == 0) { /*kiểm tra profile này đã gửi hồ sơ vay hay chưa || trường hợp chưa*/
                echo "<script>alert('Hồ sơ này chưa gửi yêu cầu vay! không được chuyển đến tổ chức tín dụng.');</script>";
                return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
            }
            else /*nếu đã gửi hỗ sơ vay*/
            {
                if (isset($data_check_iset[0])) /*nếu tồn tại dữ liệu rồi*/
                {
                    $created_at = strtotime($data_check_iset[0]['created_at']);
                    $current_time = strtotime(date('Y-m-d H:i:s'));
                    $interval  = abs($current_time - $created_at);
                    $day  = round($interval / (60*60*24));
                    if ($count > 0 && $day < 60) /*đã gửi đến 1 tổ chức tín dụng và thời gian gửi nhỏ hơn 60 ngày*/
                    {
                        echo "<script>alert('Hồ sơ này đã chuyển đến tổ chức tín dụng! Vui lòng chờ để biết kết quả. Nếu hồ sơ quá 60 ngày mà TCTD không phản hồi có thể chuyển đến TCTD khác');</script>";
                        return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
                    }
                    if ($count > 0 && $data_check_iset[0]['status'] == 2) /*đã gửi đến 1 tổ chức tín dụng và đã được duyệt vay */
                    {
                        echo "<script>alert('Hồ sơ đã được TCTD duyệt vay! Không được gửi đến 1 TCTD khác');</script>";
                        return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
                    }
                    if ($count === 0 && $day < 60 && $data_check_iset[0]['status'] !== 2)  /*chưa gửi đến 1 tổ chức tín dụng chưa đủ 60 ngày chưa đc duyệt */
                    {
                        echo "<script>alert('Hồ sơ này đã chuyển đến tổ chức tín dụng! Vui lòng chờ để biết kết quả. Nếu hồ sơ quá 60 ngày mà TCTD không phản hồi có thể chuyển đến TCTD khác');</script>";
                        return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
                    }
                    if ($count > 0 && $day > 60 && $data_check_iset[0]['status'] !== 2)  /*chưa gửi đến 1 tổ chức tín dụng chưa đủ 60 ngày chưa đc duyệt */
                    {
                        echo "<script>alert('Hồ sơ này đã chuyển đến TCTD này và không được duyệt! Vui lòng gửi đến TCTD khác.');</script>";
                        return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
                    }
                    if ($count === 0 && $day > 60 && $data_check_iset[0]['status'] !== 2) /*chưa gửi đến 1 tổ chức tín dụng đã đủ 60 ngày chưa đc duyệt */
                    {
                       if ((int)$tctd_id == 1) {
                            return $this->check_conditions_ocb($data_send);
                        }
                        if ((int)$tctd_id == 2) {
                            return $this->check_conditions_mirae_asset($data_m_a,$data_mail);
                        }
                    }
                }
                else /*chưa tồn tại dữ liệu*/
                {
                    if ((int)$tctd_id == 1) { /*start api ocb*/
                        return $this->check_conditions_ocb($data_send);
                    } /*end api ocb*/
                    if ((int)$tctd_id == 2) {
                        return $this->check_conditions_mirae_asset($data_m_a,$data_mail);
                    }  
                }
            }
        }
    }

    /**
    * check điều kiện và gửi dữ liệu tại ocb
    *@param 
    *@return  
    */
    public function check_conditions_ocb($param)
    {
        $check_pronvince_ocb = array('1000','2100','1850','1600','1700','1750','1800','1300','1950','1900','2250','2150','2200','4000','4050'); /*danh sách tỉnh không thuộc hỗ trợ cho vay của ocb*/
        if ($param['results'] == 'Khách hàng hiện đang quan hệ tại 5 TCTD, không có nợ cần chú ý và không có nợ xấu tại thời điểm cuối tháng'
            || $param['results'] == 'Khách hàng hiện đang quan hệ tại 3 TCTD, không có nợ cần chú ý và không có nợ xấu tại thời điểm cuối tháng'
            || $param['results'] == 'Khách hàng hiện đang quan hệ tại 4 TCTD, không có nợ cần chú ý và không có nợ xấu tại thời điểm cuối tháng' ) {
            echo "<script>alert('Khách hàng đang trong tình trạng cảnh báo hoặc có nợ xấu ! không được duyệt');</script>";
            return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].''); 
            // return 1; /*không được vay do đang nợ*/
        }
        else
        {
            if ($this->check_addr($param['code'],$check_pronvince_ocb) == false) {
                echo "<script>alert('Khách hàng sinh sống tại tỉnh/tp mà TCTD này không hỗ trợ cho vay.');</script>";
                return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
                 // return 2;  /*sống tại tỉnh tp không hỗ trợ vay*/
            }
            else
            {
                if ($param['age'] <= 20 || $param['age'] >= 62 ) {
                    echo "<script>alert('Hồ sơ không đủ tuổi vay.');</script>";
                    return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
                    // return 3; /*hồ sơ k đủ tuổi để vay*/
                }
                else
                {
                    $mesage_ = $this->get_data_api_partner($param['tctd_id'],$param['data']);
                    $create_lender = array(
                                        'tctd_id' =>  $param['tctd_id'],
                                        'content' => $mesage_['ErrMsg'],
                                        'status' => $mesage_['ErrCode'],
                                        'log_id' => $param['log_id']
                                      );
                    if ($this->create_lender_results($create_lender) == true) {
                        echo "<script>alert('Gửi thành công.');</script>";
                        return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
                    }
                    else
                    {
                        echo "<script>alert('Gửi thất bại.');</script>";
                        return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
                    }
                }
            }
        } 
    }

    /**
    * check điều kiện và gửi dữ liệu tại mirae asset
    *@param 
    *@return  
    */
    public function check_conditions_mirae_asset($param , $data_mail)
    {
        $phone = rand(1000000000,9999999999);
        $timestamp = strtotime(date('Y-m-d H:m:s'));
        $vendorid = 10;
        $url = 'http://leads.mafcvn.vn:8081/api/leads-generator?id='.$param['cmnd'].'&phone='.$phone.'&vendorid='.$vendorid.'&timestamp='.$timestamp ;
        $key = $param['cmnd'].''.$phone.''.$vendorid.''.$timestamp.'CXqffsQtvqWayT5GarGL';
        $header = array('AuthorizationToken : '.sha1($key).'');
        $fields = array (
            'id' => $param['cmnd'],
            'phone' => '', 
            'vendorid' => 10 ,
            'timestamp' => $timestamp 
        );
        // return sha1($key);
        $data = $this->curl_invoke_tax_get($url,$header);
        if($data['result'] == true)
        {
            $create_lender = array(
                                'tctd_id' =>  $param['tctd_id'],
                                'content' => $data['message'],
                                'status' => $data['errorcode'],
                                'log_id' => $param['log_id']
                              );
            if ($this->create_lender_results($create_lender) == true) {
                Mail::to(['tuoi.tran@mafc.com.vn','thao.quach@mafc.com.vn','tuyen.tq@fibo.vn'])->queue(new SendMailToMafc($data_mail));
                echo "<script>alert('Gửi thành công.');</script>";
                return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
            }
            else
            {
                echo "<script>alert('Gửi thất bại.');</script>";
                return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
            }
        }
        else
        {
            echo "<script>alert('Gửi thất bại.');</script>";
            return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
        }
    }

    /********************************************************************************/
    /********************************************************************************/
    /*******************Hàm lưu dữ liệu vào tb lender_results************************/
    /********************************************************************************/
    /********************************************************************************/
    public function create_lender_results($data)
    {
        $create = LenderResults::create($data);
        if ($create) {
            return true;
        }
        else
        {
            return false;
        }
    }


    /********************************************************************************/
    /********************************************************************************/
    /*******************Hàm lưu só tiền giải ngân thực của kế toán********************/
    /********************************************************************************/
    /********************************************************************************/
    public function savemoney($id , Request $req)
    {
        $money = $req->input('tiengiainganthuc');
        $update = HistoryLog::where('id',$id)->update(['so_tien_giai_ngan_thuc' => $money]);
        if ($update == true) {
            return redirect('admin/history');
        }
    }
    /*function test*/
    public function get_DB()
    {
        $email = 'tranquangtuyengss@gmail.com';
        $verify_code = 1334;
        $log = CicLog::where([['email', '=', 'admin@admin.com'],['verify_code','=','1334']])->limit(1)->get()->toArray();
        echo "<pre>";
        echo json_encode($log);
        
        // echo json_encode($log);
    }


    /**
     *@param Reqest
     *@return success or fails 
     */
    public function send_to_cs_team(Request $req)
    {
        $param = $req->all();
        // return $param;
        if (isset($param['name']) && isset($param['group_u']) && isset($param['state']) && $param['group_u'] != null && $param['state'] !== null) {
            for ($i=0; $i < count($param['name']) ; $i++) { 
                if ($param['name'][count($param['name'])-1] == NULL) {
                    echo "<script>alert('Bạn chỉ được chọn hồ sơ đã gửi hồ sơ vay! kiểm tra lại.');</script>";
                    return redirect('admin/history');            
                }
                else
                {
                    HistoryLog::whereIn('id',$param['name'])->update(['user_group_id' => $param['group_u'] , 'cs_id' => $param['state']]);
                    echo "<script>alert('Thành công!');</script>";
                    return redirect('admin/history');
                }
            }
        /**
         * có id gửi lên 
         */
        }
        else
        {
            echo "<script>alert('Vui lòng chọn hồ sơ vay và team-cs + cs user để chuyển hồ sơ vay đến!');</script>";
            return redirect('admin/history');
        }
    }



    public function getUser_teAm($id) {
        $states = User::where("group_id",$id)->select(['id','name'])->get();
        return response()->json(['result' => $states], 200);
    }
}
