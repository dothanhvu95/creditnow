<script type="text/javascript">

	// biểu đồ thống kê lượng lead theo từng ngân hàng

	google.charts.load('current', {'packages':['corechart']});

	// Set a callback to run when the Google Visualization API is loaded.
	google.charts.setOnLoadCallback(drawChart1);

	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart1() {

	// Create the data table.
	var data1 = new google.visualization.DataTable();
	data1.addColumn('string', 'Topping');
	data1.addColumn('number', 'Slices');
	data1.addRows([
		@if(isset($report_partner_1))
			@foreach($report_partner_1 as $key => $val)
			  ['{{$val->tctd_name}}', {{$val->lead}}],
			@endforeach
		@endif
	]);

	// Set chart options
	var options1 = {'title':'Biểu đồ tỉ lệ gửi hồ sơ đến các TCTD',
	               'width':400,
	               'height':300};

	// Instantiate and draw our chart, passing in some options.
	var chart1 = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart1.draw(data1, options1);
	}


	/*bieeir đồ thống kê tỉ lệ giải ngân theo từng ngân hàng*/
	google.charts.load('current', {'packages':['corechart']});

	// Set a callback to run when the Google Visualization API is loaded.
	google.charts.setOnLoadCallback(drawChart);

	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Topping');
	data.addColumn('number', 'Slices');
	data.addRows([
		@if(isset($report_partner_1))
			@foreach($report_partner_1 as $key => $val)
			  ['{{$val->tctd_name}}', {{$val->success}}],
			@endforeach
		@endif
	]);

	// Set chart options
	var options = {'title':'Biểu đồ tỉ lệ giải ngân hồ sơ của các TCTD',
	               'width':400,
	               'height':300};

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart(document.getElementById('chart_div_1'));
	chart.draw(data, options);
	}
</script>