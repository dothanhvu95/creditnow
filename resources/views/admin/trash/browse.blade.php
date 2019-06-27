@extends('voyager::master')



@section('page_header')
<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
  font-weight: bold;
}
.active{
  background-color: #666;
  color: white;
}
</style>
@stop



@section('content')
    <div class="page-content browse container-fluid">
         @include('vendor.voyager.checkuser')
 
        <?php $u_id = Auth::user()->id;?>
        <?php $role_id = Auth::user()->role_id;?>
        <?php $group_id_u = Auth::user()->group_id; ?>
        <div class="row">

            <div class="col-sm-12 text-center" style="background-color: white; padding-bottom: 20px;">

                <div class="row">

                    <div class="col-sm-12 text-left">

                        <h3 style="font-weight: bold;">Lịch sử vay</h3>

                    </div>

                </div>

                <!-- <form class="form-inline" action="{{url('admin/history')}}" method="GET"> -->
                    {!! Form::open(['method'=>'get']) !!}

                  
                    
                        <div class="col-sm-6 text-left form-inline" >
                        <div class="container">
                            <div class="col-sm-12 ">
                                
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Trạng thái:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <label for="inputPassword6" > </label>
                                    <div class="form-group">
                                        {!! Form::select('zzz',$zzz,@$search['zzz'],['class'=>"form-control ",'style'=>'min-width: 200px;' ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Agencies:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <label for="inputPassword6" > </label>
                                    <div class="form-group">
                                        {!! Form::select('agence',$agence,@$search['agence'],['class'=>"form-control ",'style'=>'min-width: 200px;' ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                    <label for="colFormLabelLg" style="font-weight: bold;">Ngày tiếp nhận hồ sơ :</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px;">
                                    <div class="form-group">
                                        <label for="inputPassword6" >Từ: </label>
                                        <!-- <input type="date" name="from_date_tn" id="from_date_TN" value="" class="form-control mx-sm-3" aria-describedby="passwordHelpInline"> -->
                                        {{ Form::date('from_date_tn',(($search['from_date_tn']))?$search['from_date_tn']:new \DateTime(),['class' => 'form-control',"aria-describedby" => "passwordHelpInline"]) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword6" >Đến: </label>
                                        {{ Form::date('to_date_tn',(($search['to_date_tn']))?$search['to_date_tn']:new \DateTime(),['class' => 'form-control',"aria-describedby" => "passwordHelpInline"]) }}
                                        
                                    </div>
                                </div>
                                
                                <div class="col-sm-4" style="padding-bottom: 10px; margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Nhập từ khóa tìm kiếm:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <div class="form-group">
                                        {!! Form::text('key',@$search['key'],['class'=>"form-control ","placeholder"=>"Nhập từ khóa tìm kiếm( CMND, SĐT,Họ Tên )",'style'=>'min-width: 420px;margin-top: 10px;' ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                    <label for="colFormLabelLg" style="font-weight: bold;">Bộ lọc:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px;">
                                    <div class="form-group">
                                        <label for="inputPassword6" >Điểm: </label>
                                        <!-- <input type="date" name="from_date_tn" id="from_date_TN" value="" class="form-control mx-sm-3" aria-describedby="passwordHelpInline"> -->
                                        {!! Form::select('point',$point,@$search['point'],['class'=>"form-control ",'style'=>'min-width: 200px;']) !!}
                                    </div>
                                    
                                </div>
                                 

                                <button type="submit" class="btn btn-danger mb-2" style="float: right;">Tìm kiếm</button>
                            </div>
                        </div>
                    </div>    
                   
                {!! Form::close()!!}

            </div>

        </div>

        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="table-responsive">
                    <form onsubmit="return confirm('Có chắc chắn muốn chuyển các hồ sơ này đến Team-CS ?')" action="{{route('send-to-cs-team')}}" method="post" accept-charset="utf-8">
                        @csrf
                        <table  id="customers" class="table-hover">
                            @if($role_id != 7)
                            @if($role_id == 8)
                            <caption style="color: #22a7f0; margin-bottom: 30px;"> &#9679;&nbsp;Hồ sơ vay:&nbsp;<span class="label label-success" style="font-weight: bold;">{{$total_gui}}</span></caption>
                            @else
                            <caption style="color: #22a7f0; margin-bottom: 30px;">&#9679;&nbsp;Hồ sơ chấm điểm: <span class="label label-danger" style="font-weight: bold;">{{$total_chichamdiem}}</span>|| &#9679;&nbsp;Hồ sơ vay:&nbsp;<span class="label label-success" style="font-weight: bold;">{{$total_gui}}</span>
                              @if(count($history) > 0 ) 
                              <button type="button" id="restoremulti" class="btn btn-success" data-toggle="modal" data-target="#exampleModalRemove" style="float: right;margin-right: 10px;">Restore</button>
                              @endif
                            </caption>
                            
                            @endif
                            @endif
                            
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    @if($role_id != 8)
                                    <th><input type="checkbox" class="checkall">Check all</th>
                                    @endif
                                    @if($role_id === 7)
                                    <th>Agencies</th>
                                    <th>Referal</th>
                                    <th>CS</th>
                                    <th>Họ tên</th>
                                    @endif
                                    @if($role_id != 8)
                                    <th>Ngày gửi hồ sơ</th>
                                    @endif
                                    <th>Số điện thoại</th>

                                    <th>CMND</th>
                                    <th>Điểm</th>
                                    <th>Khoản vay</th>
                                    <th>Số tiền giải ngân</th>
                                    @if($role_id != 7)
                                    <th>Người đăng ký</th>
                                    @endif
                                    @if($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6)
                                    <th>Agencies</th>
                                    <th>Phân loại hồ sơ</th>
                                
                                    <th>Trạng thái</th>

                                    <th>Tác vụ</th>
                                    @elseif($role_id === 7)
                                    <th>Khoản muốn vay</th>
                                    <th>Trạng thái</th>
                                    <th>Tiền giải ngân thực</th>
                                    <th>Action</th>
                                    @else
                                    <th>Trạng thái</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($history as $key => $val)

                                    @if($role_id === 1 || $role_id === 4 || $role_id === 6)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                            <input type="checkbox" name="multiname[]" value="{{$val->id}}" class='check_el' >
                                        </td>
                                        <?php $date = date('d-m-Y', strtotime($val->created_at))  ?> 
                                        <?php $date_1 = date('d-m-Y', strtotime($val->ngay_gui_ho_so))  ?> 
                                        <?php $name_vta = App\UserTax::where('cmnd',$val->cmnd)->first();?>
                                        <td>@if($date_1 !== '01-01-1970'){{ date('d-m-Y', strtotime($val->ngay_gui_ho_so)) }} @else {{'Chưa gửi'}} @endif</td>
                                        <td> {{ $val->phone_id }}</td>
                                        <td>{{ $val->cmnd }}</td>
                                        <td>{{$val->final_score}}</td>
                                        <td>{{$val->khoanvay}}</td>
                                        <td>{{ $val->so_tien_giai_ngan }}</td>
                                        <td>

                                            @if(!empty($val->name))
                                            {{ $val->name }}
                                            @elseif(!empty($name_vta))
                                               
                                                {{$name_vta['name']}}  
                                            @else 
                                            {{$val->cid_customer_name}}
                                            @endif
                                       
                                        </td>
                                        <td>{{ $val->agencies }}</td>
                                        <td>
                                             @if(($val->progress_info == 5) && ($val->history_log_id == null))
                                                <span class="label label-danger">Hồ sơ chỉ chấm điểm</span>
                                            @elseif($val->progress_info < 50 && $val->history_log_id != null)
                                                <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                                            @elseif($val->progress_info < 50 && $val->history_log_id == null)
                                                <span class="label label-info">Hồ sơ chưa cập nhật đầy đủ</span>
                                            @elseif($val->progress_info >= 50 && $val->history_log_id == null)
                                                <span class="label label-warning">Hồ sơ chưa gửi hồ sơ vay</span>
                                            @elseif($val->progress_info > 50 && $val->history_log_id !==null)
                                                <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($val->trangthai == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        </td>
                                        <td>
                                        @if($val->history_log_id !== null)
                                            <a href="{{url('admin/history/view-edit',[$val->history_log_id,$val->cmnd])}}" title="View" class="btn btn-xs btn-primary view">
                                                <i class="voyager-eye"></i>
                                            </a>
                                            

                                        @else
                                        
                                            <a href="{{url('admin/history/view-edit',[$val->id,$val->cmnd])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i>
                                            </a>
                                            
                                        
                                        @endif
                                            <a href="{{url('admin/restore',[$val->id])}}" title="Restore" class="btn btn-xs btn-warning click_remove">
                                                <i class="voyager-refresh"></i>
                                            </a>
                                            <a href="{{url('admin/trash-delete',[$val->id])}}" title="Restore" class="btn btn-xs btn-danger click_delete">
                                                <i class="voyager-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @elseif($role_id == 5)    {{--show history user_cs--}}
                                    @if($group_id_u == $val->user_group)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <th><input type="checkbox" name="name[]" value="{{$val->history_log_id}}"></th>
                                        <?php $date = date('d-m-Y', strtotime($val->created_at))  ?> 
                                        <?php $date_1 = date('d-m-Y', strtotime($val->ngay_gui_ho_so))  ?> 
                                        <td>@if($date_1 !== '01-01-1970'){{ date('d-m-Y', strtotime($val->ngay_gui_ho_so)) }} @else {{'Chưa gửi'}} @endif</td>
                                        <td> {{ $val->phone_id }}</td>
                                        <td>{{ $val->cmnd }}</td>
                                        <td>{{ $val->so_tien_giai_ngan }}</td>
                                        <td>
                                            @if(!empty($val->name))
                                            {{ $val->name }}
                                            @elseif(!empty($name_vta))
                                               
                                                {{$name_vta['name']}}  
                                            @else 
                                            {{$val->cid_customer_name}}
                                            @endif
                                        </td>
                                        <td>{{ $val->agencies }}</td>
                                        <td>
                                            @if((round(($val->progress_info/52)*100) == 0) && ($val->history_log_id == null))
                                                <span class="label label-danger">Hồ sơ chỉ chấm điểm</span>
                                            @elseif(round(($val->progress_info/52)*100) < 50 && $val->history_log_id == null)
                                                <span class="label label-info">Hồ sơ chưa cập nhật đầy đủ</span>
                                            @elseif(round(($val->progress_info/52)*100) >= 50 && $val->history_log_id == null)
                                                <span class="label label-warning">Hồ sơ chưa gửi hồ sơ vay</span>
                                            @elseif($val->progress_info > 0 && $val->history_log_id !== null)
                                                <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                                            @endif
                                        </td>
                                        <td>
                                        @if($val->trangthai !== null)
                                            
                                            @if($val->trangthai == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @else
                                            @if($val->status == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->status == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->status == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @endif
                                        </td>
                                        @if($val->history_log_id !== null)
                                        <td><a href="{{url('admin/history/view-edit',[$val->history_log_id,$val->cmnd])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Xem</span>
                                        </a></td>
                                        @else
                                        <td><a href="{{url('admin/history/view-edit',[$val->id,$val->cmnd])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Xem</span>
                                        </a></td>
                                        @endif
                                    </tr>
                                    @endif
                                    @elseif($role_id == 7)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <th><input type="checkbox" name="name[]" value="{{$val->id}}" class='check_el'></th>
                                        <td>{{$val->agencies}}</td>
                                        <td>{{$val->referal_name}}</td>
                                        <td>@if($val->cs_name_history != null){{ $val->cs_name_history }} @else {{ $val->cs_name_log }} @endif</td>
                                        <td>{{$val->name}}</td>
                                        <td>{{date('d-m-Y', strtotime($val->created_at))}}</td>
                                        <td>{{date('d-m-Y', strtotime($val->ngay_gui_ho_so))}}</td>
                                        <td>{{$val->phone}}</td>
                                        <td>{{$val->cmnd}}</td>
                                        <td>{{$val->so_tien_giai_ngan}}</td>
                                        <td>{{$val->khoanvay}}</td>
                                        <td>
                                        @if($val->trangthai !== null)
                                            
                                            @if($val->trangthai == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @else
                                            @if($val->status == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->status == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->status == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @endif
                                        </td>
                                        <form action="{{url('admin/updatemoney',$val->history_log_id)}}" method="post" accept-charset="utf-8">
                                         @csrf
                                           <td><input type="text" id="money" name="tiengiainganthuc" class="form-control" value="@if($val->so_tien_giai_ngan_thuc !== null) {{$val->so_tien_giai_ngan_thuc}} @endif" placeholder=""></td>
                                            <td><button type="submit" title="View" class="btn btn-sm btn-primary view">
                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Lưu</span>
                                            </button></td>
                                        </form>
                                        
                                    </tr>
                                    @elseif($role_id == 8)
                                        @if($val->history_log_id !== null)
                                            @if($u_id === $val->user_ref)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{ $val->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        
                                                        {{'*******'.substr((string)$val->phone_id,-4)}}
                                                    </td>
                                                    <td>
                                                        {{'*********'.substr((string)$val->cmnd,-4)}}
                                                    </td>
                                                    <td>{{ $val->so_tien_giai_ngan }}</td>
                                                    <td>@if($val->ngay_giai_ngan != '0000-00-00 00:00:00') {{ $val->ngay_giai_ngan }} @endif</td>
                                                    <td>{{ $val->note }}</td>
                                                    <td>
                                                    @if($val->trangthai !== null)
                                                        <span class="
                                                        @if($val->trangthai == 1) 
                                                            {{'label label-primary'}}
                                                        @elseif($val->trangthai == 2) 
                                                            {{'label label-success'}}
                                                        @elseif($val->trangthai == 3)
                                                            {{'label label-primary'}}
                                                        @elseif($val->trangthai == 4) 
                                                            {{'label label-info'}}
                                                        @elseif($val->trangthai == 5) 
                                                            {{'label label-warning'}}
                                                        @elseif($val->trangthai == 6) 
                                                            {{'label label-warning'}}
                                                        @elseif($val->trangthai == 7) 
                                                            {{'label label-danger'}} 
                                                        @endif"> {{ $val->status_name}} </span>
                                                    @else
                                                     <span class="
                                                        @if($val->status == 1) 
                                                            {{'label label-primary'}}
                                                        @elseif($val->status == 2) 
                                                            {{'label label-success'}}
                                                        @elseif($val->status == 3)
                                                            {{'label label-primary'}}
                                                        @elseif($val->status == 4) 
                                                            {{'label label-info'}}
                                                        @elseif($val->status == 5) 
                                                            {{'label label-warning'}}
                                                        @elseif($val->status == 6)
                                                            {{'label label-warning'}}
                                                        @elseif($val->status == 7) 
                                                            {{'label label-danger'}} 
                                                        @endif"> {{ $val->status_name}} </span>
                                                    @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach

                            </tbody>

                        </table>
                        <nav style="float: right;">
                            {!!$history->appends($search)->render()!!}
                        </nav>
                    </form>
                </div>
            </div>

        </div>

    </div>
    <div class="modal fade" id="exampleModalRemove" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="form-inline" action="{{url('admin/trash/restoremulti')}}" method="POST">
          @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title text-center" id="exampleModalLongTitle"></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                  <div class="container">
                    <input type="hidden" id="restoreid" name="restoreid" value="">
                    <div class="row">
                        <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                            <h4 style="    text-transform: uppercase;">Bạn có muốn phục hồi hồ sơ.</h4>
                        </div>

                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary restoremulti ">Restore</button>
          </div>
        </div>
        </form>
      </div>
    </div>
@stop
@section('javascript')

<script src="public/js/select-togglebutton.js"></script>
<script type="text/javascript">
    @if(isset($status))
        $('#status_search_history option[value={{$status}}]').attr('selected','selected');      
    @endif 
    @if(isset($status_profile))
        $('#status_profile_search_history option[value={{$status_profile}}]').attr('selected','selected');    
    @endif

    $(document).ready(function() {
        

        // $(".checkall").click(function () {
        //     $('input:checkbox').not(this).prop('checked', this.checked);
        // });
        $(".checkall").change(function(){
            if($(this).prop("checked")){
                $(".check_el").prop("checked",true);
            }else{
                $(".check_el").prop("checked",false);
            }
        });
       $('#restoremulti').click(function(event) {
            var checkall = $('[name="multiname[]"]:checkbox:checked').val();
            if(checkall === undefined ){
                alert(' Vui lòng chọn hồ sơ cần phục hồi.');
                return false;
            }
        });
       
   
        $('.restoremulti').click(function(event) {
            /* Act on the event */
            var val = [];
            $('.check_el:checked').each(function(i){
              val[i] = $(this).val();
            });
            var checkall = $('[name="multiname[]"]:checkbox:checked').val();       
            var tctd_name = $("input[name='to_chuc_tin_dung']:checked").parent('label').text();
            
              $('#restoreid').val(val);
              document.getElementById("restoreid").innerHTML = val ; 
              $('#exampleModalRemove').modal('hide');
            
          });

       
        $(".click_remove").click(function(){
            if(confirm("Bạn muốn phục hồi hồ sơ này không? ")){
                $.get($(this).attr("href"),function(){

                });
                $(this).parent().parent().remove();
                
            }
            return false;
        });
        $(".click_delete").click(function(){
            if(confirm("Bạn muốn xóa hồ sơ này không? ")){
                $.get($(this).attr("href"),function(){

                });
                $(this).parent().parent().remove();
                
            }
            return false;
        });

    });
</script>
@stop



