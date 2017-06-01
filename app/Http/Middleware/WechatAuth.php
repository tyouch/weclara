<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
//use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use App\Libraries\HttpRequest;
use App\Account;

class WechatAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd($request->input('code') && $request->session()->get('openid'));
        $openid = session('openid') && null;

        if(empty($openid)){

            //第一步：用户同意授权，获取code
            $code = $request->input('code') ? $request->input('code') : null;
            if (empty($code)) {
                $authorize_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . config('wechat.APPID') . '&redirect_uri=' . urlencode(config('wechat.BASEURL')) . '&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
                return redirect($authorize_url);
            }

            // 第二步：通过code换取网页授权access_token
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . config('wechat.APPID') . '&secret=' . config('wechat.APPSECRET') . '&code=' . $code . '&grant_type=authorization_code';
            $token_openid = HttpRequest::toArray($get_token_url); //dd($token_openid);

            // 第三步：刷新access_token（如果需要）

            // 第四步：拉取用户信息(需scope为 snsapi_userinfo)
            $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $token_openid['access_token'] . '&openid=' . $token_openid['openid'] . '&lang=zh_CN';
            $user_info = HttpRequest::toArray($get_user_info_url);
            $user_info = array_merge($token_openid, $user_info); //dd($user_info);
            session($user_info);
        }else{
            $user_info = $request->session()->all();
        }

        cookie('nickname', session('nickname'), 1800);
        cookie('openid', session('openid'), 1800);
        //dd($user_info, cookie('nickname', session('nickname'), 1800));

        // 判断用户表中是否有该用户
        $account = Account::where(['openid'=>session('openid')])->first();
        //dd($user_info, session('openid'),$account->openid, $account);
        //var_dump($user_info, $account);exit;

        //没有则保存
        if (empty($account)){ // insert
            $post = new Account;
            $post->openid   = $user_info['openid'];
            $post->nickname = urlencode($user_info['nickname']);
            $post->headimgurl = $user_info['headimgurl'];
            $post->hers_bit = 0;
            $post->telephone = '';
            //dd($post);
            $post->save();
            //dd('Insert ok');
        }else{
            if($account->nickname != urlencode($user_info['nickname']) || $account->headimgurl != $user_info['headimgurl']){
                $account->nickname = urlencode($user_info['nickname']);
                $account->headimgurl = $user_info['headimgurl'];
                //dd($account);
                $account->save();
                dd('Update ok');
            }
            cookie('if_register', $account['if_register'], 1800);
            //dd('Not updated');
        }

        return $next($request);
    }
}
