<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_qa".
 *
 * @property integer $id
 * @property integer $lan
 * @property integer $qtype
 * @property string $content
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsQa extends \yii\db\ActiveRecord
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
        return 'bs_qa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lan', 'qtype', 'status'], 'integer'],
            [['content'], 'string'],
            [['created', 'updated'], 'safe']
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
            'qtype' => 'Qtype',
            'content' => 'Content',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
