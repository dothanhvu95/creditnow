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
                <p>Thông tin user của partner - {{$data['name']}}: </p>
                <ul>
                    @if($data['name'] != '' || $data['name'] != null)
                    <li>Họ tên: {{$data['name']}}</li>
                    @endif
                    @if($data['email'] != '' || $data['email'] != null)
                    <li>Email: {{$data['email']}}</li>
                    @endif
                     @if($data['avatar'] != '' || $data['avatar'] != null)
                    <li>Password: 123456</li>
                    @endif
                </ul>
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