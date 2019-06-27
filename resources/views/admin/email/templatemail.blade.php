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
                <img src="{{ voyager_asset('css/img/logo.png')}}" alt="">
                <p>Xin chào <span style="font-weight: bold; font-size: 16px;">{{$data['hoten']}}</span>,</p>
                <p>Chúng tôi xin gửi đến <span style="font-weight: bold; font-size: 16px;">{{$data['hoten']}}</span> thông tin xác nhận hồ sơ vay của bạn với các thông tin cơ bản sau : </p>
                <ul>
                     @if($data['hoten'] != '' || $data['hoten'] != null)
                    <li>Họ tên: {{$data['hoten']}}</li>
                    @endif
                     @if($data['email'] != '' || $data['email'] != null)
                    <li>Email: {{$data['email']}}</li>
                    @endif
                     @if($data['cmnd'] != '' || $data['cmnd'] != null)
                    <li>CMND: {{$data['cmnd']}}</li>
                    @endif
                     @if($data['phone'] != '' || $data['phone'] != null)
                    <li>Số điện thoại: {{$data['phone']}}</li>
                    @endif
                    @if($data['note'] != '' || $data['note'] != null)
                    <li>Ghi chú của người duyêt: {{$data['note']}}</li>
                    @endif
                </ul>
                @if($data['status'] == 1)
                    <p style="color: red; font-weight: bold;">Hồ sơ của bạn đã được gửi đi và đang chờ các tổ chức tín dụng duyệt! Chúng tôi sẽ thông báo kết quả ngay sau khi TCTD phản hồi. Xin cảm ơn!</p>
                @elseif($data['status'] == 2)
                    <p style="color: red; font-weight: bold;">Xin chúc mừng! Hồ sơ của bạn đã được duyệt. Xin cảm ơn!</p>
                @elseif($data['status'] == 3)
                    <p style="color: red; font-weight: bold;">Hồ sơ của bạn đã được chúng tôi tiếp nhận. Chúng tôi sẽ liên lạc lại với bạn trong thời gian sớm nhất. Xin cảm ơn!</p>
                @elseif($data['status'] == 4)
                    <p style="color: red; font-weight: bold;">Cảm ơn bạn đã liên lạc với chúng tôi. Chúng tôi sẽ xem sét hồ sơ của bạn và phản hồi lại trong thời gian sớm nhất. Xin cảm ơn!</p>
                @elseif($data['status'] == 5)
                    <p style="color: red; font-weight: bold;">Hồ sơ của bạn đang được chờ để lấy thêm thông tin. Xin cảm ơn!</p>
                @elseif($data['status'] == 6)
                    <p style="color: red; font-weight: bold;">Hồ sơ của bạn đang được xử lý chuyển qua tổ chức tín dụng. Xin cảm ơn!</p>
                @elseif($data['status'] == 7)
                    <p style="color: red; font-weight: bold;">Hồ sơ của bạn đã bị từ chối. Xin cảm ơn!</p>
                @endif
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <p>Trân trọng!</p>
                            <div class="ft">
                                <p style="margin: 2px;">Fibo - Credit Score Team.</p>      
                                <p style="margin: 2px;">https://me.creditScore.vn</p>     
                                <p style="margin: 2px;">https://creditscore.vn</p>                                                      
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