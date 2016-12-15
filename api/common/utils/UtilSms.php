<?php
// +----------------------------------------------------------------------
// | 短信服务
// +----------------------------------------------------------------------
// | 2016-12-02
// +----------------------------------------------------------------------
// | Author: lb
// +----------------------------------------------------------------------
namespace common\utils;

use common\utils\UtilHttp;

class UtilSms
{
     /**
     * 发送短信
     * @param unknown $url  请求地址
     * @param unknown $userid  用户id
     * @param unknown $account  账号
     * @param unknown $password  密码
     * @param unknown $mobile  电话号码
     * @param unknown $content  短信内容
     * @return array()
     */
    public static function send($url,$userid,$account,$password,$mobile,$content){
        $res=['status'=>'faild','msg'=>'','success_count'=>0];

        if(!empty($url) && !empty($userid) && !empty($account) && !empty($password) && !empty($mobile)  && !empty($content)){
            $parm=['action'=>'send','userid'=>$userid,'account'=>$account,'password'=>$password,'mobile'=>$mobile,'content'=>$content,'sendTime'=>'','extno'=>''];
        
            $resdata=UtilHttp::curl_http_post($url,$parm);
            $resdata=simplexml_load_string($resdata);

            $res['status']=strtolower($resdata->returnstatus);
            $res['msg']=strtolower($resdata->message);
            $res['success_count']=intval($resdata->successCounts);
        }
        return $res; 
    }
}
?>