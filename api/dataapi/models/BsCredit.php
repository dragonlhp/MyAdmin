<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_credit".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $level
 * @property string $year
 * @property string $created
 * @property string $updated
 */
class BsCredit extends \yii\db\ActiveRecord
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
        return 'bs_credit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'level'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['year'], 'string', 'max' => 50]
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
            'level' => 'Level',
            'year' => 'Year',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
