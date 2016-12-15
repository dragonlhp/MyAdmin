<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_sd_carrier_req".
 *
 * @property integer $id
 * @property integer $lan
 * @property integer $owner
 * @property integer $owner_type
 * @property string $company
 * @property integer $area_reqs
 * @property integer $price
 * @property string $link_name
 * @property string $link_tel
 * @property string $country
 * @property integer $carrier_type_id
 * @property string $other
 * @property string $mprice
 * @property string $coupon
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsSdCarrierReq extends \yii\db\ActiveRecord
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
        return 'bs_sd_carrier_req';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lan', 'owner', 'owner_type', 'area_reqs', 'price', 'carrier_type_id', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['company', 'link_name', 'link_tel', 'country', 'other', 'mprice', 'coupon'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lan' => 'Lan',
            'owner' => 'Owner',
            'owner_type' => 'Owner Type',
            'company' => 'Company',
            'area_reqs' => 'Area Reqs',
            'price' => 'Price',
            'link_name' => 'Link Name',
            'link_tel' => 'Link Tel',
            'country' => 'Country',
            'carrier_type_id' => 'Carrier Type ID',
            'other' => 'Other',
            'mprice' => 'Mprice',
            'coupon' => 'Coupon',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
