<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_member_incubator".
 *
 * @property integer $id
 * @property string $member_id
 * @property string $name
 * @property string $link_name
 * @property string $link_mobile
 * @property string $link_tel
 * @property string $link_fax
 * @property string $co_name
 * @property string $co_mobile
 * @property string $qq
 * @property string $address
 * @property string $postal
 * @property string $capital
 * @property string $garden_address
 * @property string $garden_area
 * @property integer $garden_cnum
 * @property integer $work_num
 * @property string $created
 * @property string $updated
 */
class BsMemberIncubator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_member_incubator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'garden_cnum', 'work_num'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name', 'link_name', 'link_mobile', 'link_tel', 'link_fax', 'co_name', 'co_mobile', 'qq', 'postal', 'capital', 'garden_address', 'garden_area'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'name' => 'Name',
            'link_name' => 'Link Name',
            'link_mobile' => 'Link Mobile',
            'link_tel' => 'Link Tel',
            'link_fax' => 'Link Fax',
            'co_name' => 'Co Name',
            'co_mobile' => 'Co Mobile',
            'qq' => 'Qq',
            'address' => 'Address',
            'postal' => 'Postal',
            'capital' => 'Capital',
            'garden_address' => 'Garden Address',
            'garden_area' => 'Garden Area',
            'garden_cnum' => 'Garden Cnum',
            'work_num' => 'Work Num',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
