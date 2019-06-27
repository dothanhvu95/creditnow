@extends('voyager::master')

@section('page_title')

@section('page_header')
@stop

@section('content')
<div class="page-content browse container-fluid ">
    @include('vendor.voyager.checkuser')
    <?php $u_id = Auth::user()->id;?>
    <?php $role_id = Auth::user()->role_id;?>

    <h3 style="font-weight: bold;"><label class="my-1 mr-2" for="inputPassword2">Quản lý Referal</label></h3>
    <div style="background-color: white; padding: 20px;">
        <form class="form-inline">
          <div class="form-group mx-sm-3 mb-2">
            <label class="my-1 mr-2" for="inputPassword2">Nhập từ khóa tìm kiếm: </label>
            <input type="text" class="form-control" id="inputPassword2" placeholder="Từ khóa tìm kiếm">
          </div>
          <button type="submit" class="btn btn-primary mb-2"><i class="voyager-zoom-in"></i> Tìm kiếm</button>
        </form>
        <div class="text-right">
            <button type="button" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-sm btn-success view"><i class="voyager-plus"></i> Thêm mới</button>
            <button class="btn btn-sm btn-warning view"><i class="voyager-edit"></i> Sửa</button>
            <button class="btn btn-sm btn-danger view"><i class="voyager-trash"></i> Xóa</button>
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
                            <th>Slug</th>
                            <th>Url</th>
                            <th>Trạng thái</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                         @if($referal->count() > 0)
                            @if($role_id !== 1 && $role_id !== 4) {{-- kiểm tra trạng thái quyền người dùng --}}
                                @foreach($referal as $key => $val)
                                    @if($u_id === $val->user_id)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$val->name}}</td>
                                            <td>{{$val->slug}}</td>
                                            <td><a href="{{'https://creditnow.vn/?ref='.$val->slug}}" target="_blank">{{'https://creditnow.vn/?ref='.$val->slug}}</a></td>
                                            <td>@if($val->status == 1) {{'Đã Active'}} @else {{'Chưa Active'}} @endif</td>
                                            <td>
                                                {{-- <a href="#" title="View" class="btn btn-sm btn-warning view">
                                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Sửa</span>
                                                </a> --}}
                                                <a href="{{url('admin/delete-referals',$val->id)}}" class="btn btn-sm btn-danger view">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Xóa</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                @foreach($referal as $key => $val)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$val->name}}</td>
                                        <td>{{$val->slug}}</td>
                                        <td><a href="{{'https://creditnow.vn/?ref='.$val->slug}}" target="_blank">{{'https://creditnow.vn/?ref='.$val->slug}}</a></td>
                                        <td>@if($val->status == 1) {{'Đã Active'}} @else {{'Chưa Active'}} @endif</td>
                                        <td>
                                            {{-- <a href="#" title="View" class="btn btn-sm btn-warning view">
                                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Sửa</span>
                                            </a> --}}
                                            <a href="{{url('admin/delete-referals',$val->id)}}" class="btn btn-sm btn-danger view">
                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Xóa</span>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                            @endif
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
                            {{ $referal->links() }}
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
    <form action="{{url('admin/add-referals')}}" method="post" accept-charset="utf-8" id="from_reset">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Thêm mới Referal</h5>
          </div>
          <div class="modal-body">
            <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Tên referal :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control is-valid"  name="name" id="inputPassword" placeholder="nhập tên referal" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Slug :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="fullname" id="inputPassword" placeholder="vd: chovay, cho-vay" required>
                </div>
             </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" onclick="myFunction()" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </div>
    </form>
  </div>
</div>
@stop
@section('css')
<style type="text/css">
    .marr
    {
        padding-bottom: 25px;
    }
</style>
@stop
@section('javascript')
<script type="text/javascript">
   function myFunction() {
        document.getElementById("from_reset").reset();
    };
</script>
@stop
