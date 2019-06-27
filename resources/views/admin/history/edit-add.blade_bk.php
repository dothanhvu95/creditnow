@extends('voyager::master')



@section('page_title')



@section('page_header')

@stop



@section('content')

<div class="page-content browse container-fluid">

    <div class="container">

        <div class="row">

        @foreach($history_log as $key => $row)

           <section class="banner-area relative " style="margin-top: 0; margin-bottom: 0px; color: black;">

                <div class="col-sm-12" style="background: white; margin-top: 0px; padding-bottom: 20px;">

                  <br>

                    <div class="text-center">

                        <h2><div id="">HỒ SƠ VAY</div> </h2>

                    </div>

                    <div class="text-center" id="thongbaochuadangnhap"></div>

                    <hr>

                    <div class="col-sm-6 text-left">

                        <div class="form-row">

                            <label  style="font-weight: bold; color: black;" for="">Họ tên: </label> {{$row->name}}

                        </div>

                    </div>

                    <div class="col-sm-6 text-left">

                        <div class="form-row">

                            <label  style="font-weight: bold; color: black;" for="">Ngày sinh:</label> {{$row->age}}

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

                            <label  style="font-weight: bold; color: black;" for="">Thu nhập trung bình/tháng:</label> {{$row->income}} Triệu VNĐ

                        </div>

                    </div>

                    <br>

                    <div class="col-sm-12 text-left">

                        <?php $job = unserialize($row->job); ?>

                        @if($job['congviec'] === 'co')

                        <label for="" style="font-weight: bold; color: black;">Công việc : </label> Có

                        <ul>

                           @if($job['name_work_1'] !=='') <li>Kinh doanh: {{$job['name_work_1']}}</li> @endif

                            @if($job['name_work_2'] !=='') <li>Đi làm hưởng lương: {{$job['name_work_2']}}</li> @endif

                        </ul>

                        @else

                        <label for="" style="font-weight: bold; color: black;">Công việc : </label> Không

                        @endif

                    </div>

                    <div class="container">

                        <div class="row">

                            <?php $gttt = unserialize($row->giay_to_ca_nhan); ?>

                            <div class="col-sm-12 text-left">

                                <label for="" style="font-weight: bold; color: black;">Giấy tờ tùy thân: </label>@if($gttt['giay_to_ca_nhan_cmnd'] !== '') {{$gttt['giay_to_ca_nhan_cmnd'] }} ,@endif @if($gttt['giay_to_ca_nhan_ho_khau'] !== '') {{'Hộ khẩu'}} @endif 

                            </div>

                        </div>

                    </div>

                    <div class="container">

                        <div class="row">

                             <?php $gt_cmtn = unserialize($row->giay_to_cong_viec); ?>

                             @if($gt_cmtn['gt_cv_tn'] === 'co')

                            <div class="col-sm-12 text-left">

                                <label for="" style="font-weight: bold; color: black;">Giấy tờ chứng minh thu nhập/công việc : </label>Có

                            </div>

                            @else

                            <label for="" style="font-weight: bold; color: black;">Giấy tờ chứng minh thu nhập/công việc : </label>Không

                            @endif

                        </div>

                    </div>



                    <div class="container">

                        <div class="row">

                            <div class="col-sm-12 text-left">

                                <label for="" style="font-weight: bold; color: black;">Tài khoản ngân hàng : </label><span id="pre_bank_bank_isset"></span>

                                <div class="collapse bankrv" id="collapseExample3_1" style="margin-top: 10px; display: flex;">

                                    <div class="card card-body">

                                        <div class="form-row">

                                            <div class="col-sm-6 text-left pre_bank_name_1_rm">

                                                <div class="input-group">

                                                    <div class="input-group-prepend">

                                                        <span class="icon"><i class="fas fa-university"></i></span>

                                                    </div>

                                                    <div id="pre_bank_name_1"></div>

                                                </div>

                                            </div>

                                            <div class="col-sm-6 text-left pre_bank_number_1_rm">

                                                <div class="input-group">

                                                    <div class="input-group-prepend">

                                                        <span class="icon"><i class="far fa-credit-card"></i></span>

                                                    </div>

                                                    <div id="pre_bank_number_1"></div>

                                                </div>

                                            </div>

                                            <div class="col-sm-6 text-left pre_bank_name_2_rm ">

                                                <div class="input-group">

                                                    <div class="input-group-prepend">

                                                        <span class="icon"><i class="fas fa-university"></i></span>

                                                    </div>

                                                    <div id="pre_bank_name_2"></div>

                                                </div>

                                            </div>

                                            <div class="col-sm-6 text-left pre_bank_number_2_rm">

                                                <div class="input-group">

                                                    <div class="input-group-prepend">

                                                        <span class="icon"><i class="far fa-credit-card"></i></span>

                                                    </div>

                                                    <div id="pre_bank_number_2"></div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <br>

                    <div class="container">

                        <div class="row">

                            <div class="col-sm-12 text-center  ">

                                <label for="" style="font-weight: bold; color: black;">NGƯỜI HÔN PHỐI</label>

                            </div>

                            <div class="col-sm-12 text-left">

                                <div id="nguoihonphoi"></div>

                            </div>

                        </div>

                    </div>

                    <div class="container" id="nguoihonphoi_thongtin">

                        <div class="row">

                            <div class="col-sm-3 text-left" >

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Họ tên:</label>

                                </div>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="icon" ><i class='fas fa-user'></i></span>

                                    </div>

                                   <div id="pre_nguoi_hon_phoi_name"></div>

                                </div>

                            </div>



                            <div class="col-sm-3 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">CMND/CCCD:</label>

                                </div>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="icon"><i class="fas fa-id-card"></i></span>

                                    </div>

                                    <div id="pre_nguoi_hon_phoi_cmnd"></div>

                                </div>

                            </div>



                            <div class="col-sm-3 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Số điện thoại:</label>

                                </div>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="icon"><i class="fas fa-phone"></i></span>

                                    </div>

                                    <div id="pre_nguoi_hon_phoi_sdt"></div>

                                </div>

                            </div>



                            <div class="col-sm-3 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Con cái:</label>

                                </div>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="icon"><i class="fas fa-child"></i></span>

                                    </div>

                                    <div id="pre_so_nguoi_con"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <br>

                    <hr>

                    <div class="container">

                        <div class="row">

                            <div class="col-sm-12 text-center">

                                <label for="" style="font-weight: bold; color: black;">Thông tin nợ :</label><span id="no_thongtin"></span>

                            </div>

                        </div>

                    </div>

                    <div class="container" id="no_thongtin_1">

                        <div class="row">

                            <div class="col-sm-4 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Vay/Mua trả góp:</label>

                                </div>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="icon"><i class="fas fa-shopping-cart"></i></span>

                                    </div>

                                    <div id="pre_mua_vay_tra_gop"></div>

                                </div>

                            </div>



                            <div class="col-sm-4 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Số tiền phải trả hàng tháng:</label>

                                </div>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>

                                    </div>

                                    <div id="pre_tien_tra_hang_thang"></div>

                                </div>

                            </div>



                            <div class="col-sm-4 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Số tiền nợ còn lại:</label>

                                </div>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="icon"><i class="fas fa-money-bill"></i></span>

                                    </div>



                                    <div id="pre_tien_no_con_lai"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <br>



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

                                    <label style="font-weight: bold;" for="">Số tiền vay:</label>

                                </div>

                                <div class="input-group">

                                    <div id="sotienvay_1"></div>

                                </div>

                            </div>

                            <div class="col-sm-3 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Số điểm:</label>

                                </div>

                                <div class="input-group">

                                    <div id="sodiem_1"></div>

                                </div>

                            </div>



                            <div class="col-sm-3 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Lãi suất:</label>

                                </div>

                                <div class="input-group">

                                    <div id="pre_laisuat"></div>

                                </div>

                            </div>



                            <div class="col-sm-3 text-left">

                                <div class="form-row">

                                    <label style="font-weight: bold;" for="">Kỳ hạn:</label>

                                </div>

                                <div class="input-group">

                                    <div id="pre_kyhan"></div>

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

    <!-- End Delete File Modal -->

@stop

