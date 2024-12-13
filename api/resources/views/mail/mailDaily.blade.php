<!DOCTYPE html>
<html>
<head>
    <title>M.Work.com</title>
    <style>
        .banner{
            text-align: center;
            background: #c3eeda9e;
            width: 100%;
            padding: 30px;
            border: 1px solid green;
        }
        .footer{
            text-align: center;
            background: #c3eeda9e;
            width: 100%;
            padding: 30px 30px 10px 30px;
            border: 1px solid green;
        }
        .content{
            padding: 30px;
            font-size: 20px;
        }
        hr{
            width: 350px;
            /* margin: 20px 0; */
            margin-top: 20px;
            margin-bottom: 20px;
            color: green;
            background: green;
            height: 3px;
        }
        #fb{
            padding: 10px 15px;
            border-radius: 5px;
            background: #4267B2;
            color: white;
        }
        #ws{
            padding: 10px 15px;
            border-radius: 5px;
            background: #cb6b33;
            color: white;
        }
    </style>
</head>
<body>
<div class="banner">
    <a href="https://im.ge/i/j26Pa"><img src="https://i.im.ge/2021/08/12/j26Pa.png" alt="j26Pa.png" border="0"></a>
    <h1>{{ $details['title'] }}</h1>
</div>
<div class="content">
    <p>{!! $details['body'] !!}</p>
    <p>Vui lòng truy cập website để xem chi tiết công việc của bạn, xin cảm ơn!</p>
</div>
<div class="footer">
<div class="button">
    <button id="fb">Facebook</button>
    <button id="ws">Website</button>
    <hr>
    <p>Thuộc khoa Công Nghệ Thông Tin - Học viện Nông nghiệp Việt Nam</p>
    <p>Website: <u>http://127.0.0.1</u></p>
    <p>Địa chỉ hòm thư liên hệ: <u>uyenvt.vnua@gmail.com</u></p>
    <p>Phát triển bởi Vũ Thị Uyên, Chử Văn Tình, Nguyễn Viết Đồng © Vnua 2021 </p>
</div>
</div>

</body>
</html>