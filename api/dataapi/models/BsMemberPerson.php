<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_member_person".
 *
 * @property integer $id
 * @property string $member_id
 * @property string $name
 * @property string $mobile
 * @property string $qq
 * @property string $created
 * @property string $updated
 */
class BsMemberPerson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_member_person';
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
            [['mobile', 'qq'], 'string', 'max' => 50]
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
            'mobile' => 'Mobile',
            'qq' => 'Qq',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
