<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="page-content browse container-fluid">
            <div class="container">
                <div class="row">
                    <?php $url_img = 'https://demo.creditscore.vn/API/public/uploads/logs/'; ?> 
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
                                    <label  style="font-weight: bold; color: black;" for="">Ngày sinh:</label>{{$row->age}}
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
                                    <label  style="font-weight: bold; color: black;" for="">CCCD:</label> {{$row->cmnd}}
                                </div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="form-row">
                                    <label  style="font-weight: bold; color: black;" for="">Số điện thoại:</label>  {{$row->phone_id}}
                                </div>
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <label style="font-weight: bold; color: black;" for="">Địa chỉ thường trú:</label> {{unserialize($row->address1)['address_full']}}
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <label style="font-weight: bold; color: black;" for="">Địa chỉ tạm trú:</label> {{unserialize($row->address2)['address_full_usualy']}}
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
                                @if($job['congviec'] === 'co')
                                <label for="" style="font-weight: bold; color: black;">Công việc : </label> Có
                                <ul>
                                    @if($job['name_work_1'] !=='') <li>Kinh doanh: {{$job['name_work_1']}}</li> @endif
                                     <?php $img_work = count($job['img_work_end_hidden']) ?> 
                                    @for( $i = 0 ; $i < $img_work ; $i++)
                                    <div class="card" style="width: 18rem;">
                                      <img class="card-img-top" src="https://demo.creditscore.vn/public/API/uploads/logs/{{$job['img_work_end_hidden'][$i]}}" alt="Card image cap">
                                    </div>
                                    @endfor
                                    @if($job['name_work_2'] !=='') <li>Đi làm hưởng lương: {{$job['name_work_2']}}</li> @endif
                                    <?php $img_work_1 = count($job['img_work_end_hidden_1']) ?> 
                                    @for( $j = 0 ; $j < $img_work_1 ; $j++)
                                    <div class="card" style="width: 18rem;">
                                      <img class="card-img-top" src="https://demo.creditscore.vn/public/API/uploads/logs/{{$job['img_work_end_hidden_1'][$j]}}" alt="Card image cap">
                                    </div>
                                    @endfor
                                </ul>
                                @else
                                <label for="" style="font-weight: bold; color: black;">Công việc : </label> Không
                                @endif
                            </div>
                            <div class="container">
                                <div class="row">
                                    <?php $gttt = unserialize($row->giay_to_ca_nhan); ?>
                                    <div class="col-sm-12 text-left">
                                        <label for="" style="font-weight: bold; color: black;">Giấy tờ tùy thân: </label>@if($gttt['giay_to_ca_nhan_cmnd'] !== '' && $gttt['giay_to_ca_nhan_cmnd'] !== Null) {{ 'CMND ,' }} @endif @if($gttt['giay_to_ca_nhan_ho_khau'] !== '' && $gttt['giay_to_ca_nhan_ho_khau'] !== null) {{'Hộ khẩu'}} @endif
                                     @if(isset($gttt['img_gttt_cmnd']))     
                                    <?php $img_cmnd = count($gttt['img_gttt_cmnd']) ?>
                                    @for( $u = 0 ; $u < $img_cmnd ; $u++)
                                    <div class="card" style="width: 18rem;">
                                      <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$img_cmnd['img_gttt_cmnd'][$u]}}" alt="Card image cap">
                                    </div>
                                    @endfor
                                    @endif
                                    @if(isset($gttt['img_gttt_hokhau'])) 
                                     <?php $img_hokhau = count($gttt['img_gttt_hokhau']) ?>
                                     @for( $k = 0 ; $k < $img_hokhau ; $k++)
                                        <div class="card" style="width: 18rem;">
                                          <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$img_hokhau['img_gttt_hokhau'][$k]}}" alt="Card image cap">
                                        </div>
                                        @endfor 
                                    @endif
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                     <?php $gt_cmtn = unserialize($row->giay_to_cong_viec); ?>
                                     <div class="col-sm-12 text-left">
                                     @if($gt_cmtn['gt_cv_tn'] === 'co')
                                        <label for="" style="font-weight: bold; color: black;">Giấy tờ chứng minh thu nhập/công việc : </label>Có
                                        <ul>
                                            @if($gt_cmtn['hdld_xnct_name'] !== null)
                                            <li>{{$gt_cmtn['hdld_xnct_name']}}</li>
                                            @endif
                                            @if(isset($gt_cmtn['hdld_xnct_img']) && $gt_cmtn['hdld_xnct_img'] != null)
                                            <?php $hdld_xnct_img = count($gttt['hdld_xnct_img']) ?>
                                                @for( $k = 0 ; $k < $hdld_xnct_img ; $k++)
                                                    <div class="card" style="width: 18rem;">
                                                      <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$hdld_xnct_img['hdld_xnct_img'][$k]}}" alt="Card image cap">
                                                    </div>
                                                @endfor 
                                            @endif
                                            @if($gt_cmtn['gpkd_xnkd_name'] !== null)
                                            <li>{{$gt_cmtn['gpkd_xnkd_name']}}</li>
                                            @endif
                                            @if(isset($gt_cmtn['gpkd_xnkd_img']) && $gt_cmtn['gpkd_xnkd_img'] != null)
                                            <?php $gpkd_xnkd_img = count($gttt['gpkd_xnkd_img']) ?>
                                                @for( $k = 0 ; $k < $gpkd_xnkd_img ; $k++)
                                                    <div class="card" style="width: 18rem;">
                                                      <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$gpkd_xnkd_img['gpkd_xnkd_img'][$k]}}" alt="Card image cap">
                                                    </div>
                                                @endfor 
                                            @endif
                                            @if($gt_cmtn['bhyt_bhxh_name'] !== null)
                                            <li>{{$gt_cmtn['bhyt_bhxh_name']}}</li>
                                            @endif
                                            @if(isset($gt_cmtn['bhyt_bhxh_img']) && $gt_cmtn['bhyt_bhxh_img'] != null)
                                            <?php $bhyt_bhxh_img = count($gttt['bhyt_bhxh_img']) ?>
                                                @for( $k = 0 ; $k < $bhyt_bhxh_img ; $k++)
                                                    <div class="card" style="width: 18rem;">
                                                      <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$bhyt_bhxh_img['bhyt_bhxh_img'][$k]}}" alt="Card image cap">
                                                    </div>
                                                @endfor 
                                            @endif
                                            @if($gt_cmtn['hddn_name'] !== null)
                                            <li>{{$gt_cmtn['hddn_name']}}</li>
                                            @endif
                                            @if(isset($gt_cmtn['hddn_img']) && $gt_cmtn['hddn_img'] != null)
                                            <?php $hddn_img = count($gttt['hddn_img']) ?>
                                                @for( $k = 0 ; $k < $hddn_img ; $k++)
                                                    <div class="card" style="width: 18rem;">
                                                      <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$hddn_img['hddn_img'][$k]}}" alt="Card image cap">
                                                    </div>
                                                @endfor 
                                            @endif
                                            @if($gt_cmtn['cavet_xe_name'] !== null)
                                            <li>{{$gt_cmtn['cavet_xe_name']}}</li>
                                            @endif
                                            @if(isset($gt_cmtn['cavet_xe_img']) && $gt_cmtn['cavet_xe_img'] != null)
                                            <?php $cavet_xe_img = count($gttt['cavet_xe_img']) ?>
                                                @for( $k = 0 ; $k < $cavet_xe_img ; $k++)
                                                    <div class="card" style="width: 18rem;">
                                                      <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$cavet_xe_img['cavet_xe_img'][$k]}}" alt="Card image cap">
                                                    </div>
                                                @endfor 
                                            @endif
                                            @if($gt_cmtn['bhnt_name'] !== null)
                                            <li>{{$gt_cmtn['bhnt_name']}}</li>
                                            @endif
                                            @if(isset($gt_cmtn['bhnt_img']) && $gt_cmtn['bhnt_img'] != null)
                                            <?php $bhnt_img = count($gttt['bhnt_img']) ?>
                                                @for( $k = 0 ; $k < $bhnt_img ; $k++)
                                                    <div class="card" style="width: 18rem;">
                                                      <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$bhnt_img['bhnt_img'][$k]}}" alt="Card image cap">
                                                    </div>
                                                @endfor 
                                            @endif
                                            @if($gt_cmtn['gt_khac_name'] !== null)
                                            <li>{{$gt_cmtn['gt_khac_name']}}</li>
                                            @endif
                                            @if(isset($gt_cmtn['gt_khac_img']) && $gt_cmtn['gt_khac_img'] != null)
                                            <?php $gt_khac_img = count($gttt['gt_khac_img']) ?>
                                                @for( $k = 0 ; $k < $gt_khac_img ; $k++)
                                                    <div class="card" style="width: 18rem;">
                                                      <img class="card-img-top" src="https://demo.creditscore.vn/API/public/uploads/logs/{{$gt_khac_img['gt_khac_img'][$k]}}" alt="Card image cap">
                                                    </div>
                                                @endfor 
                                            @endif
                                        </ul>
                                    @else
                                        <label for="" style="font-weight: bold; color: black;">Giấy tờ chứng minh thu nhập/công việc : </label>Không
                                    @endif
                                    </div>  
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <?php $bank = unserialize($row->bank) ?>
                                    <div class="col-sm-12 text-left">
                                        <label for="" style="font-weight: bold; color: black;">Tài khoản ngân hàng : </label><span >{{ $bank['bank_isset'] }}</span>
                                    <ul>
                                        @if($bank['bank_name_1'] !== null) <li>Tên ngân hàng 1: {{$bank['bank_name_1']}} @if($bank['bank_number_1'] !== null) Số tài khoản 1: {{$bank['bank_number_1']}} @endif</li> @endif
                                        @if($bank['bank_name_2'] !== null) <li>Tên ngân hàng 2: {{$bank['bank_name_2']}} @if($bank['bank_number_2'] !== null) Số tài khoản 1: {{$bank['bank_number_2']}} @endif</li> @endif
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
                                            <label style="font-weight: bold;" for="">Họ tên: </label> <span>{{$nguoi_hon_phoi['nguoi_hon_phoi_name']}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">CMND/CCCD: </label> <span>{{$nguoi_hon_phoi['nguoi_hon_phoi_cmnd']}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Số điện thoại: </label> <span>{{$nguoi_hon_phoi['nguoi_hon_phoi_sdt']}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Con cái: </label> <span>{{$nguoi_hon_phoi['so_nguoi_con']}}</span>
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
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Số tiền vay:</label> <span>{{$row->khoanvay}} VNĐ</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Số điểm:</label> <span>{{$row->cib_point}} điểm</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Lãi suất:</label> <span>{{$row->interest_rate}} %/năm</span>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3 text-left">
                                        <div class="form-row">
                                            <label style="font-weight: bold;" for="">Kỳ hạn:</label> <span>{{$row->duration}} tháng</span>
                                        </div>
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
