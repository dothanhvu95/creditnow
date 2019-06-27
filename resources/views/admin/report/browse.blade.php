@extends('voyager::master')

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
                        <h3 style="font-weight: bold;">Đối soát</h3>
                    </div>
                </div>
                <?php 
                    $role_id = Auth::user()->role_id;
                    $user_id = Auth::user()->id; 
                ?>
                <form class="form-inline" action="{{url('admin/for-control')}}" method="POST">

                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"/>

                     <div class="col-sm-12 text-left">
                        <div class="form-group row">
                            <label for="colFormLabelLg" style="font-weight: bold;">Chọn năm:</label>
                        </div>
                        <select class="form-control" id="year_search" name="year">
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                       <?php if($role_id == 1 || $role_id == 4) : ?>
                       <div class="form-group">
                            <label for="colFormLabelLg" style="font-weight: bold;">Chọn Agency:</label>
                        </div>
                        <select class="form-control" id="year_search" name="agency">
                            <option value="">Xem tất cả</option>
                                @foreach($agence as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                        </select>
                       <?php endif ?>
                       <?php if($role_id != 1 && $role_id != 4 && $role_id != 5 && $role_id != 6 && $role_id != 7) : ?>
                            <div class="form-group">
                                <label for="colFormLabelLg" style="font-weight: bold;">Chọn referals:</label>
                            </div>
                            <select class="form-control" id="" name="referals">
                                <option value="">Xem tất cả</option>
                                @foreach($referals as $key => $value)
                                @if($user_id == $value->user_id)
                                    <option @if($ref == $value->id) {{'selected'}} @endif  value="{{$value->id}}">{{$value->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        <?php endif ?>
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
                                <th>Tháng</th>
                                <th>Total</th>
                                <th>Số hồ sơ chấm điểm <a href=""><i class="voyager-angle-down pull-right"></i></a></th>

                                <th>Số hồ sơ vay</th>

                                <th>Được duyệt</th>

                                <th>Tỷ lệ thành công</th>

                                <th>Tổng số tiền được giải ngân</th>

                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody >


                            @foreach($history as $val)

                            <tr>
                                <td colspan="" rowspan="" headers="" style="color: #22a7f0;"><span style="font-weight: bold;">{{'Tháng '.$val->Thang.'-'.$val->Nam}}</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-info" style="font-weight: bold;">@if($val->total === null) {{'0'}} @else{{ $val->total }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-danger" style="font-weight: bold;">@if($val->account === null) {{'0'}} @else{{ $val->account }} @endif</span></td>
                                
                                <td colspan="" rowspan="" headers=""><span class="label label-warning" style="font-weight: bold;">@if($val->ho_so_vay === null) {{'0'}} @else{{ $val->ho_so_vay }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-success" style="font-weight: bold;">@if($val->duocduyet === null) {{'0'}} @else{{ $val->duocduyet }} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-info" style="font-weight: bold;">@if($val->ti_le === null) {{'0 %'}} @else {{ $val->ti_le.' %'}} @endif</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-danger" style="font-weight: bold;">@if($val->giai_ngan === null) {{'0 Triệu VNĐ'}} @else {{ $val->giai_ngan.' Triệu VNĐ'}} @endif</span></td>
                                <td colspan="" rowspan="" headers="">
                                    <?php
                                        if ($val->Thang > 0 && $val->Thang < 10) {
                                            $from_date = $val->Nam.'-0'.$val->Thang.'-01' ; 
                                            $to_date =  $val->Nam.'-0'.$val->Thang.'-31'; 
                                         }
                                         else
                                         {
                                            $from_date = $val->Nam.''.$val->Thang.'-01' ; 
                                            $to_date = $val->Nam.''.$val->Thang.'-31' ; 
                                         }
                                    ?>
                                    <a href="{{url('admin/history',[$from_date,$to_date])}}" type="submit" class="btn btn-sm btn-primary pull-center edit"><i class="voyager-eye"></i><span class="hidden-xs hidden-sm"> Xem chi tiết</span></a>
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
    <script type="text/javascript">
        $('#year_search option[value={{$year}}]').attr('selected','selected');
    </script>
@stop



