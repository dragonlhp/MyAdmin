<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 16/12/9
 * 功能说明:
 */

namespace app\services\common;


use app\models\BsSdExtendReq;

class Extendreq
{
    static public function view($type, $id)
    {
        return BsSdExtendReq::find()->where("pro_type = $type and pro_id = $id")->asArray()->all();
    }
}

