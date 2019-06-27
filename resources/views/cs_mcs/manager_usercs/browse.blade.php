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
                    <h3 style="font-weight: bold;">Quản lý user Chăm sóc khách hàng</h3>
                </div>
                <div class="text-right">
                    <button class="btn btn-success btn-add-new" data-toggle="modal" data-target="#exampleModalCenter">
                        <i class="voyager-plus"></i> <span>Thêm mới</span>
                    </button>
                </div>
            </div> 
        </div>
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover">
                      <thead>
                        <tr>
                          <th>STT</th>
                          <th><input type="checkbox" id="checkall">Check all</th>
                          <th>Tên nhân viên</th>
                          <th>Email</th>
                          <th>Avatar</th>
                          <th>SĐT</th>
                          <th>Group</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; ?>
                        @foreach($user as $key => $val)
                        <tr>
                          <td>{{$i++}}</td>
                          <th><input type="checkbox" name="name[]" value="{{$val->id}}"></th>
                          <td>{{$val->name}}</td>
                          <td>{{$val->email}}</td>
                          <td> <img style="width: 50px;"  src="/API/public/uploads/{{$val->avatar}}" alt=""> </td>
                          <td>{{$val->phone}}</td>
                          <td>{{$val->group_name}}</td>
                          <td></td>
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
            <h5 class="modal-title" id="exampleModalLongTitle">Thêm mới tài khoản chăm sóc khách hàng</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Tên nhân viên :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control is-valid"  name="name" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <input type="hidden" class="form-control is-valid" value="123456"  name="pass" id="inputPassword" placeholder="">
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">email :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="email" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Ảnh :</label>
                <div class="col-sm-8">
                  <input type="file" class="form-control" name="avatar" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">SĐT :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="phone" id="inputPassword" placeholder="" required>
                </div>
             </div>
             <div class="form-group marr">
                <label for="inputPassword" class="col-sm-4 col-form-label">Group :</label>
                <div class="col-sm-8">
                  <select class="form-control" id="status_search_history" name="group" style="min-width: 200px;">
                        <option value="0">Chọn nhóm</option>
                        @foreach($group as $key => $row)
                            <option value="{{$row->id}}">{{$row->title}}</option>
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



