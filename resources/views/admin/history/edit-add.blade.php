@extends('voyager::master')
@section('page_title')
@section('page_header')
@stop
@section('content')

<div class="page-content browse container-fluid">
            <div class="row">
               @foreach($history_log as $key => $row)
                <div class="col-md-12">
                  <div style="padding-bottom: 15px;">
                    <span style="padding-bottom: 10px;">Phân loại hồ sơ: </span>
                    @if(($row->progress_info == 5) && ($row->history_log_id == null))
                        <span class="label label-danger">Hồ sơ chỉ chấm điểm</span>
                    @elseif($row->progress_info < 50 && $row->history_log_id != null)
                        <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                    @elseif($row->progress_info < 50 && $row->history_log_id == null)
                        <span class="label label-info">Hồ sơ chưa cập nhật đầy đủ</span>
                    @elseif($row->progress_info >= 50 && $row->history_log_id == null)
                        <span class="label label-warning">Hồ sơ chưa gửi hồ sơ vay</span>
                    @elseif($row->progress_info > 50 && $row->history_log_id !==null)
                        <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                    @endif
                    <br>
                    @if($row->trangthai !== null)
                    <span style="padding-bottom: 10px;">Trạng thái hồ sơ: 
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
                          {{'label label-warning'}}
                      @elseif($row->trangthai == 7) 
                          {{'label label-dark'}}
                      @elseif($row->trangthai == 8) 
                          {{'label label-danger'}}
                      @elseif($row->trangthai == 9) 
                          {{'label label-danger'}}  
                      @endif">{{ $row->status_name}} </span></span>
                    @else
                      <span style="padding-bottom: 10px;">Trạng thái hồ sơ: 
                      <span class="
                      @if($row->status == 1) 
                          {{'label label-danger'}}
                      @elseif($row->status == 2) 
                          {{'label label-success'}}
                      @elseif($row->status == 3)
                          {{'label label-primary'}}
                      @elseif($row->status == 4) 
                          {{'label label-info'}}
                      @elseif($row->status == 5) 
                          {{'label label-warning'}}
                      @elseif($row->status == 6) 
                          {{'label label-warning'}}
                      @elseif($row->status == 7) 
                          {{'label label-dark'}}
                      @elseif($row->trangthai == 8) 
                          {{'label label-danger'}}
                      @elseif($row->trangthai == 9) 
                          {{'label label-danger'}}   
                      @endif">{{ $row->status_name}} </span></span>
                    @endif
                      <br>
                      @if($row->cs_id_history !== null)
                      <span style="padding-bottom: 10px;">Người duyệt hồ sơ:
                      <span style="font-weight: bold;">{{$row->cs_name_history}}</span> 
                      </span>
                      @endif
                      @if($row->cs_id_log !== null)
                      <span style="padding-bottom: 10px;">Người duyệt hồ sơ:
                      <span style="font-weight: bold;">{{$row->cs_name_log}}</span> 
                      </span>
                      @endif
                  </div>
                    <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="col-sm-6 " >
                            <div>
                                <div class="row">
                                    <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                        <h3 style="    text-transform: uppercase;">Thông tin cá nhân</h3>
                                    </div>
                                    <table class="table table-hover table-responsive table-bordered">
                                      <tbody class="col">
                                        <input type="hidden" name="" id="history_id" @if($row->history_log_id != null) value="{{$row->history_log_id}}" @else value="{{$row->id}}" @endif>
                                        <input type="hidden" name="" id="log_id_1" @if($row->id != null) value="{{$row->id}}" @endif>
                                        <input type="hidden" name="" id="khoanvay_send_1" @if($row->khoanvay != null) value="{{$row->khoanvay}}" @endif>
                                        <tr>
                                          <td>Họ và tên : <span class="text-boil" id="name">{{$row->name}}</span> </td>
                                          <td>CMND : <span class="text-boil" id="cmnd"> {{$row->cmnd}}</span></td>
                                        </tr>
                                        <tr>
                                          <td colspan="2">CCCD : <span class="text-boil" id="cccd"></span></td>
                                        </tr>
                                         <tr>
                                          <td>Email : <span class="text-boil">{{$row->email}}</span> </td>
                                          <td>SĐT : <span class="text-boil" id="phone"> {{$row->phone}} </span></td>
                                        </tr>
                                        <tr>
                                          <td>Giới tính : <span class="text-boil">{{$row->gender}}</span> </td>
                                          <?php $date = date('d-m-Y', strtotime($row->age)); ?>
                                          <td>Ngày sinh : <span class="text-boil" id="age">@if($date != '01-01-1770' && $date !='01-01-1970') {{date('d-m-Y', strtotime($row->age))}} @endif</span></td>
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
                                          <td colspan="2">Địa chị thường trú : <span class="text-boil" id="adress">
                                              <?php
                                                  $city = DB::table('devvn_tinhthanhpho')->get()->toArray();
                                                  $idPronvi = unserialize($row->address1)['matp_address'];
                                                  if(isset($idPronvi))
                                                  {
                                                    foreach ($city as $key => $value) {
                                                        if($idPronvi == $value->matp)
                                                        {
                                                          echo '<input type="hidden" id="tinh_id" name="tinh_id" value="'.$idPronvi.'">
                                                                  '. $value->name.'';
                                                        }
                                                    }
                                                  }
                                                 
                                              ?>
                                              <?php $addr = unserialize($row->address1) ?>
                                              
                                          </span> </td>
                                        </tr>
                                         <tr>
                                          <td colspan="2">Địa chỉ tạm trú : <span class="text-boil" id="address_u">
                                              <?php $addr_u = unserialize($row->address2) ?>
                                              {{ @$addr_u['address_full_usualy'] }}
                                          </span> </td>
                                        </tr>
                                         <tr>
                                          <td>Công việc: <span class="text-boil">
                                              <?php $job =  unserialize($row->job)?>
                                                @if(isset($job['check_job']) && $job['check_job'] === 'co')
                                                    {{ 'Có' }}
                                                @else
                                                    {{ 'Không' }}
                                                @endif
                                          </span> </td>
                                          <td>Thu nhập trung bình: <span class="text-boil" id="thunhap">{{  $row->income }}</span></td>
                                        </tr>
                                         
                                        <tr>
                                          <td>Khoản muốn vay: <span class="text-boil" id="khoanvay">
                                            {{  $row->khoanvay }}
                                      VNĐ</span> </td>
                                          <td>Ghi chú: <span class="text-boil"> {{  $row->note }} </span></td>
                                        </tr>
                                        <tr>
                                          <td>Điểm: <span class="text-boil"> {{  $row->final_score }} Điểm </span> </td>
                                          <td>Khoản cho vay: <span class="text-boil">{{  $row->loan }}  Triệu VNĐ </span> </td>
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
                                <div class="row" >
                                    <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                        <h3 style="    text-transform: uppercase;">Thông tin cic</h3>
                                    </div>
                                    <table class="table table-hover table-responsive table-bordered">
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
                                  <div class="row" >
                                      <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                                          <h3 style="    text-transform: uppercase;">Thông tin thuế</h3>
                                      </div>
                                      <table class="table table-hover table-responsive table-bordered">
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
                                            <?php $date_1 = date('d-m-Y', strtotime($usertax->created_date)); ?>
                                            <?php $date_2 = date('d-m-Y', strtotime($usertax->closed_date));?>                                              
						<td>Ngày đăng ký : <span class="text-boil">@if($date_1 != '01-01-1970'){{date('d-m-Y', strtotime($usertax->created_date))}} @endif</span> </td>
                                              <td>Ngày đóng : <span class="text-boil">@if($usertax->closed_date != '0000-00-00 00:00:00'){{ date('d-m-Y', strtotime($usertax->closed_date)) }} @endif</span> </td>
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
                @if($row->history_log_id !== null)
                  <form onsubmit="return confirm('Bạn có muốn thay đổi trạng thái ?')" class="form-inline" action="{{url('admin/update-note-admin', [$row->history_log_id,$row->cmnd])}}" method="post">
                @else
                  <form onsubmit="return confirm('Bạn có muốn thay đổi trạng thái ?')" class="form-inline" action="{{url('admin/update-note-admin', [$row->id,$row->cmnd])}}" method="post">
                @endif
                   @csrf
                   <div class="container">
                       <div class="row">
                          <input type="hidden" name="email" value="{{$row->email}}">
                          <input type="hidden" name="hoten" value="{{$row->name}}"> 
                          <input type="hidden" name="cmnd" value="{{$row->cmnd}}"> 
                          <input type="hidden" name="sdt" value="{{$row->phone}}">
                          <div class="form-group col-md-3">
                                  <label class="text-boil" for="inputEmail4">Duyệt hồ sơ :</label>
                                  @if($row->trangthai !== null)
                                    <?php $status = $row->trangthai ?>
                                  @else 
                                    <?php $status = $row->status ?> 
                                  @endif
                                  <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="status">
                                      @foreach($logsstatus as $key => $data)
                                        <option @if($status == $data->id) {{"selected"}} @endif value="{{$data->id}}">{{$data->name}}</option>
                                      @endforeach
                                  </select>
                          </div>  
                          <div class="form-group col-md-8">
                            @if($lender_results->count() > 0 )
                            @foreach($lender_results as $key => $rows)

                              <div class="col-md-4" style="border: 1px solid;border-radius: 2px;padding: 20px 0px 0px 10px;width: 47%;background: wheat;margin:0px 10px 10px 10px">
                                <label>Tổ chức tín dụng : <span style="font-weight: bold; color: black;">
                                  <?php $name_tctd = App\Lender::where('id',$rows['tctd_id'])->first(); ?>
                                  {{$name_tctd['name']}}
                                </span> </label><br>
                                <label>Nội dung:  <span style="font-weight: bold; color: black;">@if($rows->content != null) {{$rows->content}} @else {{'Tổ chức tín dụng chưa trả lời'}} @endif </span></label><br>
                                <label>Trạng thái:  <span style="font-weight: bold; color: black;">
                                  <?php $name_status = App\LogsStatus::where('id',$rows['status'])->first();?>
                                  {{$name_status['name']}}
                                </span></label><br>
                                <label>Ngày tiếp nhận: <span style="font-weight: bold; color: black;"> @if($rows->content != null){{date('d-m-Y', strtotime($rows->created_at))}} @else {{'Tổ chức tín dụng chưa trả lời'}} @endif </span></label><br>
                                <label>Ngày duyệt hsv: <span style="font-weight: bold; color: black;"> @if($rows->content != null) {{$rows->ngay_giai_ngan}} @else {{'Tổ chức tín dụng chưa trả lời'}} @endif </span> </label><br>
                                <label>Số tiền giải ngân: <span style="font-weight: bold; color: black;"> @if($rows->content != null) {{App\MrData::toPicePrivate($rows->so_tien_giai_ngan)}} @else {{'Tổ chức tín dụng chưa trả lời'}} @endif </span></label>
                              </div>

                            @endforeach
                            @endif
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
                          <!-- <a href="" type="button" id="sendto_TCTD" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter"><i class="voyager-move"></i> GỬI QUA TCTD</a> -->
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
    <!-- modal send to tổ chức tín dụng -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="form-inline" action="{{url('admin/send-credit-inst')}}" method="POST">
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
                    <input type="hidden" id="log_send" name="log_send" value="">
                    <input type="hidden" id="log_id" name="log_id" value="">
                    <input type="hidden" id="name_send" name="name_send" value="">
                    <input type="hidden" id="phone_send" name="phone_send" value="">
                    <input type="hidden" id="cmnd_send" name="cmnd_send" value="">
                    <input type="hidden" id="cccd_send" name="cccd_send" value="">
                    <input type="hidden" id="addr_send" name="addr_send" value="">
                    <input type="hidden" id="addr_send_u" name="addr_send_u" value="">
                    <input type="hidden" id="tinh" name="addr_id_send" value="">
                    <input type="hidden" id="tctd_id_send" name="tctd_send" value="">
                    <input type="hidden" id="loan" name="loan" value="">
                    <input type="hidden" id="age_send" name="age_send" value="">
                    <input type="hidden" id="thunhap_send" name="thunhap_send" value="">
                    <div class="row">
                        <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                            <h4 style="    text-transform: uppercase;">Thông tin gửi đến tổ chức tín dụng</h4>
                        </div>
                        <table class="table table-hover table-responsive">
                          <tbody class="col">
                            <tr>
                              <td>Họ và tên : <span class="text-boil" id="name_send_1"></span></td>
                              <td>Số điện thoại : <span class="text-boil" id="phone_send_1"></span></td>
                            </tr>
                            <tr>
                              <td>CMND : <span class="text-boil" id="cmnd_send_1"></span></td>
                              <td>CCCD : <span class="text-boil" id="cccd_send_1"></span> </td>
                            </tr>
                            <tr>
                              <td colspan="2">Địa chỉ : <span class="text-boil" id="addr_send_1"></span></td>
                            </tr>
                            <tr>
                              <td colspan="2">Khoản muốn vay : <span class="text-boil" id="khoanvay_send"></span></td>
                            </tr>
                            <tr>
                              <td colspan="2">Tên tổ chức tín dụng : <span class="text-boil" id="tctd_send_1"></span> </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Gửi</button>
          </div>
        </div>
        </form>
      </div>
    </div>
