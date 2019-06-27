@extends('voyager::master')
@section('page_header')
<style type="text/css">
	.alert .close {
    position: relative;
    right: -31px;
    top: -16px;
    padding: 13px;
    background: #3330 !important;
    border-radius: 2px;
}
</style>
@stop

@section('content')
    
    <div class="page-content browse container-fluid" style="position: relative">
         @include('vendor.voyager.checkuser')
 
        <?php $u_id = Auth::user()->id;?>
        <?php $role_id = Auth::user()->role_id;?>
        <?php $group_id_u = Auth::user()->group_id; ?>
       

        <div class="panel panel-bordered">
            <div class="panel-body">
               	<div class="container">
                    
               		<div class="col-sm-10" style="border: 1px solid #ef0909;padding: 25px;">
                       
               			<div class="col-sm-offset-4">
               				<h2>CHẤM ĐIỂM TÍN DỤNG</h2>		
               			</div>
               			{!! Form::open(['method'=>'post','class'=>'form-horizontal']) !!}
                         
    						    <div class="form-group ">
    						      <label class="col-sm-3 control-label" style="text-align: left;padding-left: 70px;">HỌ VÀ TÊN</label>
    						      <div class="col-sm-7">
    						        {!! Form::text('name',null,['class'=>"form-control ","placeholder"=>"Nhập Họ Tên "]) !!}
    						        @if($errors->has("name"))
    				        		<i style="color: red;">
    				        			{{$errors->first("name")}}
    				        		</i>
    				        		@endif
    						      </div>
    						    </div>
                                <div class="form-group ">
                                  <label class="col-sm-3 control-label" style="text-align: left;padding-left: 70px;">SỐ ĐIỆN THOẠI</label>
                                  <div class="col-sm-7">
                                    {!! Form::number('phone',null,['class'=>"form-control ","placeholder"=>"Nhập Số điện thoại "]) !!}
                                    @if($errors->has("phone"))
                                    <i style="color: red;">
                                        {{$errors->first("phone")}}
                                    </i>
                                    @endif
                                  </div>
                                </div>
    						    <div class="form-group ">
    						      <label class="col-sm-3 control-label" style="text-align: left;padding-left: 70px;">CHỨNG MINH NHÂN DÂN</label>
    						      <div class="col-sm-7">
    						        {!! Form::number('cmnd',null,['class'=>"form-control ","id"=>"cmnd","placeholder"=>"Nhập Chứng minh nhân dân " ]) !!}
    						        @if($errors->has("cmnd"))
    				        		<i id="errCmnd" style="color: red;">
    				        			{{$errors->first("cmnd")}}
    				        		</i>
    				        		@endif
    						      </div>
    						    </div>
    						    <div class="form-group ">
    						      <label class="col-sm-3 control-label" style="text-align: left;padding-left: 70px;">CĂN CƯỚC CÔNG DÂN</label>
    						      <div class="col-sm-7">
    						        {!! Form::number('cccd',null,['class'=>"form-control ","placeholder"=>"Nhập Căn cước công dân " ]) !!}
    						        @if($errors->has("cccd"))
    				        		<i style="color: red;">
    				        			{{$errors->first("cccd")}}
    				        		</i>
    				        		@endif
    						      </div>
    						    </div>
    						    <div style="float: right;">
    						    	<button class="btn btn-danger btn_click">Chấm Điểm</button>	
    						    </div>
                               
    					  	{!! Form::close()!!}
            </div>  		
				</div>
        @if(session('success'))
        <div class="container">
          <div class="col-sm-10">
            <div class="alert alert-primary alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong class="text-boil" style="color: black">{{session('success')}}</strong>
              <table class="table">
                @if(!empty($check))
                <tbody class="col">
                  <tr>
                    <td>Họ Tên: <span class="text-boil"> {{  $check->name }}  </span> </td>
                    <td>CMND/CCCD: <span class="text-boil"> {{  $check->cmnd }}  </span> </td>
                  </tr>
                  <tr>
                    <td>Điểm: <span class="text-boil"> {{  $check->score }} Điểm </span> </td>
                    <td>Khoản cho vay: <span class="text-boil">{{  $check->loan }}  Triệu VNĐ </span> </td>
                  </tr>
                   <tr>
                    <td>Lãi suất:<span class="text-boil">{{  $check->interest_rate }} %/Năm</span> </td>
                    <td>Kỳ hạn vay: <span class="text-boil">{{  $check->duration }} Tháng </span> </td>
                  </tr>
                </tbody>
                @endif
              </table>
            </div>
          </div>
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
                                <th> Họ Tên </th>
                                <th>CMND</th>
                                <th>CCCD</th>
                                <th>Số điện thoại</th>
                                <th>Điểm</th>
                                <th>Khoản cho vay</th>
                                <th>Lãi suất</th>
                                <th>Kỳ hạn vay</th>
                                <th>Ngày chấm điểm</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $i = 1; ?>
                            @if($list_score->count() > 0)
                            @foreach($list_score as $key => $val)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$val->name}}</td>
                                <td>{{$val->cmnd}}</td>
                                <td>{{$val->cccd}}</td>
                                <td>{{$val->phone}}</td>
                                <td>{{$val->score}}/850</td>
                                <td>{{App\MrData::toPrice($val->loan)}}</td>
                                <td>{{$val->interest_rate}} %/Năm</td>
                                <td>{{$val->duration}} Tháng</td>
                                <td>{{date('d-m-Y', strtotime($val->created_at)) }}</td>
                                <td>
                                    @if($val->status == 1)
                                    <span class="label label-success">Success</span>
                                    @else
                                    <span class="label label-danger">Duplicate data in system</span>
                                    @endif                                   
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="10" rowspan="" headers="" style="text-align:  center;">Không có dữ liệu trong bảng này</td>
                            </tr>
                            @endif
                        </tbody>

                    </table>
                    <nav style="float: right;">
                            {!!$list_score->render()!!}
                    </nav>

                </div>

               

               


            </div>

        </div>

    </div>
    <div id="loader" style="position: absolute; top:50%; left:50%;width: 1.7%;height: 4.8%; background: url('public/upload/loader.gif');background-repeat: no-repeat;"></div>

@stop
@section('javascript')
<script type="text/javascript">

    $(document).ready(function(){
        $('#loader').hide();

        $('form').submit(function() 
        {
            $('#loader').show();
        }) ;
    })
 
</script>
@stop



