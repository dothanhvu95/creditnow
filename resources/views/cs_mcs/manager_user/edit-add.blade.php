@extends('voyager::master')



@section('page_header')

@stop



@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="row">
                    <div class="col-sm-12 text-left">
                        <h3 style="font-weight: bold;">Báo cáo tình trạng theo từng tổ chức tín dụng</h3>
                    </div>
                </div>
                @include('vendor.voyager.checkuser')
                <?php 
                    $role_id = Auth::user()->role_id;
                    $user_id = Auth::user()->id; 
                ?>
                <form class="form-inline" action="{{url('admin/manager-partner-cs',$id)}}" method="POST">
                    @csrf
                    <div class="col-sm-12 text-left">
                        <div class="form-group row">
                        <label for="colFormLabelLg" style="font-weight: bold;">Thời gian :</label>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword6" >Từ: </label>
                            <input type="date" name="from_date_tn" id="from_date_TN" value="{{date("Y")}}-01-01" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword6" >Đến: </label>
                            <input type="date" name="to_date_tn" id="to_date_TN" value="{{ date("Y") }}-12-31" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
                        </div>
                        {{-- <div class="form-group row">
                        <label for="colFormLabelLg" style="font-weight: bold;">Tên TCTD :</label>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="status_search_history" name="status" style="min-width: 200px;">
                                 <option value="">Chọn TCTD</option>
                                @foreach($lender as $key => $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($role_id === 6)
                        <div class="form-group">
                            <select class="form-control" id="status_search_history" name="status" style="min-width: 200px;">
                                 <option value="">Chọn người phụ trách</option>
                                @foreach($cs as $key => $rows)
                                    <option value="{{$rows->id}}">{{$rows->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif --}}
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
                                <th>Thời gian</th>
                                <th>Tên TCTD</th>
                                <th>Hợp đồng <a href=""><i class="voyager-angle-down pull-right"></i></a></th>
                                <th>% TCTD trả</th>
                                <th>Số lượng lead</th>
                                <th>Số lượng giải ngân</th>
                                <th>Tỷ lệ thành công</th>
                                <th>Số tiền giải ngân</th>
                                <th>Số tiền TCTD phải trả</th>
                                <th>Số tiền TCTD đã trả</th>
                                <th>Số tiền TCTD nợ lại</th>
                                <th>Tình hình đối soát</th>
                                <th>Người phụ trách</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach($report as $key => $val)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>@if($val->ngaygui != null)  {{$val->ngaygui}} @else {{"Chưa có hồ sơ"}} @endif </td>
                                <td>@if($val->tctd_name != null)  {{$val->tctd_name}} @else {{"Chưa có hợp đồng"}} @endif </td>
                                <td>@if($val->description != null)  {{$val->description}} @else {{"Chưa xét"}} @endif </td>
                                <td>{{$val->contract}}</td>
                                <td>@if($val->tong_lead != null)  {{$val->tong_lead}} @else {{"0"}} @endif </td>
                                <td>@if($val->tong_giai_ngan != null)  {{$val->tong_giai_ngan}} @else {{"0"}} @endif </td>
                                <td>@if($val->tile_thanh_cong != null)  {{$val->tile_thanh_cong.' %'}} @else {{"0 %"}} @endif </td>
                                <td>@if($val->so_tien_giai_ngan != null)  {{$val->so_tien_giai_ngan.' Triệu VNĐ'}} @else {{"0 Triệu VNĐ"}} @endif </td>
                                <td>{{'đợi tính'}}</td>
                                <td>{{'đợi tính'}}</td>
                                <td>{{'đợi tính'}}</td>
                                <td>{{'đợi tính'}}</td>
                                <td>@if($val->nguoi_phu_trach != null)  {{$val->nguoi_phu_trach}} @else {{"Chưa có"}} @endif </td>
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

                {{-- table lịch sử làm việc --}}
                @if($role_id == 5)
                <button type="button" class="btn btn-primary text-right" data-toggle="modal" data-target="#exampleModalCenter">
                    Lưu công việc
                    </button>
                @endif
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover">
                        <thead>
                            <tr style="font-weight: bold;">
                                <th>STT</th>
                                <th>Ngày</th>
                                <th>Công việc</th>
                                <th>Người liên lạc <a href=""><i class="voyager-angle-down pull-right"></i></a></th>
                                <th>Ghi chú</th>
                                <th>Đính kèm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach($history as $key => $value)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>@if($value->created_at != null)  {{ date('d-m-Y', strtotime($value->created_at)) }} @endif </td>
                                <td>{{$value->job}}</td>
                                <td>@if($value->support != null)  {{$value->support}} @else {{"Chưa cập nhật"}} @endif </td>
                                <td>@if($value->note != null)  {{$value->note}}  @endif </td>
                                <td>@if($value->file != null)  <a href="{{'http://demo.creditscore.vn/BackEnd/public/uploads/'.$value->file}}" target="_blank">Xem tệp đính kèm</a> @else {{"Không có đính kèm"}} @endif </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                     <div class="container">
                        <div class="row ">
                            <div class="col-sm-12 text-center">
                                {{ $history->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="{{url('admin/save-cs-job',$id)}}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Công việc đã làm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @if($history->count() > 0)
            <?php
                $created_at = strtotime($history[0]->created_at);
                $current_time = strtotime(date('Y-m-d H:i:s'));
                $interval  = abs($current_time - $created_at);
                $day  = round($interval / (60*60*24));
                if ($day <= 0) {
                    $result = $history[0]->job;
                    $id_result = $history[0]->id;
                    $note = $history[0]->note;
                }
                else
                {
                    $result = "";
                }
            ?>
          @endif
          <div class="modal-body">
            <div class="form-group row">
                <input type="hidden" name="id_result" value="@if(@$id_result) {{$id_result}} @endif">
                <label for="inputPassword" class="col-sm-2 col-form-label">Mô tả công việc:</label>
                <div class="col-sm-10">
                  <textarea name="job" class="form-control" value="" placeholder="Nhập các mô tả công việc của bạn vào đây..." id="" cols="30" style="width: 100%; min-height: 250px; margin-bottom: 5px;">@if(@$result){{$result}}@endif</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Ghi chú:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="note" value="@if(@$note) {{$note}} @endif" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Tệp đính kèm:</label>
                <div class="col-sm-10">
                  <input type="file" class="form-control" name="photo" value="" placeholder="">
                </div>
            </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </div>
    </form>
  </div>
</div>
@stop
@section('javascript')
<script type="text/javascript">
</script>
@stop



