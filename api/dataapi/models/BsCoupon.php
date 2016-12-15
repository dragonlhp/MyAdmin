<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_coupon".
 *
 * @property integer $id
 * @property integer $owner
 * @property integer $service_id
 * @property integer $service_member_id
 * @property string $code
 * @property string $contract_price
 * @property string $discounts
 * @property string $req_company
 * @property string $link_name
 * @property string $link_tel
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner', 'service_id', 'service_member_id', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['code', 'discounts', 'link_name', 'link_tel'], 'string', 'max' => 50],
            [['contract_price', 'req_company'], 'string', 'max' => 255],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner' => 'Owner',
            'service_id' => 'Service ID',
            'service_member_id' => 'Service Member ID',
            'code' => 'Code',
            'contract_price' => 'Contract Price',
            'discounts' => 'Discounts',
            'req_company' => 'Req Company',
            'link_name' => 'Link Name',
            'link_tel' => 'Link Tel',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
