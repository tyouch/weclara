<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        var HOST = '<?= $host?>';
        wx.config({
            debug       : false,
            appId       : '<?= $signPackage["appId"];?>',
            timestamp   : '<?= $signPackage["timestamp"];?>',
            nonceStr    : '<?= $signPackage["nonceStr"];?>',
            signature   : '<?= $signPackage["signature"];?>',
            jsApiList   : [
                // 所有要调用的 API 都要加到这个列表中
                'checkJsApi',
                //'openLocation',
                //'getLocation',
                'onMenuShareAppMessage'
            ]
        });
        wx.ready(function () {
            wx.checkJsApi({
                jsApiList: [
                    'getLocation'
                ],
                success: function (res) {
                    // alert(JSON.stringify(res));
                    // alert(JSON.stringify(res.checkResult.getLocation));
                    if (res.checkResult.getLocation == false) {
                        alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                        return;
                    }
                }
            });
            wx.onMenuShareAppMessage({
                title   : 'Tyou', // 分享标题
                desc    : '微信公众平台是运营者通过公众号为微信用户提供资讯和服务的平台，而公众平台开发接口则是提供服务的基础，开发者在公众平台网站中创建公众号、获取接口权限后，可以通过阅读本接口文档来帮助开发。', // 分享描述
                link    : HOST, // 分享链接
                imgUrl  : 'http://wx.qlogo.cn/mmopen/Z62csequ0j69tyiaqBZHiaNQxoust9rjG95G6iaafv72Vw8dmrVZCicEHNzC8mtqnibTtYjd3QecoBogEicDD11JwLbYJ48icnIJibvQ/0', // 分享图标
                type    : '', // 分享类型,music、video或link，不填默认为link
                dataUrl : '', // 如果type是music或video，则要提供数据链接，默认为空
                success : function () {
                    location.href = HOST;
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            /*wx.getLocation({
             success: function (res) {
             var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
             var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
             var speed = res.speed; // 速度，以米/每秒计
             var accuracy = res.accuracy; // 位置精度
             alert("纬度:"+latitude+" 经度:"+longitude);
             },
             cancel: function (res) {
             alert('用户拒绝授权获取地理位置');
             }
             });*/
        });
        //http://api.map.baidu.com/geocoder?location=39.80091,116.5021&output=json&key=28bcdd84fae25699606ffad27f8da77b
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
