@extends('voyager::master')
@section('page_header')
@stop
@section('content')
@include('vendor.voyager.checkuser')
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
                        <h3 style="font-weight: bold;">Đối soát</h3>
                    </div>
                </div>
                <?php 
                    $role_id = Auth::user()->role_id;
                    $user_id = Auth::user()->id; 
                ?>
                <form class="form-inline" action="{{url('admin/report-credit-institutions')}}" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"/>
                     <div class="col-sm-12 text-left">
                            <div class="form-group ">
                                <label for="colFormLabelLg" style="font-weight: bold;">Chọn năm:</label>
                            </div>
                            <select class="form-control" id="year_search" name="year">
                                @foreach(Config::get('constants.select_year') as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                            <div class="form-group ">
                                <label for="colFormLabelLg" style="font-weight: bold;">Chọn TCTD:</label>
                            </div>
                            <select class="form-control" id="tctd_search" name="tctd">
                                <option value="">Xem tất cả</option>
                                    @foreach($tctd as $key => $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                        <button type="submit" class="btn btn-danger mb-2">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover">
                        <thead>
                            <tr style="font-weight: bold;">
                                <th>STT</th>
                                <th>TCTD</th>
                                <th>Tháng</th>
                                <th>Lead <a href=""><i class="voyager-angle-down pull-right"></i></a></th>
                                <th>Tài khoản cập nhật</th>
                                <th>Số hồ sơ vay</th>
                                <th>Được duyệt</th>
                                <th>Tỉ lệ</th>
                                <th>Khoản vay</th>
                                <th>Tổng số tiền được giải ngân</th>
                                <th>Số tiền giải ngân thực</th>
                                <th>Ghi chú</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody >
                            <?php $i = 1; ?>
                            @foreach($history as $val)
                            <tr>
                                <td colspan="" rowspan="" headers="" style="color: #22a7f0;"><span style="font-weight: bold;">{{$i++}}</span></td>
                                <td colspan="" rowspan="" headers="" style="color: #22a7f0;"><span style="font-weight: bold;">{{$val->name_tctd}}</span></td>
                                <td colspan="" rowspan="" headers="" style="color: #22a7f0;"><span style="font-weight: bold;">{{'Tháng '.$val->thang.'-'.$val->nam}}</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-info" style="font-weight: bold;">@if($val->tong_lead === null) {{'0'}} @else{{ $val->tong_lead }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-info" style="font-weight: bold;">@if($val->account === null) {{'0'}} @else{{ $val->account }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-warning" style="font-weight: bold;">@if($val->ho_so_vay === null) {{'0'}} @else{{ $val->ho_so_vay }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-success" style="font-weight: bold;">@if($val->duocduyet === null) {{'0'}} @else{{ $val->duocduyet }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-success" style="font-weight: bold;">@if($val->tile === null) {{'0'}} @else{{ $val->tile }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-warning" style="font-weight: bold;">@if($val->khoan_vay === null) {{'0 Triệu VNĐ'}} @else{{ $val->khoan_vay.' Triệu VNĐ' }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-success" style="font-weight: bold;">@if($val->giai_ngan === null) {{'0 Triệu VNĐ'}} @else {{ $val->giai_ngan.' Triệu VNĐ'}} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-success" style="font-weight: bold;">@if($val->giai_ngan_thuc === null) {{'0 Triệu VNĐ'}} @else {{ $val->giai_ngan_thuc.' Triệu VNĐ'}} @endif</span></td>
                                <form action="{{url('admin/report-credit-institutions-save')}}" method="post" accept-charset="utf-8">
                                    @csrf
                                    <td>
                                        <input type="hidden" name="tctd_id" value="{{$val->id_tctd}}">
                                        <input type="hidden" name="role_id" value="9">
                                        @if($val->thang > 0 && $val->thang < 10)
                                            <?php $thang = '0'.$val->thang; ?>
                                        @else
                                            <?php $thang = $val->thang; ?>
                                        @endif
                                        <input type="date" name="date_note" value="{{$val->nam}}-{{$thang}}-15" style="display: none;">
                                        <input class="form-control" type="text" name="note" value="{{$val->note}}" placeholder="">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary"><i class="voyager-list-add"></i>&#32;Lưu</button>
                                    </td>
                                </form>
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
    <script type="text/javascript">
        $('#year_search option[value={{$year}}]').attr('selected','selected');
        $('#tctd_search option[value={{$tctd_input}}]').attr('selected','selected');
    </script>
@stop



