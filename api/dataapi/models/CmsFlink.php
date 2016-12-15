<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cms_flink".
 *
 * @property integer $id
 * @property integer $lan
 * @property integer $pid
 * @property string $name
 * @property string $url
 * @property integer $rank
 * @property string $img
 * @property integer $status
 * @property string $created
 * @property integer $creater
 * @property string $updated
 * @property integer $updater
 */
class CmsFlink extends \yii\db\ActiveRecord
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
        return 'cms_flink';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lan', 'pid', 'rank', 'status', 'creater', 'updater'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name', 'url', 'img'], 'string', 'max' => 255]
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
            'pid' => 'Pid',
            'name' => 'Name',
            'url' => 'Url',
            'rank' => 'Rank',
            'img' => 'Img',
            'status' => 'Status',
            'created' => 'Created',
            'creater' => 'Creater',
            'updated' => 'Updated',
            'updater' => 'Updater',
        ];
    }
}
