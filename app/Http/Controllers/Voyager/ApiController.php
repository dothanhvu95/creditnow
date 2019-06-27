<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Mail\SendMail;
use App\Mail\SendMailToMafc;
use App\Http\Controllers\stdClass;
use Illuminate\Bus\Queueable;
use App\Mail\EmailInfo;
use App\Mail\CicMail;
use App\CicLog;
use App\Api_Log;
use App\User;
use App\HistoryLog_View;
use App\HistoryLog;
use App\LogsStatus;
use App\UserTax;
use App\Tax;
use App\Lender;
use App\Provincial;
use App\LenderResults;
use App\UserGroup;
use App\Referal;
use App\Support;
use App\VerifyCode;
use App\CicConfig;
use App\CicDeclare;
use App\CicResult;
use App\CicProfile;
use App\CicExt;
use App\History;
use App\HistoryExt;
use Carbon\Carbon;
use Excel;
use DB;
use Mail;
use Validator;
use City;


class ApiController extends Controller
{
	protected $View=[];
    /**
    * Đối soát ADPIA 
    * @param $request
    * @return list
    */
    public function formChamDiem(Request $request){
        if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        $price = Auth::user()->price;
        $rq = Auth::user()->request;
        $balance = Auth::user()->balance;
        $date = $current_time = date('Y-m-d H:i:s');
        
        
        if($request->isMethod("post")){
                
                $validater=Validator::make($request->all(),[
                    "name"   => "required",
                    "phone"  => "required",
                    
                    
                ],[
                    'name.required'     =>"Vui lòng nhập họ tên. ",
                    'phone.required'     =>"Vui lòng nhập số điện thoại. ",
                    
                ]);
                if($validater->fails()){
                    return redirect()->back()->withErrors($validater)->withInput();
                }else{
                    
                  
                    if (!empty($request->input('cccd'))) {
                        $cmt = $request->input('cccd');
                    }else{
                        $cmt = $request->input('cmnd');
                    } 
                    
                 
                    $check = CicLog::where('cmnd',$cmt)->first();
                    if(empty($check)){
                        $response = $this->scoring($cmt);
                    
                        $data = array(
                            'name'  => $request->input('name'),
                            'cmnd'  => $request->input('cmnd'),
                            'phone' => $request->input('phone'),
                            'cccd'  => $request->input('cccd'),
                            'score' => isset($response['point']) ? $response['point'] : 0,
                            'interest_rate'     => isset($response['interest_rate']) ? $response['interest_rate'] : 0,
                            'loan'              => isset($response['loan']) ? $response['loan'] : 0,
                            'duration'          => isset($response['duration']) ? $response['duration'] : 0,
                            'status' => 1,
                            'referal_id' => 30,
                            'content' =>'OK. Deduct money',
                            'user_id'            => $user_id
                        );
                      
                        $cic_log = array(
                            'phone_id' => $request->input('phone'),
                            'name'  => $request->input('name'),
                            'phone' => $request->input('phone'),
                            'cmnd'  => $cmt,
                            'cid_result'=> $response['content'],
                            'final_score' => isset($response['point']) ? $response['point'] : 0,
                            'interest_rate'     => isset($response['interest_rate']) ? $response['interest_rate'] : 0,
                            'loan'              => isset($response['loan']) ? $response['loan'] : 0,
                            'duration'          => isset($response['duration']) ? $response['duration'] : 0,
                            'cic_reload' => 0, //1 reloaded
                            'self_declare_point' => 0,
                            'status' => 1,
                            'api_key' => "",
                            'referal_id' => 30,
                            'cid_customer_name' => $request->input('name'),
                            'cid_customer_address' => "",
                            'cid_customer_tell' => $request->input('phone'),
                            'user_id' => $user_id
                        );
                       
                        $sss = Api_Log::create($data);
                        CicLog::create($cic_log);
                        if($balance > 0 ){
                            $discount = $balance - 33000;    
                        }else{
                            $discount = $price - 33000;
                        }
                        
                        $updateprice = User::where('id',$user_id)->update(['request'=>$rq+1,'balance'=>$discount]);
                        $this->View['check'] = Api_Log::where('cmnd',$request->input('cmnd'))->whereOr('cccd',$request->input('cccd'))->first();
                        $request->session()->flash("success","Chấm điểm thành công " );
                    }else{
                        $check_api = Api_Log::where('cmnd',$check['cmnd'])->whereOr('cccd',$check['cmnd'])->first();
                        if(empty($check_api)){
                            $data = array(
                                'name'  => isset($check['name']) ? $check['name'] : $check['cid_customer_name'],
                                'cmnd'  => $check['cmnd'],
                                'phone' => $check['phone'],
                                'score' => isset($check['final_score']) ? $check['final_score'] : 0,
                                'interest_rate'     => isset($check['interest_rate']) ? $check['interest_rate'] : 0,
                                'loan'              => isset($check['loan']) ? $check['loan'] : 0,
                                'duration'          => isset($check['duration']) ? $check['duration'] : 0,
                                'referal_id' => $check['referal_id'],
                                'content' => 'Had credit scoring. Do not deduct money',
                                'user_id'            => $user_id 
                            );
                            Api_Log::create($data); 
                        }else{
                            $up_api = Api_Log::where('cmnd',$check['cmnd'])->whereOr('cccd',$check['cmnd'])->update(['created_at'=>$date]);
                        }
                        
                        $up = CicLog::where('cmnd',$cmt)->update(['created_at'=>$date]);
                        $this->View['check'] = Api_Log::where('cmnd',$request->input('cmnd'))->whereOr('cccd',$request->input('cccd'))->first();
                        $request->session()->flash("success","Chứng minh thư này đã được chấm rồi." );
                    }



                    
                   
                   

                }
                

        }
        

        $this->View['list_score']  = Api_Log::orderBy('created_at','desc')->paginate(15);
        return view('admin.api.chamdiem',$this->View);
    }


