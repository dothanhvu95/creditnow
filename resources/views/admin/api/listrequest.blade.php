@extends('voyager::master')



@section('page_header')

@stop



@section('content')
    <div class="page-content browse container-fluid">
         @include('vendor.voyager.checkuser')
 
        <?php $u_id = Auth::user()->id;?>
        <?php $role_id = Auth::user()->role_id;?>
        <div class="row">

            <div class="col-sm-12 text-center" style="background-color: white; padding-bottom: 20px;">

                <div class="row">

                    <div class="col-sm-12 text-left">

                        <h3 style="font-weight: bold;">Quản lý Request </h3>

                    </div>

                </div>
            </div>
        </div>

        <div class="panel panel-bordered">

            <div class="panel-body">

                <div class="table-responsive">

                    <table id="dataTable" class="table table-hover">
                        <thead>

                            <tr>
                                <th>STT</th>
                                <th> Họ Tên </th>
                                <th>CMND</th>
                                <th>CCCD</th>
                                <th>Điểm</th>
                                <th>Khoản cho vay</th>
                                <th>Lãi suất</th>
                                <th>Kỳ hạn vay</th>
                                <th>Ngày chấm điểm</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $i = 1; ?>
                            @if($api_log->count() > 0)
                            @foreach($api_log as $key => $val)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$val->name}}</td>
                                <td>{{$val->cmnd}}</td>
                                <td>{{$val->cccd}}</td>
                                <td>{{$val->score}}</td>
                                <td>{{$val->loan}}</td>
                                <td>{{$val->interest_rate}}</td>
                                <td>{{$val->duration}}</td>
                                <td>{{date('d-m-Y', strtotime($val->created_at)) }}</td>
                                <td>
                                    @if($val->status == 1)
                                    <span class="label label-success">Success</span>
                                    @else
                                    <span class="label label-danger">Duplicate data in system</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="10" rowspan="" headers="" style="text-align:  center;">Không có dữ liệu trong bảng này</td>
                            </tr>
                            @endif
                        </tbody>

                    </table>
                    <nav style="float: right;">
                            {!!$api_log->render()!!}
                        </nav>

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



   

@stop
@section('javascript')

@stop



