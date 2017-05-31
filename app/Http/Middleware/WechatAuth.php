<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Routing\Redirector;

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
        //
        $openid = $request->session()->get('openid') && null;

        if(empty($openid)){

            $code = $request->input('code') && null;
            //dd(config('wechat.APPID'));
            if (empty($code)) { // wx 授权验证
                //dd($_SERVER);
                $authorize_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . config('wechat.APPID') . '&redirect_uri=' . urlencode(config('wechat.BASEURL')) . '&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
                return redirect($authorize_url);
            }
            dd($code);
            // 获取wx信息
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . config('wechat.APPID') . '&secret=' . config('wechat.APPSECRET') . '&code=' . $code . '&grant_type=authorization_code';
            return api($get_token_url);

            $token_openid = $this->get_token_openid($code);
            $openid = $token_openid['openid'];
            $user_info = $this->get_user_info($token_openid['access_token'], $openid);
            $user_info = array_merge($token_openid, $user_info);
            //var_dump($user_info);exit;
            $this->session->set_userdata($user_info);
        }else{
            $user_info = $this->session->all_userdata();
        }

        $this->input->set_cookie('nickname', $this->session->userdata('nickname'), 1800);
        $this->input->set_cookie('openid', $this->session->userdata('openid' ), 1800);


        // 判断用户表中是否有该用户
        $this->load->model('account_model');
        $account = $this->account_model->getInfoByOpenid($openid, true);

        //var_dump($account,$user_info);
        //var_dump($account['nickname'],$account['headimgurl'],$user_info['nickname'],$user_info['headimgurl']);exit;

        //没有则保存
        if (empty($account)){
            $this->account_model->save($user_info);
        }else{
            if($account['nickname'] != $user_info['nickname'] || $account['headimgurl'] != $user_info['headimgurl']) {
                $this->account_model->save($user_info, true);
            }
            $this->input->set_cookie('if_register', $account['if_register'], 1800);
        }
        //

        return $next($request);
    }
}
