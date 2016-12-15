<?php

namespace app\models;


use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_sd_carrier_sup".
 *
 * @property integer $id
 * @property integer $lan
 * @property integer $owner
 * @property integer $owner_type
 * @property integer $member_id
 * @property integer $incubator_id
 * @property string $carrier_area
 * @property string $rent_area
 * @property string $address
 * @property string $rental
 * @property string $property
 * @property string $link_name
 * @property string $link_tel
 * @property integer $carrier_type_id
 * @property string $coupon
 * @property string $note
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsSdCarrierSup extends \yii\db\ActiveRecord
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
        return 'bs_sd_carrier_sup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lan', 'owner', 'owner_type', 'member_id', 'incubator_id', 'carrier_type_id', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['carrier_area', 'rent_area', 'address', 'rental', 'property', 'link_name', 'link_tel', 'coupon', 'note'], 'string', 'max' => 255]
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
            'member_id' => 'Member ID',
            'incubator_id' => 'Incubator ID',
            'carrier_area' => 'Carrier Area',
            'rent_area' => 'Rent Area',
            'address' => 'Address',
            'rental' => 'Rental',
            'property' => 'Property',
            'link_name' => 'Link Name',
            'link_tel' => 'Link Tel',
            'carrier_type_id' => 'Carrier Type ID',
            'coupon' => 'Coupon',
            'note' => 'Note',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

}
