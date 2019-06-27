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

</style>
@stop
@section('content')
<?php
    $u_id = Auth::user()->id;
?>
<?php $role_id = Auth::user()->role_id;?>
@if($role_id === 2 || $role_id === 5)
    @include('vendor.voyager.checkpemission')
@else
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="row">
                    <div class="col-sm-12 text-left">
                        <h3 style="font-weight: bold;">Danh sách số điện thoại S-wifi</h3>
                    </div>
                </div>
                <?php 
                    $role_id = Auth::user()->role_id;
                    $user_id = Auth::user()->id; 
                ?>
				

            </div>
        </div>
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="customers" class="table table-hover">
                        <caption style="color: #22a7f0; margin-bottom: 30px;">&#9679;&nbsp;Tổng số điện thoại: <span class="label label-danger" style="font-weight: bold;">{{$support->total()}}</span> || &#9679;&nbsp;Tổng gửi lần 1:&nbsp;<span class="label label-success" style="font-weight: bold;"> {{$one->total()}}</span> 
                        || &#9679;&nbsp;Tổng gửi lần 2:&nbsp;<span class="label label-info" style="font-weight: bold;">{{$two->total()}}</span> 
                        || &#9679;&nbsp;Tổng gửi lần 3:&nbsp;<span class="label label-primary" style="font-weight: bold;">{{$three->total()}}</span> 
                        || &#9679;&nbsp;Tổng gửi lần 4:&nbsp;<span class="label label-default" style="font-weight: bold;">{{$four->total()}}</span>
                        || &#9679;&nbsp;Tổng HS chấm điểm:&nbsp;<span class="label label-warning" style="font-weight: bold;">{{$totalhscd->total()}}</span>
                        || &#9679;&nbsp;Tổng HS vay:&nbsp;<span class="label label-warning" style="font-weight: bold;">{{$totalhsv->total()}}</span>
                        @if($role_id == 8)
                        <button type="button" id="importexcel" class="btn btn-success" data-toggle="modal" data-target="#exampleModalImport" style="float:right;margin-right:10px;margin-top: -8px;">Import Excel</button>
                        @endif
                        </caption>
                        <thead>
                            <tr style="font-weight: bold;">
                                <th>STT</th>
                                <th>Agencies</th>
                                <th>Số điện thoại</th>
                                <th>Trạng thái</th>
                                <th>Lượt gửi SMS</th>
                                <th>Hồ sơ</th>
                                <th>Ngày tạo</th>
                            </tr>

                        </thead>

                        <tbody >

                            <?php $i = 1; ?>
							@foreach($support as $key => $value)
                            <tr>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">{{$i++}}</span></td>
                                <td colspan="" rowspan="" headers=""><span class="label label-info" style="font-weight: bold;">S-Wifi</span></td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">{{$value['phone']}}</span></td>
                                <td colspan="" rowspan="" headers="">
                                    @if($value->status == 1)
                                        <span class="label label-success" style="font-size: 12px">Send</span>
                                    @else
                                          <span class="label label-info" style="font-size: 12px">Unsend</span>
                                    @endif
                                </td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;">{{$value['number']}}</span></td>
                                <td colspan="" rowspan="" headers="">
                                    <?php 
                                        $hscd  = App\HistoryLog_View::where('phone',$value['phone'])->whereRaw('history_log_id IS NULL AND progress_info = 5')->first();
                                        $hsv = App\HistoryLog_View::where('phone',$value['phone'])->whereRaw('history_log_id IS NOT NULL')->first();
                                    ?>
                                    @if(!empty($hsv))
                                    <span style="font-weight: bold;" class="label label-success">Hồ sơ vay</span>
                                    @elseif(!empty($hscd))
                                    <span style="font-weight: bold; " class="label label-danger">Hồ sơ chấm điểm</span>
                                    @else
                                    <span style="font-weight: bold;">Không có</span>
                                    @endif 
                                    
                                </td>
                                <td colspan="" rowspan="" headers=""><span style="font-weight: bold;"> {{date('d-m-Y', strtotime($value->created_at))}}</span></td>
                            </tr>
							@endforeach
                            

                        </tbody>
                         
                    </table>
                    <nav style="float: right;">
                            {!!$support->render()!!}
                        </nav>
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
    <div class="modal fade" id="exampleModalImport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="form-inline" action="{{url('admin/swifi/import')}}" method="POST" enctype="multipart/form-data" files ="true">
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
                                <span class="text-boil">File excel:</span>
                              </td>
                              <td>
                               
                               <input type="file" name="excel" id="import" accept=".csv,.xlsx,.xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" ID="fileSelect" runat="server">
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
    @endif
@stop
@section('javascript')
<script type="text/javascript">
    $('.importexcel').click(function(){
            var name=$('#import').val();
            if(name=='')
                {
                    $('#errImport').text('Vui lòng chọn file excel.').slideDown('slow').delay(2000).slideUp('slow');
                    $('#import').focus();
                    return false;
                }
        });
</script>
@stop