    /*
    *
    *
    *
    * 
     */
    public function excelScore(Request $request){


        if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        $price = Auth::user()->price;
        $rq = Auth::user()->request;
        $balance = Auth::user()->balance;
        $date = $current_time = date('Y-m-d H:i:s');
        
        
        if($request->isMethod("post")){
                
            $validater=Validator::make($request->all(),[
                "excel"   => "required",
            ],[
                'excel.required'     =>"Vui lòng chọn file excel. ",
            ]);
            if($validater->fails()){
                return redirect()->back()->withErrors($validater)->withInput();
            }else{
                
                if($request->hasFile('excel')){

                    $path = $request->file('excel')->getRealPath();
                    $data = \Excel::load($path)->get();
                    
                    if($data){
                        foreach ($data as $key => $value) {
                            if(!empty($value['cccd'])){
                                $cmt = $value['cccd'];
                            }else{
                                $cmt = $value['cmnd'];
                            }
                            if(!empty($cmt)){
                                $check = CicLog::where('cmnd',$cmt)->first();
                                if(empty($check)){
                                    $response = $this->scoring($cmt);
                                
                                    $data = array(
                                        'name'  => isset($value['name'])?$value['name']:'0',
                                        'cmnd'  => isset($value['cmnd'])?$value['cmnd']:'0',
                                        'phone' => isset($value['phone'])?$value['phone']:'0',
                                        'cccd'  => isset($value['cccd'])?$value['cccd']:'0',
                                        'score' => isset($response['point']) ? $response['point'] : 0,
                                        'interest_rate'     => isset($response['interest_rate']) ? $response['interest_rate'] : 0,
                                        'loan'              => isset($response['loan']) ? $response['loan'] : 0,
                                        'duration'          => isset($response['duration']) ? $response['duration'] : 0,
                                        'status' => 1,
                                        'referal_id' => 30,
                                        'content' =>'OK. Deduct money',
                                        'user_id'            => $user_id
                                    );
                                  
                                    $cic_log = array(
                                        'phone_id' => isset($value['phone'])?$value['phone']:'0',
                                        'name'  => isset($value['name'])?$value['name']:'',
                                        'phone' => isset($value['phone'])?$value['phone']:'0',
                                        'cmnd'  => $cmt,
                                        'cid_result'=> $response['content'],
                                        'final_score' => isset($response['point']) ? $response['point'] : 0,
                                        'interest_rate'     => isset($response['interest_rate']) ? $response['interest_rate'] : 0,
                                        'loan'              => isset($response['loan']) ? $response['loan'] : 0,
                                        'duration'          => isset($response['duration']) ? $response['duration'] : 0,
                                        'cic_reload' => 0, //1 reloaded
                                        'self_declare_point' => 0,
                                        'status' => 1,
                                        'api_key' => "",
                                        'referal_id' => 30,
                                        'cid_customer_name' => isset($value['name'])?$value['name']:'',
                                        'cid_customer_address' => "",
                                        'cid_customer_tell' => isset($value['phone'])?$value['phone']:'0',
                                        'user_id' => $user_id
                                    );
                                   
                                    $sss = Api_Log::create($data);
                                    CicLog::create($cic_log);
                                    if($balance > 0 ){
                                        $discount = $balance - 33000;    
                                    }else{
                                        $discount = $price - 33000;
                                    }
                                    
                                    $updateprice = User::where('id',$user_id)->update(['request'=>$rq+1,'balance'=>$discount]);
                                    $this->View['check'] = Api_Log::where('cmnd',$request->input('cmnd'))->whereOr('cccd',$request->input('cccd'))->first();
                                    $request->session()->flash("success","Chấm điểm thành công " );
                                }else{
                                    $check_api = Api_Log::where('cmnd',$check['cmnd'])->whereOr('cccd',$check['cmnd'])->first();
                                    if(empty($check_api)){
                                        $data = array(
                                            'name'  => isset($check['name']) ? $check['name'] : $check['cid_customer_name'],
                                            'cmnd'  => $check['cmnd'],
                                            'phone' => $check['phone'],
                                            'score' => isset($check['final_score']) ? $check['final_score'] : 0,
                                            'interest_rate'     => isset($check['interest_rate']) ? $check['interest_rate'] : 0,
                                            'loan'              => isset($check['loan']) ? $check['loan'] : 0,
                                            'duration'          => isset($check['duration']) ? $check['duration'] : 0,
                                            'referal_id' => $check['referal_id'],
                                            'content' => 'Had credit scoring. Do not deduct money',
                                            'user_id'            => $user_id 
                                        );
                                        Api_Log::create($data); 
                                    }else{
                                        $up_api = Api_Log::where('cmnd',$check['cmnd'])->whereOr('cccd',$check['cmnd'])->update(['created_at'=>$date]);
                                    }
                                
                                    $up = CicLog::where('cmnd',$cmt)->update(['created_at'=>$date]);
                                    $this->View['check'] = Api_Log::where('cmnd',$request->input('cmnd'))->whereOr('cccd',$request->input('cccd'))->first();
                                    $request->session()->flash("success","Chứng minh thư này đã được chấm rồi." );
                                }   
                            }
                            
                        }
                        echo "<script>alert('Import thành công !');</script>";
                        return redirect('admin/api/excel-score');
                         
                    }else{
                        echo "<script>alert('File excel không có dữ liệu !');</script>";
                        return redirect('admin/api/excel-score'); 
                    }
                    
                } 
            }
        }
        

        $this->View['list_score']  = Api_Log::orderBy('created_at','desc')->paginate(15);
        // return view('admin.api.chamdiem',$this->View);
        return view('admin.api.excelscore',$this->View); 
    }



