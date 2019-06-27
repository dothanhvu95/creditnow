<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p>Thông tin hồ sơ vay của {{$data['hoten']}}: </p>
                <ul>
                    @if($data['hoten'] != '' || $data['hoten'] != null)
                    <li>Họ tên: {{$data['hoten']}}</li>
                    @endif
                    @if($data['age'] != '' || $data['age'] != null)
                    <li>Tuổi: {{$data['age']}}</li>
                    @endif
                     @if($data['cmnd'] != '' || $data['cmnd'] != null)
                    <li>CMND/CCCD: {{$data['cmnd']}}</li>
                    @endif
                     @if($data['phone'] != '' || $data['phone'] != null)
                    <li>SĐT: {{$data['phone']}}</li>
                    @endif
                    @if($data['thunhap'] != '' || $data['thunhap'] != null)
                    <li>Thu nhập: {{$data['thunhap']}}</li>
                    @endif
                    @if($data['khoan_vay'] != '' || $data['khoan_vay'] != null)
                    <li>Khoản muốn vay: {{$data['khoan_vay']}} VNĐ</li>
                    @endif
                    @if($data['addr'] != '' || $data['addr'] != null)
                    <li>Địa chỉ: {{$data['addr']}}</li>
                    @endif
                </ul>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <p>Trân trọng!</p>
                            <div class="ft">
                                <p style="margin: 2px;">Fibo - Credit Team.</p>      
                                <p style="margin: 2px;">https://creditnow.vn</p>     
                                <p style="margin: 2px;">https://creditnow.vn</p>                                                      
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>

</body>
<style>
    div.ct p {
  	   margin: 2px 43px;
    }
    div.ft p { margin:2px; }
</style>
</html>