<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Static Top Navbar Example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    <script href="{{asset('bootstrap/js/bootstrap.js')}}"></script>
    <style>
        th{
            text-align: center;
        }
        tr{
            text-align: center;
        }
    </style>
</head>
<body>
    <div>
        <br>
        <div class="panel panel-info">
            <div class="panel-heading">开天眼</div>
            <table class="table table-striped table-hover">
                <tr>
                    <th>公司名称</th>
                    <th>公司法人</th>
                    <th>联系电话</th>
                    <th>联系邮箱</th>
                    <th>注册时间</th>
                    <th>注册资本</th>
                </tr>
                @foreach($result as $data)
                    <tr>
                        <td>{{isset($data['company_name']) ? $data['company_name'] : '无'}}</td>
                        <td>{{isset($data['company_people']) ? $data['company_people'] : '无'}}</td>
                        <td>{{isset($data['phone']) ? $data['phone'] : '无'}}</td>
                        <td>{{isset($data['email']) ? $data['email'] : '无'}}</td>
                        <td>{{isset($data['company_time']) ? $data['company_time'] : '无'}}</td>
                        <td>{{isset($data['company_price']) ? $data['company_price'] : '无'}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</body>
</html>