<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 16/11/1
 * 功能说明:
 */

namespace app\services\common;

namespace api\controllers;

use app\models\BasicUser;
use app\models\BsMember;
use yii;

/**
 * 类名称： uploadcontroller
 * 类说明：
 */
class BsOwner
{

    static function Owner($data, $userid = null)
    {
        //取出Member中的载体用户;
        $datas = [];
        foreach ($data as $key => $item) {
            if ($item['owner_type'] == 2) {
                $Member = BsMember::findOne($item['member_id']);
                $datas[$key] = $item;
                $datas[$key]['member'] = $Member['login_name'] ? $Member['login_name'] : '管理员';
                $datas[$key]['owner'] = $Member['login_name'] ? $Member['login_name'] : '管理员';
            } else {
                $Member = BsMember::findOne($item['member_id']);
                $datas[$key] = $item;
                $datas[$key]['member'] = $Member['login_name'] ? $Member['login_name'] : '管理员';

                $User = BasicUser::findOne($userid);
                $datas[$key]['owner'] = $User['name'] ? $User['name'] : '管理员';
            }
        }

    }
}