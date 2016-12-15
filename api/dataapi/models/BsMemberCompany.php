<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_member_company".
 *
 * @property integer $id
 * @property string $member_id
 * @property integer $industry_id
 * @property integer $incubator_id
 * @property integer $incubator_member_id
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
 * @property string $created
 * @property string $updated
 */
class BsMemberCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_member_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'industry_id', 'incubator_id', 'incubator_member_id'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name', 'address'], 'string', 'max' => 255],
            [['link_name', 'link_mobile', 'link_tel', 'link_fax', 'co_name', 'co_mobile', 'qq', 'postal', 'capital'], 'string', 'max' => 50]
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
            'industry_id' => 'Industry ID',
            'incubator_id' => 'Incubator ID',
            'incubator_member_id' => 'Incubator Member ID',
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
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