    // Các thành phố thỏa điều kiện 
    // Các tỉnh thành đáp ứng:TPHCM và 
    // các tỉnh Miền Tây, Hà Nội 01, Bắc Ninh 27 , Vĩnh Phúc 26, Thanh Hóa 38, Nghệ An 40 ,Đà Nẵng 48 N, Quảng Nam 49 N, Huế  46, Khánh Hòa 56, Bình Thuận 60, Đồng Nai 75,
    // Bình Dương 74, Bình Phước 70, Bà Rịa Vũng Tàu 77,51,52,54,58,62.64.66.67.68.72.

    public function location(Request $request){
        
        $cmnd = $request->input('cmnd','0');
        $cic = $request->input('cic');
        if(strlen($cmnd) == 12){
            $cccdcut = substr($cmnd,0,-9);  
            $cmtcut = ''  ;
        }else{
            $cmtcut = $str = substr($cmnd,0,-7);
            $cccdcut = '';
        }
        
        
        $ciccut = substr($cic,0,-8);
        if($request->isMethod("get")){    
            if(!empty($cic)){
                $chcic = Provincial::where("cic", $ciccut)->first();
                if($chcic->matp == 1 || $chcic->matp == 27 || $chcic->matp == 26 || $chcic->matp == 38 || $chcic->matp == 40 ||$chcic->matp == 46){
                    $data = array(
                        'city'    => $chcic['name'],
                        'content' => 'White City',
                        'point'   => '+0'  
                    );
                    return $data;
                }
                if($chcic->matp == 4 || $chcic->matp == 6 || $chcic->matp == 8 || $chcic->matp == 10 || $chcic->matp == 11 ||$chcic->matp == 12 ||$chcic->matp == 14||$chcic->matp == 15||$chcic->matp == 17||$chcic->matp == 19||$chcic->matp == 20||$chcic->matp == 22||$chcic->matp == 24||$chcic->matp == 25||$chcic->matp == 30||$chcic->matp == 31||$chcic->matp == 33||$chcic->matp == 34||$chcic->matp == 35||$chcic->matp == 36||$chcic->matp == 37||$chcic->matp == 42||$chcic->matp == 44 ||$chcic->matp == 46){
                    $data = array(
                        'city'    => $chcic['name'],
                        'content' => 'White City',
                        'point'   => '-30'  
                    );
                    return $data;
                }
                if($chcic->matp == 51 || $chcic->matp == 52 || $chcic->matp == 54 || $chcic->matp == 58 || $chcic->matp == 62 ||$chcic->matp == 64 ||$chcic->matp == 66 ||$chcic->matp == 67 ||$chcic->matp == 68 ||$chcic->matp == 72){
                    $data = array(
                        'city'    => $chcic['name'],
                        'content' => 'Black City',
                        'point'   => '-20'  
                    );
                    return $data;
                }else{
                    $data = array(
                        'city'    => $chcic['name'],
                        'content' => 'White City',
                        'point'   =>  '+10' 
                    );
                    return $data;
                } 
            }elseif(!empty($cmtcut)){
                if($cmtcut == 12 || $cmtcut == 13 || $cmtcut == 26 || $cmtcut == 28 || $cmtcut == 33 || $cmtcut == 36 || $cmtcut == 38){
                    $check1 = Provincial::whereIn("cmnd", [$cmtcut])->get();
                    
                    // $loca = array();
                    foreach ($check1 as $key => $value) {
                        if($value->matp == 27 || $value->matp == 26 || $value->matp == 60 ){
                            $loca[] = $value['name'];
                            $xx = implode(',',$loca);
                            
                            
                            $data = array(
                                'city'    => $xx,
                                'content' => 'White + Black City',
                                'point'   => '+0'  
                            );

                        }else{
                            $loca[] = $value['name'];
                            $xx = implode(',',$loca);
                            $data = array(
                                'city'    => $xx,
                                'content' => 'White City',
                                'point'   => '+10'  
                            );
                        }
                        
                    }

                    return $data;
                }else{
                    $check = Provincial::where("cmnd", $cmtcut)->first();

                
                    if($check->matp == 1 || $check->matp == 27 || $check->matp == 26 || $check->matp == 38 || $check->matp == 40 ||$check->matp == 46){
                        $data = array(
                            'city'    => $check['name'],
                            'content' => 'White City',
                            'point'   => '+0'  
                        );
                        return $data;
                    }
                    if($check->matp == 4 || $check->matp == 6 || $check->matp == 8 || $check->matp == 10 || $check->matp == 11 ||$check->matp == 12 ||$check->matp == 14||$check->matp == 15||$check->matp == 17||$check->matp == 19||$check->matp == 20||$check->matp == 22||$check->matp == 24||$check->matp == 25||$check->matp == 30||$check->matp == 31||$check->matp == 33||$check->matp == 34||$check->matp == 35||$check->matp == 36||$check->matp == 37||$check->matp == 42||$check->matp == 44 ||$check->matp == 46){
                        $data = array(
                            'city'    => $check['name'],
                            'content' => 'White City',
                            'point'   => '-30'  
                        );
                        return $data;
                    }
                    if($check->matp == 51 || $check->matp == 52 || $check->matp == 54 || $check->matp == 58 || $check->matp == 62 ||$check->matp == 64 ||$check->matp == 66 ||$check->matp == 67 ||$check->matp == 68 ||$check->matp == 72){
                        $data = array(
                            'city'    => $check['name'],
                            'content' => 'Black City',
                            'point'   => '-20'  
                        );
                        return $data;
                    }else{
                        $data = array(
                            'city'    => $check['name'],
                            'content' => 'White City',
                            'point'   =>  '+10' 
                        );
                        return $data;
                    }    
                }

                          
            }else{
                if(!empty($cccdcut)){
                    $check = Provincial::where("cccd", $cccdcut)->first();
                    if($check->matp == 1 || $check->matp == 27 || $check->matp == 26 || $check->matp == 38 || $check->matp == 40 ||$check->matp == 46){
                    $data = array(
                        'city'    => $check['name'],
                        'content' => 'White City',
                        'point'   => '+0'  
                    );
                    return $data;
                    }
                    if($check->matp == 4 || $check->matp == 6 || $check->matp == 8 || $check->matp == 10 || $check->matp == 11 ||$check->matp == 12 ||$check->matp == 14||$check->matp == 15||$check->matp == 17||$check->matp == 19||$check->matp == 20||$check->matp == 22||$check->matp == 24||$check->matp == 25||$check->matp == 30||$check->matp == 31||$check->matp == 33||$check->matp == 34||$check->matp == 35||$check->matp == 36||$check->matp == 37||$check->matp == 42||$check->matp == 44 ||$check->matp == 46){
                        $data = array(
                            'city'    => $check['name'],
                            'content' => 'White City',
                            'point'   => '-30'  
                        );
                        return $data;
                    }
                    if($check->matp == 51 || $check->matp == 52 || $check->matp == 54 || $check->matp == 58 || $check->matp == 62 ||$check->matp == 64 ||$check->matp == 66 ||$check->matp == 67 ||$check->matp == 68 ||$check->matp == 72){
                        $data = array(
                            'city'    => $check['name'],
                            'content' => 'Black City',
                            'point'   => '-20'  
                        );
                        return $data;
                    }else{
                        $data = array(
                            'city'    => $check['name'],
                            'content' => 'White City',
                            'point'   =>  '+10' 
                        );
                        return $data;
                    }
                }

                
                
            }
            
        }
        

        $this->View['location'] = Provincial::get()->pluck('name','matp');
       
        
        return view('admin.api.location',$this->View);

    }  
    public function listRequest(Request $req){
        $year=array(
                "2018"=>"2018",
                "2019"=>"2019",
                "2020"=>"2020",
                "2021"=>"2021",
                "2022"=>"2022"
        );
        $this->View['year'] = $year;

        $this->View['api_log'] = Api_Log::orderBy('created_at','DESC')->paginate(20);
        return view('admin.api.listrequest',$this->View);
    }
    public function userActive(Request $req){
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
        $this->View['role_id'] = Auth::user()->role_id;
        $this->View['user_id'] = Auth::user()->id;
        $this->View['price'] = Auth::user()->price;
        $this->View['request'] = Auth::user()->request;
        $this->View['balance'] = Auth::user()->balance;
        // return $agencies;

        $where_1 = "";
        ($role_id === 10 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7) ? $where_1="WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y $agence_s": $where_1="WHERE DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = $u_id $agence_s";
        $where_2 = "";
        ($role_id === 10 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7) ? $where_2="WHERE DATE_FORMAT( created_at, '%d' ) = d AND DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y $agence_s": $where_2="WHERE DATE_FORMAT( created_at, '%d' ) = d AND DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = $u_id $agence_s"; 
        $where_3 = "";
        ($role_id === 10 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7) ? $where_3="WHERE DATE_FORMAT( created_at, '%d' ) = d AND DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND progress_info = 0 $agence_s": $where_3="WHERE DATE_FORMAT( created_at, '%d' ) = d AND DATE_FORMAT( created_at, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND progress_info = 0 AND user_ref = $u_id $agence_s";

        $where = "";
        $where_count = "";
        ($role_id === 10 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7) ? $where_count="WHERE DATE_FORMAT( ngay_gui_ho_so, '%d' ) = d AND DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y $agence_s": $where_count="WHERE DATE_FORMAT( ngay_gui_ho_so, '%d' ) = d AND DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( created_at, '%Y' ) = y AND user_ref = $u_id $agence_s";
        ($role_id === 10 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7) ? $where="WHERE DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( ngay_gui_ho_so, '%Y' ) = y $agence_s": $where="WHERE DATE_FORMAT( ngay_gui_ho_so, '%m' ) = m AND DATE_FORMAT( ngay_gui_ho_so, '%Y' ) = y AND user_ref = $u_id $echo $agence_s";

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
        // return $tong;
        /********************************************************/
        return view('admin.api.user',compact('history_col_chart','history_line_chart','referals','ref','year','agencies','agence','count_kh','usernew','hsnew','hschamnew','totaluser','agency','totalcham','comment'));
    }

