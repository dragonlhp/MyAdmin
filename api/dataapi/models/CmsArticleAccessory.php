<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cms_article_accessory".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $name
 * @property string $size
 * @property string $path
 * @property integer $d_num
 * @property string $created
 * @property integer $creater
 * @property string $updated
 * @property integer $updater
 */
class CmsArticleAccessory extends \yii\db\ActiveRecord
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
//        return 'cms_article_accessory';
        return 'cms_article_accessory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'd_num', 'creater', 'updater'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name', 'path'], 'string', 'max' => 255],
            [['size'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'name' => 'Name',
            'size' => 'Size',
            'path' => 'Path',
            'd_num' => 'D Num',
            'created' => 'Created',
            'creater' => 'Creater',
            'updated' => 'Updated',
            'updater' => 'Updater',
        ];
    }
}
