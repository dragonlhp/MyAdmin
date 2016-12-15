<?php
/**
 * Created by PhpStorm.
 * User: silkios
 * Date: 16/11/21
 * Time: 下午3:25
 */
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class BsSdCoupon extends \yii\db\ActiveRecord
{
    /**
     * 自动添加创建和更新人以及时间
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => date("Y-m-d H:i:s")
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_coupon';
    }

    public function rules()
    {
        return [
            [['status','owner', 'service_id', 'service_member_id'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['code','contract_price','discounts','req_company','link_name','link_tel','code'], 'string', 'max' => 255]
        ];
    }
}