    public function userActiveBK(Request $request){
        if(!Auth::user()){
            $this->redirect(); 
        }
        $this->View['role_id'] = Auth::user()->role_id;
        $this->View['user_id'] = Auth::user()->id;
        $this->View['price'] = Auth::user()->price;
        $this->View['request'] = Auth::user()->request;
        $this->View['balance'] = Auth::user()->balance;
        return view('admin.api.user',$this->View);
    }
     public function scoring($cmnd){
        //get tax
        $tax = (array)$this->getTax($cmnd);

        //call api
        $url = 'http://14.161.30.85:3004/api/getCicInfo?cmnd='.$cmnd;

        $results = $this->curl_invoke($url, '');
        if(isset($results->status)) $results->cic = "";
                                    
        //mapping result 
        $point_cic = $this->matrixResults($results, $tax, '');

        
        //random point
        $rand_point = rand(-3, 7);
        //result = cic + declare
        $point = $point_cic + $rand_point + round(0/10);

        
        $response = $this->cicResults($point, $results, $tax);

        return $response[0];
    }
   /**
     * get Tax user
     * "mst": "6001077782",
        {
        "status": 1,
        "data": [
            {
                "mst": "8244825994",
                "name": "Ha Nguyen Binh",
                "cmnd": "023546419",
                "cccd": "",
                "place_registed": "Chi cục Thuế Quận 5",
                "address": "",
                "city": "TP Hồ Chí Minh",
                "province": "Quận 5",
                "phone": "",
                "created_date": "2012-09-20T17:00:00.000Z",
                "closed_date": "0000-00-00 00:00:00",
                "notes": "NNT đang hoạt động (đã được cấp GCN ĐKT)"
            }
            ]   
        }
     * @param $cmnd
     * @return array
     */
    public function getTax($cmnd){
        $log_tax = DB::table('user_tax')->where('cmnd', '=', $cmnd)->orWhere('cccd', '=', $cmnd)->limit(1)->get()->toArray();        
        if($log_tax){
            $tax = $log_tax[0];
        }else{
            //$url_tax = 'http://14.161.30.85:4001/api/v1/mst?cmnd='.$cmnd;
            $url_tax = 'http://14.161.30.85:3004/api/saveTaxInfo?cmnd='.$cmnd;
            //get tax
            $tax = $this->curl_invoke_tax($url_tax); 

            if($tax->status == 0){ //error
                $tax = "";                
            }else if($tax->data[0]->mst === null || $tax->data[0]->mst === ""){ //mst empty
                DB::table('user_tax')->insert(array('cmnd' => $cmnd));
                $tax = "";                
            }else if($tax->data[0]->mst !== null || $tax->data[0]->mst !== ""){                
                $data = array(
                    'mst' => isset($tax->data[0]->mst) ? $tax->data[0]->mst : "",
                    'cmnd' => isset($tax->data[0]->cmnd) ? $tax->data[0]->cmnd : "",
                    'name' => isset($tax->data[0]->name) ? $tax->data[0]->name : "",
                    'cccd' => isset($tax->data[0]->cccd) ? $tax->data[0]->cccd : "",
                    'place_registed' => isset($tax->data[0]->place_registed) ? $tax->data[0]->place_registed : "",
                    'address' => isset($tax->data[0]->address) ? $tax->data[0]->address : "",
                    'city' => isset($tax->data[0]->city) ? $tax->data[0]->city : "",
                    'province' => isset($tax->data[0]->province) ? $tax->data[0]->province : "",
                    'phone' => isset($tax->data[0]->phone) ? $tax->data[0]->phone : "",
                    'created_date' => ($tax->data[0]->created_date && $tax->data[0]->created_date !== "0000-00-00 00:00:00") ? date('Y-m-d H:i:s', strtotime($tax->data[0]->created_date)) : "0000-00-00 00:00:00",
                    'closed_date' => ($tax->data[0]->closed_date && $tax->data[0]->closed_date !== "0000-00-00 00:00:00") ? date('Y-m-d H:i:s', strtotime($tax->data[0]->closed_date)) : "0000-00-00 00:00:00",
                    'notes' => isset($tax->data[0]->notes) ? $tax->data[0]->notes : "",
                );                

                $tax = $tax->data[0];
               DB::table('user_tax')->insert($data);
            }
        }

        return $tax;        
    } 
     /**
     * get database cic infomation
     * @param $url
     * @return Array
     */
    public function curl_invoke_tax($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // SSL important
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($output);

        return $results;
    }
    /**
     * get database cic infomation
     * @param $url
     * @return Array
     */
    public function curl_invoke($url, $request){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // SSL important
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($output);

        if(!isset($results->content) && !isset($results->status)){
            $this->getCic($request);
            return response()->json(['Hệ thống đang gặp lỗi, vui lòng thực hiện lại'], 203);
        }

        return $results;
    }

