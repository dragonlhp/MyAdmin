<?php
/**
 *处理邮件发送内容
 *
 *
 * @author      libin<hansen.li@silksoftware.com>
 * @version     1.0
 * @since       1.0
 */
namespace common\services;


class SerMailContent
{


    /**
     * 获取注册激活邮件内容
     * @param $parm  site:站点名称  loginname：登录名称  activeurl：激活地址  siturl：站点地址
     * $parm=['site'=>'','loginname'=>,'activeurl'=>'','siturl'=>]
     */
   public  static function getRegistActiveContent($parm=[])
    {

        $res['title']='您在'.@$parm['site'].'注册的账号'.@$parm['loginname'].'需要进行验证';
        $res['content']='尊敬的用户'.@$parm['loginname'].':

      您好!


      感谢您在我们网站注册成为会员，故系统自动为你发送了这封邮件。请点击下面链接进行验证：
'.@$parm['activeurl'].'。


      '.@$parm['site'].'
      '.date("Y-m-d H:i:s").'
     '.@$parm['siturl'];

        return $res;
    }

    /**
     * 获取忘记密码邮件内容

     * @param $parm  site:站点名称  loginname：登录名称  activeurl：激活地址  siturl：站点地址
     * $parm=['site'=>'','loginname'=>,'activeurl'=>'','siturl'=>]
     */
    public static function getForgotPwdContent($parm=[])
    {

        $res['title']='您在'.@$parm['site'].'找回密码需要进行验证';
        $res['content']='尊敬的用户'.@$parm['loginname'].':

      您好!


      您在我们网站申请找回密码，故系统自动为你发送了这封邮件。请点击下面链接设置您的密码：
'.@$parm['activeurl'].'。


        '.@$parm['site'].'
        '.date("Y-m-d H:i:s").'
        '.@$parm['siturl'];

        return $res;
    }
}