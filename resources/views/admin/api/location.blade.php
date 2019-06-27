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
    <div class="page-content browse container-fluid">
         @include('vendor.voyager.checkuser')
 
        <?php $u_id = Auth::user()->id;?>
        <?php $role_id = Auth::user()->role_id;?>
        <?php $group_id_u = Auth::user()->group_id; ?>
       

        <div class="panel panel-bordered">
            <div class="panel-body">
               	<div class="container">
               		<div class="col-sm-10" style="border: 1px solid #ef0909;padding: 25px;">
               			
               			{!! Form::open(['method'=>'get','class'=>'form-horizontal']) !!}
                         
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
                                
                               
    						    <div style="float: right;">
    						    	<button class="btn btn-danger btn_click">SUBMIT</button>	
    						    </div>
    				{!! Form::close()!!}
            </div>  		
		</div>        
      </div>
    </div>
</div>

@stop
@section('javascript')
<script type="text/javascript">
  $(document).ready(function() {
    // $('.btn_click').click(function(){
    //   var cmnd=$('#cmnd').val();
    //   var sap = $('#sap').val();
    //   if(cmnd.length < 7)
    //     {
    //       $('#errCmnd').text('Chứng minh nhân dân quá ngắn.').slideDown('slow').delay(2000).slideUp('slow');
    //       $('#cmnd').focus();
    //       return false;
    //     }
      
    // });
  });
</script>
@stop



