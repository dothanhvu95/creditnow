<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="page-content browse container-fluid">
            <div class="container">
                <div class="row">
                    <?php $url_img = '/api/public/uploads/logs/'; ?> 
                @foreach($history_log as $key => $row)
                   <section class="banner-area relative " style="margin-top: 0; margin-bottom: 0px; color: black;">
                        <div class="col-sm-12" style="background: white; margin-top: 0px; padding-bottom: 20px;">
                          <br>
                            <div class="text-center">
                                <h2><div id="">HỒ SƠ VAY</div> </h2>
                            </div>
                            <hr>
                            <div class="col-sm-6 text-left">
                                <div class="form-row">
                                    <label  style="font-weight: bold; color: black;" for="">Họ tên: </label>{{$row->name}}
                                </div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="form-row">
                                    <?php $date = date('d-m-Y', strtotime($row->age)) ?>
                                    <label  style="font-weight: bold; color: black;" for="">Ngày sinh:</label>@if($date != "01-01-1770" && $date !='01-01-1970'){{date('d-m-Y', strtotime($row->age))}} @endif
                                </div>
                            </div>
                            </br>
                            <div class="col-sm-6 text-left">
                                <div class="form-row">
                                    <label  style="font-weight: bold; color: black;" for="">CMND:</label> {{$row->cmnd}}
                                </div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="form-row">
                                    <label  style="font-weight: bold; color: black;" for="">CCCD:</label>
                                </div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="form-row">
                                    <label  style="font-weight: bold; color: black;" for="">Số điện thoại:</label>  {{$row->phone_id}}
                                </div>
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <label style="font-weight: bold; color: black;" for="">Địa chỉ thường trú:</label> {{@unserialize($row->address1)['address_full']}}
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <label style="font-weight: bold; color: black;" for="">Địa chỉ tạm trú:</label> {{@unserialize($row->address2)['address_full_usualy']}}
                            </div>
                            <br>
                            <div class="col-sm-6 text-left">
                                <div class="form-row">
                                    <label  style="font-weight: bold; color: black;" for="">Thu nhập trung bình/tháng:</label>@if($row->income !== null) {{$row->income}} Triệu VNĐ @endif
                                </div>
                            </div>
                            <br>
                            <div class="col-sm-12 text-left">
                                <?php $job = unserialize($row->job); ?>
                                @if(isset($job['check_job']) && $job['check_job'] === 'co')
                                <label for="" style="font-weight: bold; color: black;">Công việc : </label> Có
                                <ul>


                                </ul>
                                @else
                                <label for="" style="font-weight: bold; color: black;">Công việc : </label> Không
                                @endif
                            </div>
                            <div class="container">
                                <div class="row">
                                    <?php $gttt = unserialize($row->giay_to_ca_nhan); ?>
                                    <div class="col-sm-12 text-left">
                                        <ul class="gtcn">
                                            <li>
                                                @if(isset($gttt['img_gttt_cmnd']))
                                                <?php $img_cmnd = $gttt['img_gttt_cmnd']; ?>
                                               
                                                @foreach($img_cmnd as $key => $val)
                                                <div class="card" style="width: 18rem;">
                                                  <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                                </div>
                                                @endforeach
                                                @endif
                                            </li>
                                            <li>
                                                @if(isset($gttt['img_gttt_ho_khau']))
                                                <?php $img_hokhau = $gttt['img_gttt_ho_khau']; ?>
                                               
                                                @foreach($img_hokhau as $key => $val)
                                                    <div class="card" style="width: 18rem;">
                                                      <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                                    </div>
                                                @endforeach
                                                @endif
                                            </li>
                                        </ul> 
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                     <?php $gt_cmtn = unserialize($row->giay_to_cong_viec); ?>
                                     <div class="col-sm-12 text-left">
                                    <ul>
                                        @if(isset($gt_cmtn['hdld_xnct_img']))
                                        <?php $hdld_xnct_img = $gt_cmtn['hdld_xnct_img']; ?>
                                        @foreach($hdld_xnct_img as $key => $val)
                                            <div class="card" style="width: 18rem;">
                                                <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                            </div>
                                        @endforeach
                                        @endif
                                        @if(isset($gt_cmtn['gpkd_xnkd_img']))
                                        <?php $gpkd_xnkd_img = $gt_cmtn['gpkd_xnkd_img']; ?>
                                        @foreach($gpkd_xnkd_img as $key => $val)
                                            <div class="card" style="width: 18rem;">
                                                <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                            </div>
                                        @endforeach
                                        @endif
                                        @if(isset($gt_cmtn['bhyt_bhxh_img']))
                                        <?php $bhyt_bhxh_img = $gt_cmtn['bhyt_bhxh_img']; ?>
                                        @foreach($bhyt_bhxh_img as $key => $val)
                                            <div class="card" style="width: 18rem;">
                                                <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                            </div>
                                        @endforeach
                                        @endif
                                        @if(isset($gt_cmtn['hddn_img']))
                                        <?php $hddn_img = $gt_cmtn['hddn_img']; ?>
                                        @foreach($hddn_img as $key => $val)
                                            <div class="card" style="width: 18rem;">
                                                <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                            </div>
                                        @endforeach
                                        @endif
                                        @if(isset($gt_cmtn['skl_bl_img']))
                                        <?php $skl_bl_img = $gt_cmtn['skl_bl_img']; ?>
                                        @foreach($skl_bl_img as $key => $val)
                                            <div class="card" style="width: 18rem;">
                                                <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                            </div>
                                        @endforeach
                                        @endif
                                        @if(isset($gt_cmtn['cavet_xe_img']))
                                        <?php $cavet_xe_img = $gt_cmtn['cavet_xe_img']; ?>
                                        @foreach($cavet_xe_img as $key => $val)
                                            <div class="card" style="width: 18rem;">
                                                <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                            </div>
                                        @endforeach
                                        @endif
                                        @if(isset($gt_cmtn['bhnt_img']))
                                        <?php $bhnt_img = $gt_cmtn['bhnt_img']; ?>
                                        @foreach($bhnt_img as $key => $val)
                                            <div class="card" style="width: 18rem;">
                                                <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                            </div>
                                        @endforeach
                                        @endif
                                        
                                        @if(isset($gt_cmtn['gt_khac_img']))
                                        <?php $gt_khac_img = $gt_cmtn['gt_khac_img']; ?>
                                        @foreach($gt_khac_img as $key => $val)
                                            <div class="card" style="width: 18rem;">
                                                <img class="card-img-top" style="width: 50px;" src="/api/public/uploads/logs/{{$val}}" alt="Card image cap">
                                            </div>
                                        @endforeach
                                        @endif
                                    </ul>
                                    </div>  
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <?php $bank = unserialize($row->bank) ?>
                                    <div class="col-sm-12 text-left">
                                    <ul>
                                        @if(isset($bank['bank_name']) && $bank['bank_name'] !== null) <li>Tên ngân hàng : {{$bank['bank_name']}} @if($bank['bank_number'] !== null) Số tài khoản: {{$bank['bank_number']}} @endif @if($bank['bank_branch'] !== null) Chi nhánh: {{$bank['bank_branch']}} @endif</li> @endif
                                    </ul>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <?php $nguoi_hon_phoi = unserialize($row->nguoi_hon_phoi) ?>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 text-center  ">
                                        <label for="" style="font-weight: bold; color: black;">NGƯỜI HÔN PHỐI</label>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-3 text-left" >
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Họ tên: </label> <span>@if (isset($nguoi_hon_phoi['nguoi_hon_phoi_name'])){{$nguoi_hon_phoi['nguoi_hon_phoi_name']}} @endif</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">CMND/CCCD: </label> <span>@if (isset($nguoi_hon_phoi['nguoi_hon_phoi_name'])){{$nguoi_hon_phoi['nguoi_hon_phoi_cmnd']}} @endif</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Số điện thoại: </label> <span>@if (isset($nguoi_hon_phoi['nguoi_hon_phoi_name'])){{$nguoi_hon_phoi['nguoi_hon_phoi_sdt']}} @endif</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Con cái: </label> <span>@if (isset($nguoi_hon_phoi['nguoi_hon_phoi_name'])){{$nguoi_hon_phoi['so_nguoi_con']}} @endif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <?php $thong_tin_no = unserialize($row->thong_tin_no) ?> 
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <label for="" style="font-weight: bold; color: black;">THÔNG TIN NỢ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="container" id="">
                                <div class="row">
                                    <div class="col-sm-4 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Vay/Mua trả góp:</label> <span>{{$thong_tin_no['mua_vay_tra_gop']}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Số tiền phải trả hàng tháng:</label> <span>{{$thong_tin_no['tien_tra_hang_thang']}} VNĐ</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Số tiền nợ còn lại:</label> <span>{{$thong_tin_no['tien_no_con_lai']}} VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <label for="" style="font-weight: bold; color: black;">THÔNG TIN VAY</label>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-4 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Số tiền muốn vay:</label> <span>{{$row->khoanvay}} VNĐ</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Số điểm:</label> <span>{{$row->final_score}} điểm</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Khoản cho vay:</label> <span>{{$row->loan}} điểm</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Lãi suất:</label> <span>{{$row->interest_rate}} %/năm</span>
                                        </div> 
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Kỳ hạn:</label> <span>{{$row->duration}} tháng</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 text-left">
                                        <label for="" style="font-weight: bold; color: black;">Ghi chú:</label> <span> {{$row->user_notes}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endforeach
                </div>
            </div>
        </div>
        <div class="modal-footer text-center">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<style type="text/css" media="screen">
    .gtcn li
    {
        float: left;
        margin-left: 40px;
        list-style: none;
    }
</style>
