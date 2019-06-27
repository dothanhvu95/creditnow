<div class="container-fluid" style="margin-top: 60px;">
  <?php 
    $tong_nh = 0;
    $tong_kh = 0;
    $tong_agency = 0;
     foreach ($count_kh as $key => $val) {
          $tong_nh = $tong_nh + $val->tong_lender ;
          $tong_kh = $tong_kh + $val->tong_khachhang ;
          $tong_agency = $tong_agency + $val->tong_agency ;
     }
  ?>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-6 col-lg-3" >
        <div class="card label-success">
          <div class="card-body pb-0">
            <button class="btn btn-transparent p-0 float-right" type="button">
              <i class="icon-location-pin"></i>
            </button>
            <div class="text-value font-bold">SỐ LƯỢNG KHÁCH HÀNG PHỤ TRÁCH</div>
            <div class="font-txtx">{{$tong_kh}} Đơn vị</div>
          </div>
        </div>
      </div>
      <!-- /.col-->
      <div class="col-sm-6 col-lg-3">
        <div class="card label-info">
          <div class="card-body pb-0">
            <button class="btn btn-transparent p-0 float-right" type="button">
              <i class="icon-location-pin"></i>
            </button>
            <div class="text-value font-bold">NGÂN HÀNG/TCTD PHỤ TRÁCH</div>
            <div class="font-txtx">{{$tong_nh}} Đơn vị</div>
          </div>
        </div>
      </div>
      <!-- /.col-->
      <div class="col-sm-6 col-lg-3">
        <div class="card label-warning">
          <div class="card-body pb-0">
            <div class="btn-group float-right">
              <button class="btn btn-transparent dropdown-toggle p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-settings"></i>
              </button>
            </div>
            <div class="text-value font-bold">AGENCIES PHỤ TRÁCH</div>
            <div class="font-txtx">{{$tong_agency}} Đơn vị</div>
          </div>
        </div>
      </div>
      <!-- /.col-->
      <div class="col-sm-6 col-lg-3">
        <div class="card label-danger">
          <div class="card-body pb-0">
            <div class="btn-group float-right">
              <button class="btn btn-transparent dropdown-toggle p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-settings"></i>
              </button>
            </div>
            <div class="text-value font-bold">ĐÁNH GIÁ TEAM CS</div>
            <div class="font-txtx">Tốt</div>
          </div>
        </div>
      </div>
      <!-- /.col-->
    </div>
  </div>
</div>
@if($role_id == 6)
<div class="container">
  <div class="animated fadeIn">
    <div class="row">
      <div class="table-responsive">
        <table id="dataTable" class="table table-hover">
          <caption class="text-center" style="color: #22a7f0;"> &#9679;&nbsp;Bảng thống kê của từng nhân viên trong nhóm &nbsp;&#9679;</caption>
            <thead>
                <tr style="font-weight: bold;">
                    <th>STT</th>
                    <th>Team</th>
                    <th>Tên NV-CS</th>
                    <th>Số KH <a href=""><i class="voyager-angle-down pull-right"></i></a></th>
                    <th>Số TCTD</th>
                    <th>Số Agencies</th>
                </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              @foreach($count_kh as $key => $val)
              <tr>
                <td>{{$i++}}</td>
                <td>{{$val->title}}</td>
                <td>{{$val->u_name}}</td>
                <td>{{$val->tong_khachhang}}</td>
                <td>{{$val->tong_lender}}</td>
                <td>{{$val->tong_agency}}</td>
              </tr>
              @endforeach
               <tr style=" background-color: #3366cc; color: white; font-weight: bold;">
                  <td colspan="3" rowspan="" headers="">Tổng: </td>
                  <td colspan="" rowspan="" headers="">{{$tong_kh.' Khách hàng'}}</td>
                  <td colspan="" rowspan="" headers="">{{$tong_nh.' TCTD'}}</td>
                  <td colspan="" rowspan="" headers="">{{$tong_agency.' Agency'}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
  </div>
</div>
@endif



<!-- style in page -->
<style type="text/css" media="screen">
  .font-bold
  {
    color: white;
    font-weight: bold;
  }
  .font-txtx
  {
    color: white;
    font-size: 30px;
  }
</style>