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
    background-color: #e5ece6;
    color: #353d47;
    font-weight: bold;
}

</style>
@stop
@section('content')
<?php
    $u_id = Auth::user()->id;
?>
<?php $role_id = Auth::user()->role_id;?>
@if($role_id === 2 || $role_id === 5)
    @include('vendor.voyager.checkpemission')
@else
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="row">
                    <div class="col-sm-12 text-left">
                        <h3 style="font-weight: bold;">Đối soát OCB</h3>
                    </div>
                </div>
                <?php 
                    $role_id = Auth::user()->role_id;
                    $user_id = Auth::user()->id; 
                ?>
				{!! Form::open(['method'=>'get','class'=>"form-inline"]) !!}
                     <div class="col-sm-12 text-left">
                        <div class="form-group row">
                            <label for="colFormLabelLg" style="font-weight: bold;">Chọn năm:</label>
                        </div>
                        {!! Form::select('year',$year,(($search['year']))?$search['year']:new \Date('Y'),['class'=>"form-control" ]) !!}
                      
                        <button type="submit" class="btn btn-danger mb-2">Tìm kiếm</button>
                    </div>
                {!! Form::close()!!}

            </div>
        </div>
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="customers" class="table table-hover">
                        <thead>
                            <tr style="font-weight: bold;">
                                <th>Tháng</th>
                                <th>TCTD</th>
                                <th>Total</th>
                                <th>Số hồ sơ vay</th>
                                <th>Chờ duyệt</th>
                                <th>Được duyệt</th>
                                <th>Hồ sơ trùng</th>
                                <th>Từ chối</th>
                                <th>Tỷ lệ thành công</th>
                                <th>Khoản vay</th>
                                <th>Tổng số tiền được giải ngân</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody >


							@foreach($history as $key => $value)
                            <tr>
                                <td colspan="" rowspan="" headers="" style="color: #22a7f0;"><span style="font-weight: bold;">{{$value->Thang.'/'.$value->Nam}}</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-info" style="font-weight: bold;">OCB</span></td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">{{$value->total}}</span></td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">{{$value->hsv}}</span></td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">@if($value->choduyet === null) {{'0'}} @else{{ $value->choduyet }} @endif Hồ sơ</span></td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">@if($value->duocduyet === null) {{'0'}} @else{{ $value->duocduyet }} @endif Hồ sơ</span></td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">@if($value->hstrung === null) {{'0'}} @else{{ $value->hstrung }} @endif </span></td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">@if($value->tuchoi === null) {{'0'}} @else{{ $value->tuchoi }} @endif Hồ sơ</span></td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">@if($value->ti_le === null) {{'0 %'}} @else {{ $value->ti_le.' %'}} @endif</span></td>
                                <td colspan="" rowspan="" headers="">
                                    <span class="label label-danger" style="font-weight: bold;">
                                        {{App\MrData::toPrice($value->khoan_vay)}}
                                    </span>
                                </td>
                                <td colspan="" rowspan="" headers=""><span class="label label-danger" style="font-weight: bold;">{{App\MrData::toPrice($value->giai_ngan)}}</span></td>
                               <td>
                                <?php
                                        if ($value->Thang > 0 && $value->Thang <= 10) {
                                            if($value->Thang == 2 ){
                                                $from_date = $value->Nam.'-0'.$value->Thang.'-01' ; 
                                                $to_date =  $value->Nam.'-0'.$value->Thang.'-28';
                                            }else{
                                                $from_date = $value->Nam.'-0'.$value->Thang.'-01' ;
                                                if($value->Thang == 4 || $value->Thang == 6 || $value->Thang == 9){
                                                    $to_date = $value->Nam.'-0'.$value->Thang.'-30' ; 
                                                }else{
                                                    $to_date = $value->Nam.'-0'.$value->Thang.'-31' ;     
                                                }    
                                            }
                                         }
                                         else
                                         {
                                            $from_date = $value->Nam.'-'.$value->Thang.'-01' ; 
                                            if($value->Thang == 11){
                                                $to_date = $value->Nam.'-'.$value->Thang.'-30' ; 
                                            }else{
                                                $to_date = $value->Nam.'-'.$value->Thang.'-31' ; 
                                            }
                                         }
                                    ?>
                                   <a href="/backend/admin/history?from_date_tn={{$from_date}}&to_date_tn={{$to_date}}&tctd=1" type="submit" class="btn btn-sm btn-primary pull-center edit" target="_blank"><i class="voyager-eye"></i><span class="hidden-xs hidden-sm"> Xem chi tiết</span></a>
                               </td>
                            </tr>
							@endforeach
                            

                        </tbody>

                    </table>

                     <div class="container">

                        <div class="row ">

                            <div class="col-sm-12 text-center">
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
    @endif
@stop
@section('javascript')
@stop



