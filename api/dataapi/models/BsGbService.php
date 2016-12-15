<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%bs_gb_service}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property string $link_name
 * @property string $tel
 * @property integer $status
 * @property string $updated
 */
class BsGbService extends \yii\db\ActiveRecord
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
        return '{{%bs_gb_service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['updated'], 'safe'],
            [['name', 'content'], 'string', 'max' => 255],
            [['link_name', 'tel'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'content' => 'Content',
            'link_name' => 'Link Name',
            'tel' => 'Tel',
            'status' => 'Status',
            'updated' => 'Updated',
        ];
    }
}
