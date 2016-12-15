<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bs_member_service".
 *
 * @property integer $id
 * @property string $member_id
 * @property string $name
 * @property string $link_name
 * @property string $link_mobile
 * @property string $link_tel
 * @property string $co_name
 * @property string $co_mobile
 * @property string $co_tel
 * @property string $created
 * @property string $updated
 */
class BsMemberService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_member_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['link_name', 'link_mobile', 'link_tel', 'co_name', 'co_mobile', 'co_tel'], 'string', 'max' => 50]
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
            'co_name' => 'Co Name',
            'co_mobile' => 'Co Mobile',
            'co_tel' => 'Co Tel',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
