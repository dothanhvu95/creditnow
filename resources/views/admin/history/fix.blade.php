@extends('voyager::master')



@section('page_header')
<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
background-color: #e5ece6;
    color: #353d47;
  font-weight: bold;
}
.dataTables_paginate{
    display: none;
}

</style>
@stop



@section('content')
    <div class="page-content browse container-fluid">
         @include('vendor.voyager.checkuser')
 
        <?php $u_id = Auth::user()->id;?>
        <?php $role_id = Auth::user()->role_id;?>
        <?php $group_id_u = Auth::user()->group_id; ?>
        <div class="row">

            <div class="col-sm-12 text-center" style="background-color: white; padding-bottom: 20px;">

                <div class="row">

                    <div class="col-sm-12 text-left">

                        <h3 style="font-weight: bold;">Hồ sơ vay</h3>

                    </div>

                </div>

                {!! Form::open(['method'=>'get']) !!}

                  
                    
                    <div class="col-sm-6 text-left form-inline" >
                        <div class="container">
                            <div class="col-sm-12 ">
                                @if(Auth::user())
                                <?php $role_id = Auth::user()->role_id;?>
                                @if($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7)
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                    <label for="colFormLabelLg" style="font-weight: bold;">Phân loại hồ sơ:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <label for="inputPassword6" > </label>
                                    <div class="form-group">

                                        {!! Form::select('type',$type,@$search['type'],['class'=>"form-control ",'style'=>'min-width: 200px;' ]) !!}
                                        
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Agencies:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <label for="inputPassword6" > </label>
                                    <div class="form-group">
                                        {!! Form::select('agence',$agence,@$search['agence'],['class'=>"form-control ",'style'=>'min-width: 200px;' ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Tổ chức tín dụng:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <label for="inputPassword6" > </label>
                                    <div class="form-group">
                                        {!! Form::select('tctd',$tctd,@$search['tctd'],['class'=>"form-control ",'style'=>'min-width: 200px;' ]) !!}
                                    </div>
                                </div>
                                @endif
                                @endif
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Trạng thái:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <label for="inputPassword6" > </label>
                                    <div class="form-group">
                                        {!! Form::select('status',$status,@$search['status'],['class'=>"form-control ",'style'=>'min-width: 200px;' ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                    <label for="colFormLabelLg" style="font-weight: bold;">Ngày tiếp nhận hồ sơ :</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px;">
                                    <div class="form-group">
                                        <label for="inputPassword6" >Từ: </label>
                                        <!-- <input type="date" name="from_date_tn" id="from_date_TN" value="" class="form-control mx-sm-3" aria-describedby="passwordHelpInline"> -->
                                        {{ Form::date('from_date_tn',(!empty($search['from_date_tn']))?$search['from_date_tn']: null,['class' => 'form-control',"aria-describedby" => "passwordHelpInline"]) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword6" >Đến: </label>
                                        {{ Form::date('to_date_tn',(!empty($search['to_date_tn']))?$search['to_date_tn']: null,['class' => 'form-control',"aria-describedby" => "passwordHelpInline"]) }}
                                        
                                    </div>
                                </div>
                               
                               

                               
                            </div>
                        </div>

                    </div>    
                   <div class="col-sm-6 text-left form-inline">
                       <div class="container">
                           <div class="col-sm-12">
                                <div class="col-sm-4" style="padding-bottom: 10px; margin-top: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Nhập từ khóa tìm kiếm:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <div class="form-group">
                                        {!! Form::text('key',@$search['key'],['class'=>"form-control ","placeholder"=>"Nhập từ khóa tìm kiếm( CMND, SĐT,Họ Tên )",'style'=>'min-width: 420px;margin-top: 10px;' ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                    <label for="colFormLabelLg" style="font-weight: bold;">Bộ lọc:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <div class="form-group">
                                        <!-- <input type="date" name="from_date_tn" id="from_date_TN" value="" class="form-control mx-sm-3" aria-describedby="passwordHelpInline"> -->
                                        {!! Form::select('point',$point,@$search['point'],['class'=>"form-control ",'style'=>'min-width: 200px;']) !!}
                                    </div>
                                    
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                    <label for="colFormLabelLg" style="font-weight: bold;">Show:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <div class="form-group">
                                        <!-- <input type="date" name="from_date_tn" id="from_date_TN" value="" class="form-control mx-sm-3" aria-describedby="passwordHelpInline"> -->
                                        {!! Form::select('show',$show,@$search['show'],['class'=>"form-control "]) !!}
                                    </div>
                                    
                                </div>
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                    <label for="colFormLabelLg" style="font-weight: bold;">Qlead:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px; padding-left: 35px;">
                                    <div class="form-group">
                                        <!-- <input type="date" name="from_date_tn" id="from_date_TN" value="" class="form-control mx-sm-3" aria-describedby="passwordHelpInline"> -->
                                        {!! Form::select('qlead',$qlead,@$search['qlead'],['class'=>"form-control "]) !!}
                                    </div>
                                    
                                </div>

                           </div>
                       </div>
                   </div>
                    <button type="submit" class="btn btn-danger mb-2" style="float: right;">Tìm kiếm</button>
                {!! Form::close()!!}



            </div>

        </div>

        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="table-responsive">
                   

                        <table id="customers" class="table-hover">
                            @if($role_id != 7)
                            @if($role_id == 8)

                            @else
                           
                                <button type="button" id="approve" class="btn btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg" style="float: right;margin-right: 10px;">Duyệt</button>
                                <button type="button" id="exportexcel" class="btn btn-success" data-toggle="modal" data-target="#exampleModalExport" style="float: right;margin-right: 10px;">Export Excel</button>
                                <button type="button" id="importexcel" class="btn btn-default" data-toggle="modal" data-target="#exampleModalImport" style="float: right;margin-right: 10px;">Import Excel</button>

                            </caption>

                            @endif
                            @endif
                            <nav style="float: right;">
                               {!!$history->appends($search)->render()!!}
                            </nav>
                            <thead>
                                <tr>
                                    <th width="20px"><input type="checkbox" class="checkall"></th>
                                    @if($role_id != 8)
                                    <th>STT</th>
                                    @endif
                                    @if($role_id != 7)
                                    <th>Người đăng ký</th>
                                    @endif
                                    <th>Điểm</th>
                                    <th>Khoản vay</th>
                                     <th>Phone</th>
                                    <th>CMND</th>
                                    <th>Địa chỉ</th>
                                    @if($role_id === 7)
                                    <th>Agencies</th>
                                    <th>Referal</th>
                                    <th>CS</th>
                                    <th>Họ tên</th>
                                    @endif
                                    @if($role_id == 8)
                                    <th>LeadId</th>
                                    @endif
                                    <!-- <th>Ngày tạo</th> -->
                                    @if($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6)
                                     {{-- <th>Tỷ lệ hoàn thành (%)</th>  --}}
                                    <th>Tuổi</th>
                                    <th>Agencies</th>
                                    @if($role_id != 8)
                                    <th>Ngày gửi HS</th>
                                    <th>Đang vay</th>
                                    <th>Điều kiện</th>
                                    @endif
                                    <th>Phân loại hồ sơ</th>
                                
                                    <th>Trạng thái</th>

                                    <th>Tác vụ</th>
                                    @elseif($role_id === 7)
                                    <th>Khoản muốn vay</th>
                                    <th>Trạng thái</th>
                                    <th>Tiền giải ngân thực</th>
                                    <th>Action</th>
                                    @else
                                    <th>Trạng thái</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($history as $key => $val)

                                    @if($role_id === 1 || $role_id === 4 || $role_id === 6)
                                    <tr>
                                        <td><input type="checkbox" name="multiname[]" value="{{$val['history_log_id']}}" class='check_el'></td>
                                        <td>{{$i++}}</td>
                                        <?php $date = date('d-m-Y', strtotime($val['created_at']))  ?> 
                                        <?php $date_1 = date('d-m-Y h:i A', strtotime($val['ngay_gui_ho_so']))  ?> 

                                        <td>
                                          @if($val['history_log_id'] !== null)
                                          <a href="{{url('admin/history/view-edit',[$val['history_log_id'],$val['cmnd']])}}" title="View">
                                              @if(!empty($val['name']))
                                                {{$val['name']}}
                                              @elseif($val['cid_customer_name'])
                                                {{$val['cid_customer_name']}}
                                              @endif
                                          </a>
                                          @else
                                            <a href="{{url('admin/history/view-edit',[$val['id'],$val['cmnd']])}}" title="View" >
                                              @if(!empty($val['name']))
                                                {{$val['name']}}
                                              @elseif($val['cid_customer_name'])
                                                {{$val['cid_customer_name']}}
                                              @endif
                                            </a>
                                          @endif
                                        </td>
                                        <td>
                                            <p style="font-weight: bold;font-size: 15px;color: red;">{{$val['final_score']}}</p>
                                        </td>
                                        <td>
                                            <p style="font-weight: bold;font-size: 15px;color: #4722da;">{{ $val['khoanvay'] }} VNĐ</p>
                                        </td>
                                        <td>{{ $val['phone'] }}</td>
                                        <td>{{ $val['cmnd'] }}</td>
                                        
                                        <td>
                                            <?php
                                                $city = App\Provincial::getCity();
                                                $addr = unserialize($val['address1']);
                                                $add = $addr['matp_address'];

                                            ?>
                                            
                                        
                                        </td>
                                        
                                        <td>
                                            <?php $year = date('Y', strtotime($val['age']));
                                              $age = date('Y')-$year; echo $age;
                                            ?>
                                            
                                        </td>
                                        <td>
                                            {{$val['agencies'] }}
                                        </td>
                                        <td>
                                            {{date('d-m-Y h:i A', strtotime($val['created_at']))}} 
                                        </td>
                                        <td>
                                           
                                            {{$val['debt']}}
                                        </td>
                                        <td>

                                            @if($val['qlead'] == 1)
                                            <span class="label label-warning">  QLead
                                            </span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($val['history_log_id'] == null)
                                                <span class="label label-danger">Hồ sơ chấm điểm</span>
                                            @elseif($val['history_log_id'] != null)
                                                <span class="label label-success">Hồ sơ vay</span>
                                            @endif
                                        </td>
                                        <td>
                                            
                                            <?php $lender_result = App\LenderResults::getLenderResult($val['history_log_id']);
                                                
                                            
                                            ?>
                                            @if($lender_result->count() > 0)
                                               
                                                    <label> 
                                                        <strong style="font-weight: bold;">{{$tctd[$lender_result['tctd_id']]}} :</strong>
                                                        <span class=" label label-danger">
                                                            
                                                        </span>
                                                   

                                                
                                            @else
                                                
                                                <span class="label label-primary">{{$val['status_name']}}</span>
                                                

                                            @endif
                                        
                                        </td>
                                        @if($val['history_log_id'] !== null)
                                        <td><a href="{{url('admin/history/view-edit',[$val['history_log_id'],$val['cmnd']])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i>
                                            </a>
                                            <a href="{{url('admin/trash',[$val['id']])}}" title="Trash" class="btn btn-sm btn-danger click_remove" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="voyager-trash"></i>
                                            </a>
                                        </td>
                                        @else
                                        <td>
                                            <a href="{{url('admin/history/view-edit',[$val['id'],$val['cmnd']])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i> 
                                            </a>
                                            <a href="{{url('admin/trash',[$val['id']])}}" title="Trash" class="btn btn-sm btn-danger click_remove" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="voyager-trash"></i>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                    
                                    @endif
                                @endforeach

                            </tbody>
                        </table>
                        <nav style="float: right;">
                           {!!$history->appends($search)->render()!!}
                        </nav>
                </div>
            </div>

        </div>

    </div>
    <div class="modal fade" id="exampleModalExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="form-inline" action="{{url('admin/mirae/export')}}" method="POST">
          @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title text-center" id="exampleModalLongTitle"></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                  <div class="container">
                    <input type="hidden" id="excelid" name="excelid" value="">
                    <div class="row">
                        <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                            <h4 style="    text-transform: uppercase;">Bạn có muốn xuất ra Excel.</h4>
                        </div>

                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary exportexcel ">Xuất</button>
          </div>
        </div>
        </form>
      </div>
    </div>
    <div class="modal fade" id="exampleModalImport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <form class="form-inline" action="{{url('admin/import/import-list')}}" method="POST" enctype="multipart/form-data"  files ="true">
          @csrf
            
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title text-center" id="exampleModalLongTitle"></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                  <div class="container">
                    <div class="row">
                        <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                            <h4 style="text-transform: uppercase;">Import Excel.</h4>
                        </div>
                         <table class="table table-hover table-responsive">
                          <tbody class="col">
                            <tr>
                              <td>
                                <span class="text-boil">Tổ chức TD:</span>
                              </td>
                              <td>
                                <?php 
                                    $tctd_name  = App\Lender::where('status',1)->get();
                                ?>
                                <select class="form-control" name="tctd" id="checktctd" >
                                    <option value=""> Chọn tổ chức TD.</option>
                                    @foreach($tctd_name as $k => $v) 
                                    <option value="{{$v['id']}}">{{$v['name']}}</option>
                                    @endforeach
                                </select>
                                <p style="color: red" id='errCredit'></p>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <span class="text-boil">File excel:</span>
                              </td>
                              <td>
                               
                               <input type="file" name="excel" id="import" accept=".csv,.xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" ID="fileSelect" runat="server">
                                <p style="color: red" id='errImport'></p>
                              </td>
                            </tr>
                            
                          </tbody>
                        </table>

                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary importexcel ">Import</button>
          </div>
        </div>
        </form>
      </div>
    </div>




   

@stop
@section('javascript')
<script type="text/javascript">
    @if(isset($status))
        $('#status_search_history option[value={{$status}}]').attr('selected','selected');      
    @endif 
    @if(isset($status_profile))
        $('#status_profile_search_history option[value={{$status_profile}}]').attr('selected','selected');    
    @endif

    $(document).ready(function() {
        $('select[name="group_u"]').on('change', function(){
        var group_id = $(this).val();
        if(group_id) {
            $.ajax({
                url: 'admin/user-in-group/'+group_id,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    $('select[name="state"]').empty();
                    $.each(data.result, function(key, val){
                        $('select[name="state"]').append('<option value="'+ val.id +'">' + val.name + '</option>');
                    });
                }
            });
        } else {
            $('select[name="state"]').empty();
             $('select[name="state"]').append('<option value="">--Chọn user--</option>');
        }

    });
        $(".checkall").change(function(){
            if($(this).prop("checked")){
                $(".check_el").prop("checked",true);
            }else{
                $(".check_el").prop("checked",false);
            }
        });
       //  $('#customers').dataTable({
       //  "searching": false, 
       //  "aLengthMenu": [[20, 50, 100], [20, 50, 100]],
       //  "iDisplayLength": 20
       //  // "paging": false
       // });
        /* check export excel */
        $('#exportexcel').click(function(event) {
            var checkall = $('[name="multiname[]"]:checkbox:checked').val();
            if(checkall === undefined ){
                alert(' Vui lòng chọn hồ sơ cần xuất excel.');
                return false;
            }
        });
        $('.exportexcel').click(function(event) {
            /* Act on the event */
            var val = [];
            $('.check_el:checked').each(function(i){
              val[i] = $(this).val();
            });
            var checkall = $('[name="multiname[]"]:checkbox:checked').val();       
            var tctd_name = $("input[name='to_chuc_tin_dung']:checked").parent('label').text();
            
              $('#excelid').val(val);
              document.getElementById("excelid").innerHTML = val ; 
              $('#exampleModalExport').modal('hide');
            
          });

        /* check approved status*/
        $('#approve').click(function(event) {
            var checkall = $('[name="multiname[]"]:checkbox:checked').val();
            if(checkall === undefined ){
                alert(' Vui lòng chọn hồ sơ cần duyệt.');
                return false;
            }
        });
        $('#approve').click(function(event) {
            /* Act on the event */
            var val = [];
            $('.check_el:checked').each(function(i){
              val[i] = $(this).val();
            });
            var checkall = $('[name="multiname[]"]:checkbox:checked').val();       
            var tctd_name = $("input[name='to_chuc_tin_dung']:checked").parent('label').text();
           
              $('#approve_id').val(val);
              document.getElementById("log_id").innerHTML = val ; 
            
        });
        $('.importexcel').click(function(){
            var name = $('#import').val();
            var checktctd = $('#checktctd').val();
            if(checktctd == '')
            {
                $('#errCredit').text('Vui lòng chọn tổ chức tín dụng.').slideDown('slow').delay(2000).slideUp('slow');
                $('#checktctd').focus();
                return false;
            }
            if(name == '')
            {
                $('#errImport').text('Vui lòng chọn file excel.').slideDown('slow').delay(2000).slideUp('slow');
                $('#import').focus();
                return false;
            }
            
        });

    });
</script>
@stop