    /**
     * - Công thức Scorring 1.5
    -   CÓ MÃ CIC
    o   Điểm CIC
       Nợ 0 : CIC = 660
       Nợ 1 : CIC = 600
       Nợ 2 : CIC = 560
       Các loại Nợ khác vẫn giữ nguyên 
    o   Không có MST : - 60 (không đi làm), chỉ Nợ 1 là Ngưng. 
    o   CÓ MST thì Dự Đoán Tuổi 
       Tuổi : Điểm Cộng
    •   TUỔI <25 : +10*TLN
    •   TUỔI 25-28 : +30*TLN
    •   TUỔI 28-30 : +50*TLN
    •   TUỔI 30-35 : +80*TLN
    •   TUỔI >35 : +100*TLN
       Tỷ lệ Nhân (so với Nợ)
    •   Nợ =0, TLN = 100%
    •   Nợ =1, TLN = 50%
    •   Nợ =2, TLN = 30%
    •   Nợ >2, hoặc có nợ chú ý, nợ xấu, thì TLN=0%
    -   Không có Mã CIC (chưa từng Vay)
    o   Không có MST : =>> SORRY (chúng tôi chưa thể kiểm tra, xin tạo TK để nhận Info trong 24h tới)
    o   CÓ MST (có đi làm) 
       CIC = 600
       TUỔI : ĐIỂM CỘNG giữ nguyên
       TLN = 50% (có chút dè dặt vì Chưa Có Hành Vi Trả Nợ trong quá khứ) 
       Lãi suất = -3% (ưu ái cho người chưa từng vay) 
    -   Others

    Lưu ý : 
    -   Các số trong công thức có thể được EDIT lại phù hợp với hoàn cảnh tương lai.
    -   Không còn dạng Random (-10, +10)
    * $params $results | ket qua tra ve tu api
    * $params $tax | ma so thue
    * 
     */
    public function matrixResults($results, $tax, $tuoi = ""){
        //mapping result 
        $point_cic = $this->pointCic($results);
        $point_cic = $point_cic['point'];
        $current_year = date('Y');
        //Lãi suất = -3% (ưu ái cho người chưa từng vay) 
        $discount = -3;
        $tln = 0;
        $percent = 100;
        $current_age = 23;
        $age = 24;


        //age
        if(isset($tax['created_date']) && $tax['created_date'] !== "0000-00-00 00:00:00"){
            $created_tax = date('Y', strtotime($tax['created_date']));
            $ex_year = $current_year - $created_tax;
            if($ex_year > 0){
                $age = 23 + $ex_year;
            }
        }        

        //tuoi khai bao
        if($tuoi) $age = $tuoi;

        //ti le nhan
        if($point_cic == 660){ // no.0
            $tln = 100;
        }else if($point_cic == 600){ // no 1
            $tln = 50;
        }else if($point_cic == 560){ // no2
            $tln = 30;
        }
           
        //check cic
        if($results->cic !== ""){
            if(isset($tax['mst']) && $tax['mst'] !== "" || $tuoi){
                //tinh diem tren tuoi                
                $point_cic = $this->calAge($tax, $age, $point_cic, $tln, $percent);
            }else{
                if($point_cic > 0) $point_cic -= 60;                    
            }

        }else{ //chua tung vay
            if(isset($tax['mst']) && $tax['mst'] !== "" || $tuoi){ 
                if(isset($tax['mst']) && $tax['mst'] !=="" && isset($results->content) !== "Server không thể kết nối internet"){
                    $point_cic = 600;
                    $tln = 50;
                }

                //tinh diem tren tuoi
                $point_cic = $this->calAge($tax, $age, $point_cic, $tln, $percent);
            }else{ //not tax
                $point_cic = 0; //SORRY (chúng tôi chưa thể kiểm tra, xin tạo TK để nhận Info trong 24h tới)
            }
        }

        return $point_cic;
    }

