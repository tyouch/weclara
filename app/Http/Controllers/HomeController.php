<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('wechatAuth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $authorize_url = urlencode('https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . config('wechat.APPID') . '&redirect_uri=' . config('wechat.BASEURL') . '&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect');
        dd($authorize_url);
        redirect($authorize_url);

        return view('home');
    }
}
