@extends('voyager::master')

@section('page_header')
@stop
@section('content')
    <div class="page-content browse container-fluid">
            <div class="row">
            	@if(Auth::user()->role_id === 1 || Auth::user()->role_id === 4) {{-- test if admin || admin  Administrator --}}
            	<div class="col-md-12">
            		<div class="panel panel-bordered">
                    <div class="panel-body">
						<div class="col-sm-6 ">
							<div>

									<div class="row">
										<div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
											<h3 style="    text-transform: uppercase;">Thông tin cá nhân</h3>
										</div>
										<table class="table table-hover table-responsive">
										  <tbody class="col">
										    <tr>
										      <td>Họ và tên : <span class="text-boil">{{$log['name']}}</span> </td>
										      <td>CMND : <span class="text-boil"> {{$log['cmnd']}}</span></td>
										    </tr>
										     <tr>
										      <td>Email : <span class="text-boil">{{$log['email']}}</span> </td>
										      <td>SĐT : <span class="text-boil"> {{$log['phone']}} </span></td>
										    </tr>
										    <tr>
										      <td>Giới tính : <span class="text-boil">{{$log['gender']}}</span> </td>
										      <td>Tuổi : <span class="text-boil"> {{($log['age'] != 0) ? $log['age'] : '' }} </span></td>
										    </tr>
										     <tr>
										      <td>Kết hôn : <span class="text-boil">{{$log['married']}}</span> </td>
										      <td>Số người con : <span class="text-boil"> {{ ($log['child']) ? $log['child'] : '' }} </span></td>
										    </tr>
										    <tr>
										      <td colspan="2">Địa chị thường trú : <span class="text-boil">{{$log['address1']}}</span> </td>
										    </tr>
										     <tr>
										      <td colspan="2">Địa chỉ tạm trú : <span class="text-boil">{{$log['address2']}}</span> </td>
										    </tr>
										     <tr>
										      <td>Có đi làm : <span class="text-boil">{{$log['job']}}</span> </td>
										      <td>Thu nhập trung bình : <span class="text-boil">
											     
											       {{  ($log['income'] != '') ? number_format($log['income'],0) : '' }}
											       
										   	VNĐ </span></td>
										    </tr>
										     
										    <tr>
										      <td>Khoản muốn vay : <span class="text-boil">
											       {{ ($log['rent_money'] != '') ?number_format($log['rent_money'],0) : ''}} 
										     
										  VNĐ</span> </td>
										      <td>Ghi chú : <span class="text-boil">  </span></td>
										    </tr>
										    <tr>
										      <td>Điểm : <span class="text-boil">{{$log['final_score']}} Điểm </span> </td>
										      <td>Khoản cho vay : <span class="text-boil"> {{$log['loan']}} Triệu VNĐ </span> </td>
										    </tr>
										     <tr>
										      <td>Lãi suất : <span class="text-boil">{{$log['interest_rate']}} %/Năm</span> </td>
										      <td>Kỳ hạn vay : <span class="text-boil"> {{$log['duration']}} Tháng </span> </td>
										    </tr>
										  </tbody>
										</table>
									</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="">
							<div class="container">
								<div class="row">
									<div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
										<h3 style="    text-transform: uppercase;">Thông tin cic</h3>
									</div>
									<table class="table table-hover table-responsive">
									  <tbody class="col">
									    <tr>
									      <td>Mã CIC: <span class="text-boil">{{$log['cid_code']}}</span> </td>
									    </tr>
									     <tr>
									     	<td>Họ và tên : <span class="text-boil"> {{$log['cid_customer_name']}} </span></td>
									    </tr>
									     <tr>
									      <td>Địa chỉ : <span class="text-boil">{{$log['cid_customer_address']}}</span> </td>
									    </tr>
									    <tr>
									      <td>SĐT : <span class="text-boil">{{$log['cid_customer_tell']}}</span> </td>
									    </tr>
									    <tr>
									      <td>Đánh giá : <span class="text-boil">{{$log['cid_result']}}</span> </td>
									    </tr>
									  </tbody>
									</table>
								</div>
							</div>
							</div>
							<div class="" style="margin-top: 20px;">
							<div class="container">
								<div class="row">
									<div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
										<h3 style="    text-transform: uppercase;">Thông tin thuế</h3>
									</div>
									<table class="table table-hover table-responsive">
									  <tbody class="col">
									    <tr>
									      <td>Mã số thuế: <span class="text-boil"></span> </td>
									    </tr>
									     <tr>
									     	<td>Họ và tên : <span class="text-boil"></span></td>
									    </tr>
									     <tr>
									      <td>Địa chỉ Đăng ký : <span class="text-boil"></span> </td>
											</tr>
											<tr>
									      <td>CMND : <span class="text-boil"></span> </td>
											</tr>
											<tr>
									      <td>Tỉnh - Thành phố : <span class="text-boil"></span> </td>
											</tr>
											<tr>
									      <td>Ngày đăng ký	 : <span class="text-boil"></span> </td>
											</tr>
											<tr>
									      <td>Ghi chú : <span class="text-boil"></span> </td>
									    </tr>

									  </tbody>
									</table>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
				<hr style="width: 70%; font-size: 24px;">
				<form action="{{url('admin/update-note-admin',$log["id"])}}" method="post">
					 @csrf
				        <div class="row">
					            <div class="form-group col-md-12">
					                  <label class="text-boil" for="inputEmail4">Ghi chú :</label>
										<textarea name="admin_note" placeholder="nhập ghi chú.." id="" cols="30" style="width: 100%; " >{{$log["admin_note"]}}</textarea>
					            </div>
				        </div>
				        <button type="submit" class="btn btn-primary">Save</button>
			</div>
			@elseif(Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {{-- test if admin || admin  Administrator --}}
			@if(Auth::user()->id === $log['user_id']) {{-- set user id login == user_id in data --}}
			<div class="col-md-12">
    		<div class="panel panel-bordered">
            <div class="panel-body">
				<div class="col-sm-12 ">
					<div>
						<div class="container">
							<div class="row">
								<div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
									<h3 style="    text-transform: uppercase;">Thông tin cá nhân</h3>
								</div>
								<table class="table table-hover table-responsive">
								  <tbody class="col">
								    <tr>
								      <td>Họ và tên : <span class="text-boil">{{$log['name']}}</span> </td>
								      <td>CMND : <span class="text-boil"> {{$log['cmnd']}}</span></td>
								    </tr>
								     <tr>
								      <td>Email : <span class="text-boil">{{$log['email']}}</span> </td>
								      <td>SĐT : <span class="text-boil"> {{$log['phone']}} </span></td>
								    </tr>
								    <tr>
								      <td>Giới tính : <span class="text-boil">{{$log['gender']}}</span> </td>
								      <td>Tuổi : <span class="text-boil"> {{($log['age'] != 0) ? $log['age'] : '' }} </span></td>
								    </tr>
								     <tr>
								      <td>Kết hôn : <span class="text-boil">{{$log['married']}}</span> </td>
								      <td>Số người con : <span class="text-boil"> {{ ($log['child']) ? $log['child'] : '' }} </span></td>
								    </tr>
								    <tr>
								      <td colspan="2">Địa chị thường trú : <span class="text-boil">{{$log['address1']}}</span> </td>
								    </tr>
								     <tr>
								      <td colspan="2">Địa chỉ tạm trú : <span class="text-boil">{{$log['address2']}}</span> </td>
								    </tr>
								     <tr>
								      <td>Có đi làm : <span class="text-boil">{{$log['job']}}</span> </td>
								      <td>Thu nhập trung bình : <span class="text-boil">  {{  ($log['income'] != '') ? number_format($log['income'],0) : '' }} VNĐ </span></td>
								    </tr>
								    <tr>
								      <td>Khoản muốn vay : <span class="text-boil">{{ ($log['rent_money'] != '') ?number_format($log['rent_money'],0) : ''}}  VNĐ</span> </td>
								      <td>Ghi chú : <span class="text-boil">  </span></td>
								    </tr>
								    <tr>
									      <td>Điểm : <span class="text-boil">{{$log['final_score']}} Điểm </span> </td>
									      <td>Khoản cho vay : <span class="text-boil"> {{$log['loan']}} Triệu VNĐ </span></td>
									    </tr>
									     <tr>
									      <td>Lãi suất : <span class="text-boil">{{$log['interest_rate']}} %/Năm</span> </td>
									      <td>Kỳ hạn vay : <span class="text-boil"> {{$log['duration']}} Tháng </span></td>
									    </tr>
								  </tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				</div>
				</div>
			</div>	
			@endif
			@endif
		</div>
		</div>
 	</div>
@endsection

<style type="text/css" media="screen">
	.body
	{
		margin: 0;
		padding: 0;
	}
	.boder
	{
		border: 1px solid;
		border-color: #f1efef;
		border-top-right-radius: 5px;
		border-top-left-radius: 5px;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
	}
	.mar-gin
	{
		padding-top: 50px;
	}
	.text-boil
	{
		color: black;
		font-weight: bold;
	}
	#Ghichu
	{
		border: 1px solid;
		border-color: #f1efef;
		border-top-right-radius: 5px;
		border-top-left-radius: 5px;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
	}
	.app-container.expanded .side-body{margin-left:0px !important}
</style>