    /**
     * Caculation age
     * @param $tax, $point_cic, $tln, $percent
     * @return int
     */
    public function calAge($tax, $age = "", $point_cic, $tln, $percent){
        if($age){ 
            //tinh diem tren tuoi
            if($age < 25){
                $point_cic += 10*$tln/$percent; 
            }else if($age >= 25 && $age < 28){
                $point_cic += 30*$tln/$percent; 
            }else if($age >= 28 && $age < 30){
                $point_cic += 50*$tln/$percent; 
            }else if($age >= 30 && $age < 35){
                $point_cic += 80*$tln/$percent; 
            }else if($age >= 35){
                $point_cic += 100*$tln/$percent;
            }

            return $point_cic;
        }
    }

    /**
     * Caculator point
     * @param $data
     * @return array
     */
    public function pointCic($data){
        if($data){
            //get cic config
            $cic_configs = CicConfig::all()->toArray();
            
            $content = isset($data->content) ? $data->content : @$data->status;
            $patent = substr($content , 0, -11);
            $arr_cic = array();
            $arr_point = array_column($cic_configs, 'point');

            
            foreach($cic_configs as $item){
                $arr_cic[] = substr($item['cic_msg'], 0, -11);
            }      
            
            if($key = array_search($patent, $arr_cic)){
                foreach($cic_configs as $k => $item){
                    if($key == $k){
                        return array(
                            'point' => $item['point'],
                            'point_rand' => $item['point_rand']
                        );
                    }
                }
            }
        }
    }
     /**
     * Cic Results 
     * 20+40/300*$point-550
     * @param int
     * @return array
     */
    public function cicResults($point, $results, $tax){
        //rent money
        $min = 20;
        $max = 80;
        $range = $max - $min;

        //point
        $min_point = 550;
        $max_point = 850;
        $range_point = $max_point - $min_point;

        //percent
        $min_percent = 30;
        $max_percent = 45;
        $range_percent = $max_percent - $min_percent;

        //method
        $loan = $min + ($range/$range_point) * ($point - $min_point);
        $interest_rate = $max_percent - ($range_percent/$range) * (ceil($loan) - $min);
        //time
        if($point >= 450 && $point < 600) $duration = 24;
        else if($point >= 600 && $point < 700) $duration = 36;
        else if($point >= 700) $duration = 48;
        else $duration = 0;

        //min max
        if($point > $max_point ){
            $loan = 80;
            $interest_rate = 30;
        }else if($point >= 500 && $point < 550){
            $loan = 20;
            $interest_rate = 50;
        }else if($point >= 450 && $point < 500){
            $loan = 10;
            $interest_rate = 70;
        }else if($point < 450){
            $loan = 0;
            $interest_rate = 0;
        }

        //not cic
        if($results->cic === ""){
            if(isset($tax['mst']) && $tax['mst'] !== ""){
                $interest_rate -= 3; //discount 3%
            }
        }


        $res[] = array(
            'point' => $point,
            'loan'  => ceil($loan),
            'interest_rate' => ceil($interest_rate),
            'duration'  => $duration,
            'content'   => isset($results->status) ? $results->status :''
        );

        return $res;
    }
    

}