<style type="text/css" media="screen">
  .text-boil
  {
    font-weight: bold;
  }
</style>
@stop
@section('javascript')
<script type="text/javascript">
  $('#sendto_TCTD').click(function(event) {
    /* Act on the event */
    var khoanvay = $('#khoanvay').text();
    var khoanvay_send = $('#khoanvay_send_1').val();
    var historyid =$('#history_id').val(); 
    var log_id =$('#log_id_1').val(); 
    var name =     $('#name').text();
    var cmnd =     $('#cmnd').text();
    var cccd =     $('#cccd').text();
    var phone =     $('#phone').text();
    var adress =   $('#adress').text();
    var age = $('#age').text();    
    var thunhap = $('#thunhap').text();
    var address_u = $('#address_u').text();
    var id_tp =   $('#tinh_id').val();
    var tctd_id = $('[name="to_chuc_tin_dung"]:radio:checked').val();
    var tctd_name = $("input[name='to_chuc_tin_dung']:checked").parent('label').text();
    if (tctd_id === undefined) 
    {
      alert('Bạn không được bỏ trống mục tổ chức tín dụng');
      return false;
    }
    else
    {
      $('#log_send').val(historyid);
      $('#log_id').val(log_id);
      $('#loan').val(khoanvay_send);
      $('#name_send').val(name);
      $('#phone_send').val(phone);
      $('#cmnd_send').val(cmnd);
      $('#cccd_send').val(cccd);
      $('#addr_send').val(adress);
      $('#tinh').val(id_tp);
      $('#age_send').val(age);
      $('#thunhap_send').val(thunhap);
      $('#tctd_id_send').val(tctd_id);
      $('#addr_send_u').val(address_u);
      document.getElementById("name_send_1").innerHTML = name ; 
      document.getElementById("khoanvay_send").innerHTML = khoanvay ; 
      document.getElementById("phone_send_1").innerHTML = phone ;
      document.getElementById("cmnd_send_1").innerHTML = cmnd ;
      document.getElementById("cccd_send_1").innerHTML = cccd ;
      document.getElementById("addr_send_1").innerHTML = adress ;
      document.getElementById("tctd_send_1").innerHTML = tctd_name ;
    }
  });


  /*******************************************************************/
  /*******************************************************************/
  /*******************************************************************/
  $('input[type=radio][name=to_chuc_tin_dung]').change(function() { 
    for (var i = 0; i <= 15 ; i++) {
      if (this.value == i) { 
        $('#idtctd_'+this.value+'').css('display', 'block');
      } 
      else
      {
        $('#idtctd_'+i+'').css('display', 'none');
      }
    }
  });
  /********************************************************************/
  /********************************************************************/
  /********************************************************************/
  var tctd_id = $('[name="to_chuc_tin_dung"]:radio:checked').val();
  for (var i = 0; i <= 15 ; i++) {
    if (tctd_id == i) { 
        $('#idtctd_'+tctd_id+'').css('display', 'block');
      } 
      else
      {
        $('#idtctd_'+i+'').css('display', 'none');
      }
    }
</script>
@stop


