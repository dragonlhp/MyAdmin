<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "cms_flink".
 *
 * @property integer $id
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
            [['rank', 'status', 'creater', 'updater'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name', 'url'], 'string', 'max' => 255],
//            [['img'], 'string', 'max' => 255],

            [['name', 'url'],'required','message'=>'Please Enter a Link Name Or RUL']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'url' => '链接地址',
            'rank' => '序号',
            'img' => '图片',
            'status' => '状态 1：待发布  2：已发布  9：删除',
            'created' => '创建时间',
            'creater' => '创建者',
            'updated' => '更新时间',
            'updater' => '更新者',
        ];
    }
}
