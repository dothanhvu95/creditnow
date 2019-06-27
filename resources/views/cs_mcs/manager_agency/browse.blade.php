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
            <div class="col-sm-12 text-left">
                <h3 style="font-weight: bold;">Quản lý danh sách Agency</h3>
            </div>

            <form class="form-inline" action="" method="POST">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"/>
                <div class=" text-left" >
                    <div class="form-group ">
                        <label for="colFormLabelLg" style="font-weight: bold;">Nhập từ khóa tìm kiếm:</label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="name_agencines" value="" placeholder="Nhập từ khóa tìm kiếm...">
                    </div>
                    <button type="submit" class="btn btn-danger mb-2">Tìm kiếm</button>
                </div>
            </form>
            @if($role_id != 5)
            <div class="text-right">
                <button class="btn btn-success btn-add-new" data-toggle="modal" data-target="#exampleModalCenter">
                    <i class="voyager-plus"></i> <span>Thêm mới</span>
                </button>
            </div>
            @endif
        </div> 
    </div>
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th> Name <a href=""><i class="voyager-angle-down pull-right"></i></a></th>
                            <th>Địa chỉ</th>
                            <th>Người liên lạc</th>
                            <th>Phone</th>
                            <th>Referal</th>
                            <th>Email</th>
                            <th>Đ/c link</th>
                            <th>Số lượng lead</th>
                            <th>Được giải ngân</th>
                            <th>NH chấp nhận cho vay</th>
                            <th>Số tiền giải ngân</th>
                            <th>Số tiền TT cho Agency</th>
                            <th>Tình trạng</th>
                            <th>Người phụ trách</th>
                            @if($role_id != 5)
                             <th>Action</th>
                             @endif
                        </tr>
                    </thead>
                    <tbody>
                         <?php $i = 1; ?>
                            @if($agency->count() > 0)
                            @foreach($agency as $key => $val)
                            <form action="{{route('update-agency')}}" method="post" accept-charset="utf-8">
                                @csrf
                                <tr>
                                    <td>{{$i++}}</td>
                                    <input type="hidden" name="id" value="{{$val->id_agency}}">
                                    <td>{{$val->name_agency}}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{$val->phone_agency}}</td>
                                    <td>{{$val->ref_name}}</td>
                                    <td> {{$val->nguoi_lien_lac_user}} </td>
                                    <td>{{$val->slug}}</td>
                                    <td>@if($val->total_hsv != null || $val->total_hsv != "") {{$val->total_hsv}} @else {{'0'}} @endif </td>
                                    <td> {{$val->hsv_giaingan}} </td>
                                    <td>@if($val->ngan_hang_cho_vay != null || $val->ngan_hang_cho_vay != "") {{$val->ngan_hang_cho_vay}} @else {{'0'}} @endif </td>
                                    <td>  </td>
                                    <td>  </td>
                                    <td>  </td>
                                    <td>
                                        @if($role_id != 5)
                                        <select class="form-control" id="status_search_history" name="cs_id" style="min-width: 200px;">
                                            <option value="0">Chọn người phụ trách</option>
                                            @foreach($user as $key => $row)
                                                <option @if($val->cs_id == $row->id) {{'selected'}} @endif value="{{$row->id}}">{{$row->name}}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        @foreach($user as $key => $row)
                                        @if($val->cs_id == $row->id) {{$row->name}}@endif
                                        @endforeach
                                        @endif
                                    </td>
                                    @if($role_id != 5)
                                    <td><button type="submit" title="View" class="btn btn-sm btn-primary view">
                                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Lưu</span>
                                        </button></td>
                                    @endif
                                </tr>
                            </form>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="16" rowspan="" headers="" style="text-align:  center;">không có dữ liệu trong bảng này</td>
                            </tr>
                            @endif
                    </tbody>
                </table>

                <div id="columnchart" style="width: 100%; height: 500px;"></div>
                <div class="container">
                    <div class="row ">
                        <div class="col-sm-12 text-center">
                            {{-- {{ $lender->onEachSide(10)->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal           -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="{{route('add-new-agency')}}" method="post" accept-charset="utf-8" id="form_add_new">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Thêm mới Agency</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <div class="form-group marr">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Tên Agency :</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control is-valid"  name="name" id="inputPassword" placeholder="" required>
                    </div>
                 </div>
                <h5 style="padding: 10px;">-- Phần thông tin liên hệ + tài khoản :</h5>
                 <div class="form-group marr">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Tên người liên lạc :</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="name_user" id="inputPassword" placeholder="" required>
                    </div>
                 </div>
                 <div class="form-group marr">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Email :</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" name="email" id="inputPassword" placeholder="" required>
                    </div>
                 </div>
                    <input type="hidden" class="form-control" value="123456" name="pass" id="inputPassword" placeholder="" required>
                 <div class="form-group marr">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Phone :</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="phone" id="inputPassword" placeholder="" required>
                    </div>
                 </div>
                 <div class="form-group marr">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Tỉ lệ % tính :</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="ratio_share" id="inputPassword" placeholder="" required>
                    </div>
                 </div>
                 <div class="form-group marr">
                    <label for="inputPassword" class="col-sm-4 col-form-label">Người phụ trách:</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="status_search_history" name="cs_id" style="min-width: 200px;">
                            <option value="0">Chọn người phụ trách</option>
                            @foreach($user as $key => $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                 </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" onclick="myFunction()" class="btn btn-danger" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Thêm</button>
          </div>
        </div>
    </form>
  </div>
</div>


@stop
@section('javascript')
<script type="text/javascript">
   function myFunction() {
        document.getElementById("form_add_new").reset();
    };


    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart_1);

    function drawChart_1() {
    var data = google.visualization.arrayToDataTable([
      ['', 'Tổng HSV','HSV giải ngân'],
      <?php foreach ($agency as $key => $value) :?>
      ['{{'Agency '.$value->name_agency}}', @if($value->total_hsv) {{$value->total_hsv}} @else {{'0'}} @endif,@if($value->hsv_giaingan) {{$value->hsv_giaingan}} @else {{'0'}} @endif],
      <?php endforeach ?>
    ]);

    var options = {
      chart: {
        title: 'Biểu đồ thống kê tình trạng hồ sơ của Agency',
        subtitle: '',
      }
    };

    var chart = new google.charts.Bar(document.getElementById('columnchart'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>
<style type="text/css" media="screen">
    .marr
    {
        padding-bottom: 25px;
    }
</style>
@stop



