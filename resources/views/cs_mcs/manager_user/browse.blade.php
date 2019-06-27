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
                    <h3 style="font-weight: bold;">Quản lý danh sách TCTD/NH</h3>
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
                          <th>Tên đầy đủ</th>
                          <th>Địa chỉ</th>
                          <th>Người liên lạc</th>
                          <th>SĐT</th>
                          <th>Tỉ lệ share(%)</th>
                          <th>Email</th>
                          <th>Tình trạng</th>
                          <th>Người phụ trách</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php $i = 1; ?>
                         @if($lender->count() > 0)
                          @foreach($lender as $key => $val)
                          <form action="{{route('update-tctd')}}" method="post" accept-charset="utf-8">
                              @csrf
                              <tr>
                                  <td>{{$i++}}</td>
                                  <input type="hidden" name="id" value="{{$val->id}}">
                                  <td>{{$val->name}}</td>
                                  <td>{{$val->description}}</td>
                                  <td><input type="text" class="form-control" name="address" value="{{$val->address}}" placeholder=""></td>
                                  <td><input type="text" class="form-control" name="suport" value="{{$val->support}}" placeholder=""></td>
                                  <td>{{$val->sdt}}</td>
                                  <td>@if($val->ratio_share !== null) {{$val->ratio_share.' %'}} @else {{'0 %'}} @endif</td>
                                  <td>{{$val->email}}</td>
                                  <td>
                                      <select class="form-control" id="status_search_history" name="status" style="min-width: 200px;">
                                          <option value="0">Chọn trạng thái</option>
                                          @foreach($lender_status as $key => $row)
                                              <option @if($val->status == $row->id) {{'selected'}} @endif value="{{$row->id}}">{{$row->name}}</option>
                                          @endforeach
                                      </select>
                                  </td>
                                  <td>
                                      <select class="form-control" id="status_search_history" name="cs_id" style="min-width: 200px;">
                                          <option value="0">Chọn người phụ trách</option>
                                          @foreach($user as $key => $row)
                                              <option @if($val->cs_id == $row->id) {{'selected'}} @endif value="{{$row->id}}">{{$row->name}}</option>
                                          @endforeach
                                      </select>
                                  </td>
                                  <td>
                                    @if($role_id != 5)
                                      <button type="submit" title="View" class="btn btn-sm btn-primary view">
                                          <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Lưu</span>
                                      </button>
                                    @endif
                                      <a href="{{url('admin/manager-partner-cs',$val->id)}}" class="btn btn-warning"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm"> Xem</span></a>
                                  </td>
                              </tr>
                          </form>
                          @endforeach
                          @else
                          <tr>
                              <td colspan="10" rowspan="" headers="" style="text-align:  center;">không có dữ liệu trong bảng này</td>
                          </tr>
                          @endif
                      </tbody>
                    </table>
                    <div class="container">
                        <div class="row ">
                            <div class="col-sm-12 text-center">
                                {{ $lender->onEachSide(10)->links() }}
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
    <form action="{{route('add-new-tctd')}}" method="post" accept-charset="utf-8" id="form_add_new">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Thêm mới tổ chức tín dụng</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Tên viết tắt :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control is-valid"  name="name" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <input type="hidden" class="form-control is-valid" value="123456"  name="pass" id="inputPassword" placeholder="">
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Tên đầy đủ :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="fullname" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Địa chỉ :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="addr" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">SĐT :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="phone" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Người liên lạc :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="people" id="inputPassword" placeholder="">
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Email :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="email" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Tỉ lệ share(%):</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="ratio_share" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Trạng thái :</label>
                <div class="col-sm-8">
                  <select class="form-control" id="status_search_history" name="status_add" style="min-width: 200px;">
                        <option value="0">Chọn trạng thái</option>
                        @foreach($lender_status as $key => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Trạng thái API:</label>
                <div class="col-sm-8">
                  <select class="form-control" id="status_search_history" name="api_add" style="min-width: 200px;">
                        <option value="0">Chưa kết nối</option>
                        <option value="1">Đã kết nối</option>
                    </select>
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
</script>
<style type="text/css" media="screen">
    .marr
    {
        padding-bottom: 25px;
    }
</style>
@stop



