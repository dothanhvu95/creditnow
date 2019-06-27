<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\AcounttantNote;
use App\HistoryLog_View;
use App\Support;
use App\CicLog;
use App\User;
use Excel;
use File;
use DB;

class SupportController extends Controller
{
    protected $View=[];
    /**
    * List S-wifi
    * @param $request
    * @return list
    */
    public function index(Request $request){
        if(!Auth::user()){
           $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $u_id = Auth::user()->id;
        
        $this->View['support'] = Support::distinct('phone')->where('ref_name','S-wifi')->orderBy('created_at','desc')->paginate(50);
        $this->View['one'] = Support::where('ref_name','S-wifi')->where('number',1)->paginate(50);
        $this->View['two'] = Support::where('ref_name','S-wifi')->where('number',2)->paginate(50);
        $this->View['three'] = Support::where('ref_name','S-wifi')->where('number',3)->paginate(50);
        $this->View['four'] = Support::where('ref_name','S-wifi')->where('number',4)->paginate(50); 
        $this->View['totalhscd'] = HistoryLog_View::whereRaw("user_ref = 833 AND history_log_id IS NULL AND progress_info = 5 AND status = 1")->paginate(1);
        $this->View['totalhsv'] = HistoryLog_View::whereRaw("user_ref = 833 AND history_log_id IS NOT NULL AND status = 1")->paginate(1);
        return view('admin.support.list',$this->View);
    }
    public function importSwifi(Request $request){
        if($request->isMethod("post")){
            if($request->hasFile('excel')){
               
                $path = $request->file('excel')->getRealPath();
                
                $data = \Excel::load($path)->get();


                if($data->count()){
                    
                    foreach ($data as $key => $value) {
                        $phone = str_replace('84', '0', $value['so_dien_thoai']);
                        $check = Support::where('phone',$phone)->where('ref_name','S-wifi')->first();
                        

                        if($check['status'] == 0 && $check['number'] == 0){
                            $update = Support::where('phone',$check['phone'])->where('ref_name','S-wifi')->update(['status'=>1,'number'=>1]);
                        }else{
                            if($check['number'] == 0){
                                $update1 = Support::where('phone',$check['phone'])->where('ref_name','S-wifi')->update(['number'=>1]);
                            }
                            if($check['number'] == 1){
                                $update1 = Support::where('phone',$check['phone'])->where('ref_name','S-wifi')->update(['number'=>2]);
                            }
                            if($check['number'] == 2){
                                $update2 = Support::where('phone',$check['phone'])->where('ref_name','S-wifi')->update(['number'=>3]);
                            }
                            if($check['number'] == 3){
                                $update3 = Support::where('phone',$check['phone'])->where('ref_name','S-wifi')->update(['number'=>4]);
                            }
                        }
                    }
                    echo "<script>alert('Import thành công !');</script>";
                    return redirect('admin/s-wifi/list'); 
                }
                
            }else{
                echo "<script>alert('File excel không hợp lệ!');</script>";
                return redirect('admin/s-wifi/list'); 
            }

        }        
    }
    public function testCronjob(Request $request){
        
        $update = DB::table('test')->insert(['name'=>'ahoho']);
       
        
    }

}