@extends('voyager::master')



@section('page_header')

@stop



@section('content')
    <div class="page-content browse container-fluid">
         @include('vendor.voyager.checkuser')
 
        <?php
            $u_id = Auth::user()->id;?>
        <?php $role_id = Auth::user()->role_id;?>
        <div class="row">

            <div class="col-sm-12 text-center" style="background-color: white; padding-bottom: 20px;">

                <div class="row">

                    <div class="col-sm-12 text-left">

                        <h3 style="font-weight: bold;">Quản lý tài khoản người vay</h3>

                    </div>

                </div>

                <form class="form-inline" action="{{url('admin/borrower')}}" method="POST">

                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"/>
                    <div class="container text-left" >
                        <div class="form-group row">
                            <label for="colFormLabelLg" style="font-weight: bold;">Nhập từ khóa tìm kiếm:</label>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name_nguoivay" value="" placeholder="Nhập từ khóa tìm kiếm...">
                        </div>
                        <button type="submit" class="btn btn-danger mb-2">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-bordered">

            <div class="panel-body">

                <div class="table-responsive">

                    <table id="dataTable" class="table table-hover dataTable_his">
                        <thead>

                            <tr>
                    
                                <th>STT</th>

                                <th> Name <a href=""><i class="voyager-angle-down pull-right"></i></a></th>

                                <th>Email1</th>
                                <th>Email2</th>
                                <th>Phone1</th>
                                <th>Phone2</th>
                                <th>Gender</th>
                                <th>Avata</th>
                                <th>Created_at</th>
                                <th>Action</th>


                            </tr>

                        </thead>

                        <tbody>
                           <?php $i = 1; ?>
                            @if($user_nguoivay->count() > 0)
                            @foreach($user_nguoivay as $key => $val)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$val->name}}</td>
                                <td>{{$val->email}}</td>
                                <td>{{$val->email_2}}</td>
                                <td>{{$val->phone}}</td>
                                <td>{{$val->phone_2}}</td>
                                <td>{{$val->gender}}</td>
                                <td>
                                @if(substr($val->avatar, 0, 4) !== 'http')
                                   <img style="width: 50px;" src="/API/public/uploads/{{$val->avatar}}" alt=""> 
                                @else
                                    <img src="{{$val->avatar}}" alt="">
                                @endif
                                </td>
                                <td>{{$val->created_at}}</td>
                                <td></td>
                            </tr>
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
                            </div>

                        </div>

                    </div>

                </div>

               

               

                    {{-- <div class="pull-left">

                        <div role="status" class="show-res" aria-live="polite">{{ trans_choice(

                            'voyager::generic.showing_entries', $dataTypeContent->total(), [

                                'from' => $dataTypeContent->firstItem(),

                                'to' => $dataTypeContent->lastItem(),

                                'all' => $dataTypeContent->total()

                            ]) }}</div>

                    </div>

                    <div class="pull-right">

                        {{ $dataTypeContent->appends([

                            's' => $search->value,

                            'filter' => $search->filter,

                            'key' => $search->key,

                            'order_by' => $orderBy,

                            'sort_order' => $sortOrder

                        ])->links() }}

                    </div> --}}

            </div>

        </div>

    </div>



   

@stop
@section('javascript')

@stop



