@extends('voyager::master')



@section('page_title')



@section('page_header')
@stop
@section('content')
<div class="page-content browse container-fluid">
            <div class="row">
               @foreach($history_log as $key => $row)
                <div class="col-md-12">
                    @if($row->progress_info === null && $row->history_log_id === null) 
                        <span class="label label-danger">Tài khoản thỉ chấm điểm</span>
                    @elseif($row->progress_info < 70 && $row->history_log_id === null)
                        <span class="label label-info">Tài khoản chưa cập nhật đầy đủ</span>
                    @elseif($row->progress_info > 70 && $row->history_log_id === null)
                        <span class="label label-warning">Tài khoản chưa gửi hồ sơ vay</span>
                    @elseif($row->progress_info > 70 && $row->history_log_id !== null)
                        <span class="label label-success">Tài khoản đã gửi hồ sơ vay</span>
                    @endif
                    <span>Trạng thái hồ sơ: 
                    <span class="
                    @if($row->trangthai == 1) 
                        {{'label label-danger'}}
                    @elseif($row->trangthai == 2) 
                        {{'label label-success'}}
                    @elseif($row->trangthai == 3)
                        {{'label label-primary'}}
                    @elseif($row->trangthai == 4) 
                        {{'label label-info'}}
                    @elseif($row->trangthai == 5) 
                        {{'label label-warning'}}
                    @elseif($row->trangthai == 6) 
                        {{'label label-success'}}
                    @elseif($row->trangthai == 7) 
                        {{'label label-dark'}} 
                    @endif">{{ $row->status_name}} </span></span>
                    <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="col-sm-6 ">
                            <div>
                                <div class="row">
                                    <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                        <h3 style="    text-transform: uppercase;">Thông tin cá nhân</h3>
                                    </div>
                                    <table class="table table-hover table-responsive">
                                      <tbody class="col">
                                        <tr>
                                          <td>Họ và tên : <span class="text-boil">{{$row->name}}</span> </td>
                                          <td>CMND : <span class="text-boil"> {{$row->cmnd}}</span></td>
                                        </tr>
                                        <tr>
                                          <td colspan="2">CCCD : <span class="text-boil"> {{$row->cmnd}}</span></td>
                                        </tr>
                                         <tr>
                                          <td>Email : <span class="text-boil">{{$row->email}}</span> </td>
                                          <td>SĐT : <span class="text-boil"> {{$row->phone}} </span></td>
                                        </tr>
                                        <tr>
                                          <td>Giới tính : <span class="text-boil">{{$row->gender}}</span> </td>
                                          <td>Ngày sinh : <span class="text-boil"> {{ $row->age }} </span></td>
                                        </tr>
                                         <tr>
                                          <td>Họ tên vợ/chồng: <span class="text-boil"></span> </td>
                                          <td>CMND vợ/chồng: <span class="text-boil">  </span></td>
                                        </tr>
                                        <tr>
                                          <td>CCCD vợ/chồng: <span class="text-boil"></span> </td>
                                          <td>Số người con : <span class="text-boil">  </span></td>
                                        </tr>
                                        <tr>
                                          <td colspan="2">Địa chị thường trú : <span class="text-boil">
                                              <?php $addr = unserialize($row->address1) ?>
                                              {{ $addr['address_full'] }}
                                          </span> </td>
                                        </tr>
                                         <tr>
                                          <td colspan="2">Địa chỉ tạm trú : <span class="text-boil">
                                              <?php $addr_u = unserialize($row->address2) ?>
                                              {{ $addr_u['address_full_usualy'] }}
                                          </span> </td>
                                        </tr>
                                         <tr>
                                          <td>Công việc: <span class="text-boil">
                                              <?php $job =  unserialize($row->job)?>
                                                @if($job['congviec'] === 'co')
                                                    {{ 'Có' }}
                                                @else
                                                    {{ 'Không' }}
                                                @endif
                                          </span> </td>
                                          <td>Thu nhập trung bình: <span class="text-boil">
                                             
                                               {{  $row->income }}
                                               
                                        VNĐ </span></td>
                                        </tr>
                                         
                                        <tr>
                                          <td>Khoản muốn vay: <span class="text-boil">
                                            {{  $row->khoanvay }}
                                      VNĐ</span> </td>
                                          <td>Ghi chú: <span class="text-boil"> {{  $row->note }} </span></td>
                                        </tr>
                                        <tr>
                                          <td>Điểm: <span class="text-boil"> {{  $row->cib_point }} Điểm </span> </td>
                                          <td>Khoản cho vay: <span class="text-boil">{{  $row->so_tien_giai_ngan }}  Triệu VNĐ </span> </td>
                                        </tr>
                                         <tr>
                                          <td>Lãi suất:<span class="text-boil">{{  $row->interest_rate }} %/Năm</span> </td>
                                          <td>Kỳ hạn vay: <span class="text-boil">{{  $row->duration }} Tháng </span> </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                        <h3 style="    text-transform: uppercase;">Thông tin cic</h3>
                                    </div>
                                    <table class="table table-hover table-responsive">
                                      <tbody class="col">
                                        <tr>
                                          <td>Mã CIC: <span class="text-boil"> {{  $row->cid_code }}</span> </td>
                                        </tr>
                                         <tr>
                                            <td>Họ và tên : <span class="text-boil"> {{  $row->cid_customer_name }} </span></td>
                                        </tr>
                                         <tr>
                                          <td>Địa chỉ : <span class="text-boil"> {{  $row->cid_customer_address }} </span> </td>
                                        </tr>
                                        <tr>
                                          <td>SĐT : <span class="text-boil"> {{  $row->cid_customer_tell }} </span> </td>
                                        </tr>
                                        <tr>
                                          <td>Đánh giá : <span class="text-boil"> {{ $row->cid_result }} </span> </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                            <div class="" style="margin-top: 20px;">
                            @foreach($user_tax as $key => $usertax)
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                        <h3 style="    text-transform: uppercase;">Thông tin thuế</h3>
                                    </div>
                                    <table class="table table-hover table-responsive">
                                      <tbody class="col">
                                        <tr>
                                            <td>Họ và tên : <span class="text-boil">{{ $usertax->name }}</span></td>
                                            <td>Mã số thuế: <span class="text-boil">{{ $usertax->mst }}</span></td>
                                        </tr>
                                         <tr>
                                            <td>CMND : <span class="text-boil"> {{ $usertax->cmnd }} </span></td>
                                            <td>CCCD : <span class="text-boil">{{ $usertax->cccd }}</span> </td>
                                        </tr>
                                         <tr>
                                            <td colspan="2">SĐT : <span class="text-boil"> {{ $usertax->phone }} </span></td>
                                        </tr>
                                        <tr>
                                            <td>Ngày đăng ký : <span class="text-boil">{{ $usertax->created_date }}</span> </td>
                                            <td>Ngày đóng : <span class="text-boil">{{ $usertax->closed_date }}</span> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Địa chỉ đăng ký : <span class="text-boil">{{ $usertax->address }}</span> </td>
                                        </tr>
                                        <tr>
                                          <td colspan="2">Tỉnh - Thành phố : <span class="text-boil">{{ $usertax->city }}</span> </td>
                                        </tr>
                                        <tr>
                                          <td colspan="2">Ghi chú : <span class="text-boil"></span> </td>
                                        </tr>

                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="width: 70%; font-size: 24px;">
                <form class="form-inline" action="{{url('admin/update-note-admin', [$row->history_log_id,$row->cmnd])}}" method="post">
                   @csrf
                   <div class="container">
                       <div class="row">
                          <div class="form-group col-md-12">
                                  <label class="text-boil" for="inputEmail4">Duyệt hồ sơ :</label>
                                  <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="status">
                                      @foreach($logsstatus as $key => $data)
                                      <option value="{{$data->id}}">{{$data->name}}</option>
                                      @endforeach
                                  </select>
                          </div>
                      </div>
                       <div class="row">
                            <div class="form-group col-md-12">
                                <label class="text-boil" for="inputEmail4">Ghi chú :</label>
                                <textarea name="admin_note" placeholder="nhập ghi chú.." id="" cols="30" style="width: 100%; " ></textarea>
                            </div>
                        </div>
                      <div class="row text-center" >
                          <button type="submit" class="btn btn-primary"><i class="voyager-check"></i> Duyệt</button>
                          <a href="" class="btn btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="voyager-eye"></i> Xem chi tiết</a>
                      </div>
                   </div>
              </form>                         
            </div>
            @endforeach
        </div>
        </div>

    <!-- End Delete File Modal -->
    @include('admin.history.modal_file')
@stop

