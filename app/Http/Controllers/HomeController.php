<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Jssdk;
use App\Account;

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
        /*$account = Account::where(['openid'=>'owbLpwXc-7_xkCp3qs1FJG15Kx5M'])->first();
        $account->nickname = 'Tyou';
        $account->headimgurl = 'http://wx.qlogo.cn/mmopen/PiajxSqBRaEL0EIibUt6K6E1uxJXgZaNzSfavIusckVJzIAjQhUZmUCTmnxwEfGrcpXkibG5PjObG8YMS3dDHInYA/0';
        $account->save();
        dd($account);
        $authorize_url = urlencode('https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . config('wechat.APPID') . '&redirect_uri=' . config('wechat.BASEURL') . '&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect');
        dd($authorize_url);
        redirect($authorize_url);*/
        $params = [
            'appid'     => config('wechat.APPID'),
            'appsecret' => config('wechat.APPSECRET')
        ];
        dd((new Jssdk($params))->getSignPackage(null));
        return view('home', [
            '$host'         => config('wechat.BASEURL'),
            'signPackage'   => (new Jssdk($params))->getSignPackage(null),
        ]);
    }
}
