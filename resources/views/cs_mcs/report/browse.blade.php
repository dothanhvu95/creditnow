@extends('voyager::master')



@section('page_header')

@stop



@section('content')
    @include('Chart.chart') {{-- file biểu đồ --}}
    <div class="page-content browse container-fluid">

        @include('vendor.voyager.checkuser')
        <?php $u_id = Auth::user()->id;?>
        <?php $role_id = Auth::user()->role_id;?>
        @if($role_id == 5)
            @include('vendor.voyager.checkpemission')
        @endif
        <div class="row">
            <div class="col-sm-12 text-center" style="background-color: white; padding-bottom: 20px;">
                <div class="col-sm-12 text-left">
                    <h3 style="font-weight: bold;">Report</h3>
                </div>
            </div> 
        </div>
        <div class="panel panel-bordered">

            <div class="panel-body">

                <div class="table-responsive">
                    <div class="text-right">
                        <a href="{{url('admin/report-partner')}}" class="btn btn-success btn-add-new">
                            <i class="voyager-external"></i> <span>Xuất Excel - BC NH/TCTD</span>
                        </a>
                    </div>
                    <table id="dataTable" class="table table-hover">
                        <caption style="color: #22a7f0; margin-bottom: 30px; text-align:  center;"> &#9679;&nbsp;Bảng thống kê tình trạng hồ sơ vay theo từng ngân hàng&nbsp;&#9679;</span></caption>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Name <a href=""><i class="voyager-angle-down pull-right"></i></a></th>
                                <th>Description</th>
                                <th>Hồ sơ vay</th>
                                <th>Hồ sơ giải ngân</th>
                                <th>Số tiền giải ngân</th>
                                <th>Tỉ lệ share(%)</th>
                                <th>Khoản thu từ TCTD</th>
                                <th>Người phụ trách</th>
                            </tr>

                        </thead>

                        <tbody>
                            <?php $i = 1; 

                                $tongtienthu = 0;?>
                            @foreach($report_partner as $key => $val)
                                @csrf
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$val->tctd_name}}</td>
                                    <td>{{$val->tctd_description}}</td>
                                    <td>{{$val->lead}}</td>
                                    <td>{{$val->success}}</td>
                                    <td>@if($val->so_tien_giai_ngan){{$val->so_tien_giai_ngan.' Triệu VNĐ'}}@else {{'0 Triệu VNĐ'}} @endif</td>
                                    <td>{{$val->ratio_share.' %'}}</td>
                                    <td>@if($val->khoan_phai_tra){{$val->khoan_phai_tra.' Triệu VNĐ'}}@else {{'0 Triệu VNĐ'}} @endif</td>
                                    <td>@if($val->cs_name != null)<span class="label label-warning"><i class="icon voyager-person"></i>&nbsp;&nbsp;{{$val->cs_name}}</span>@else <span class="label label-danger">&nbsp;&nbsp;Không có người phụ trách</span> @endif</td>
                                </tr>
                                <?php
                                    $tongtienthu = $tongtienthu + $val->khoan_phai_tra
                                ?>
                            @endforeach
                            <tr style=" background-color: #3366cc; color: white; font-weight: bold;">
                              <td colspan="7" rowspan="" headers="">Tổng số tiền thu từ NH/TCTD : </td>
                              <td colspan="2" rowspan="" headers="">{{$tongtienthu.' Triệu VNĐ'}}</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="container">
                <div class="col-md-6">
                    <div id="chart_div" whith="500px" height="auto"></div>
                </div>
                <div class="col-md-6">
                    <div id="chart_div_1" whith="500px" height="auto"></div>
                </div>
                <form action="{{url('admin/report-mcs-cs-a')}}" method="post" accept-charset="utf-8">
                    @csrf
                    <div class="row text-center" style="margin-top: 20px;">
                        <label for="">Chọn năm : </label>
                        <select class="form-control" id="year_search" name="year">
                            @foreach(Config::get('constants.select_year') as $key => $value)
                            <option @if($key == date('Y')) {{'selected'}} @endif value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-danger mb-2">Xem biểu đồ</button>
                   </div>
                </form>
            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <div class="text-right">
                        <a href="{{url('admin/report-agency')}}" class="btn btn-success btn-add-new">
                            <i class="voyager-external"></i> <span>Xuất Excel - BC Agency</span>
                        </a>
                    </div>
                    <table id="dataTable" class="table table-hover">
                        <caption style="color: #22a7f0; margin-bottom: 30px; text-align:  center;"> &#9679;&nbsp;Bảng thống kê tình trạng hồ sơ vay của từng Agency&nbsp;&#9679;</span></caption>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Name <a href=""><i class="voyager-angle-down pull-right"></i></a></th>
                                <th>Referal</th>
                                <th>Hồ sơ vay</th>
                                <th>Hồ sơ giải ngân</th>
                                <th>Số tiền giải ngân</th>
                                <th>Tỉ lệ share(%)</th>
                                <th>Khoản thu từ TCTD</th>
                                <th>Người phụ trách</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1; 
                                $tongtientra = 0;
                            ?>
                            @if($report_agnecy->count() > 0)
                                @foreach($report_agnecy as $key => $val)
                                    @csrf
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$val->agency_name}}</td>
                                        <td>{{$val->ref_name}}</td>
                                        <td>{{$val->lead}}</td>
                                        <td>{{$val->success}}</td>
                                        <td>@if($val->so_tien_giai_ngan){{$val->so_tien_giai_ngan.' Triệu VNĐ'}}@else {{'0 Triệu VNĐ'}} @endif</td>
                                        <td>{{$val->ratio_share.' %'}}</td>
                                        <td>@if($val->khoan_phai_tra){{$val->khoan_phai_tra.' Triệu VNĐ'}}@else {{'0 Triệu VNĐ'}} @endif</td>
                                        <td>@if($val->support != null)<span class="label label-warning"><i class="icon voyager-person"></i>&nbsp;&nbsp;{{$val->support}}</span>@else <span class="label label-danger">&nbsp;&nbsp;Không có người phụ trách</span> @endif</td>
                                    </tr>
                                    <?php
                                        $tongtientra = $tongtientra + $val->khoan_phai_tra
                                    ?>
                                @endforeach
                                <tr style=" background-color: #3366cc; color: white; font-weight: bold;">
                                  <td colspan="7" rowspan="" headers="">Tổng số tiền chi cho Agency : </td>
                                  <td colspan="2" rowspan="" headers="">{{$tongtientra.' Triệu VNĐ'}}</td>
                                </tr>
                            @else
                            <tr>
                                <td colspan="10" rowspan="" headers="" style="text-align:  center;">không có dữ liệu trong bảng này</td>
                            </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
<script type="text/javascript">
   function myFunction() {
        document.getElementById("form_add_new").reset();
    };
    $('#year_search option[value={{$year_s}}]').attr('selected','selected');     
</script>
<style type="text/css" media="screen">
    .marr
    {
        padding-bottom: 25px;
    }
</style>
@stop



