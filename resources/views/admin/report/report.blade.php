@extends('voyager::master')

@section('page_header')
@stop
@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div id="curve_chart" style="width: 100%; height: auto;"></div>
                        <h3>Biểu đồ thống kê trong 30 ngày</h3>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 text-center">
                      <div id="columnchart_material" style="width: 100%; height: 500px;"></div>
                      <form class="form-inline" action="{{url('admin/for-control-report')}}" method="POST">

                       <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"/>
                       <div class="container">
                           <div class="row" style="margin-top: 20px;">
                               <label for="">Chọn năm</label>
                               <select class="custom-select" id="inputGroupSelect01" name="year">
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                </select>
                           </div>
                           <button type="submit" class="btn btn-success">Xem báo cáo</button>
                       </div>
                     </form>
                        <h3>Biểu đồ thống kê trong 12 tháng qua</h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop
@section('javascript')
    <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart_1);

        function drawChart_1() {
        var data = google.visualization.arrayToDataTable([
          ['', 'LEAD', 'HS vay', 'HS dược duyệt'],
          <?php foreach ($history_col_chart as $key => $value) :?>
          ['{{'Tháng '.$value->Thang}}', {{$value->lead}}, {{$value->ho_so_vay}}, {{$value->duocduyet}}],
          <?php endforeach ?>
        ]);

        var options = {
          chart: {
            title: 'Biểu đồ thống kê tình trạng hồ sơ trong năm {{$value->Nam}}',
            subtitle: '',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', 'LEAD', 'HSV', 'HS dược duyệt'],
         <?php foreach ($history_line_chart as $key => $val):?>
          ['{{$val->Ngay.'/'.$val->Thang.'/2018'}}', {{ $val->lead }} , {{ $val->ho_so_vay }} ,{{$val->duocduyet}}],
          <?php endforeach ?>
        ]);

        var options = {
          title: 'Biểu đồ thống kê tình trạng hồ sơ trong 30 ngày gần nhất',
          hAxis: {title: '',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
            
    </script>
@stop



