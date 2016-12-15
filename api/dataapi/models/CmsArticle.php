<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cms_article".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $content
 * @property string $keywords
 * @property string $description
 * @property string $source
 * @property integer $d_num
 * @property integer $status
 * @property string $created
 * @property integer $creater
 * @property string $updated
 * @property integer $updater
 */
class CmsArticle extends \yii\db\ActiveRecord
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
        return 'cms_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'd_num', 'status', 'creater', 'updater'], 'integer'],
            [['content', 'description'], 'string'],
            [['created', 'updated'], 'safe'],
            [['title', 'keywords'], 'string', 'max' => 255],
            [['source'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => '文章分类id',
            'title' => '文章标题',
            'content' => '文章内容',
            'keywords' => '关键字',
            'description' => '描述',
            'source' => '来源',
            'd_num' => '点击次数',
            'status' => '状态 1：待发布  2：已发布 9：删除',
            'created' => '创建时间',
            'creater' => '创建者',
            'updated' => '更新时间',
            'updater' => '更新者',
        ];
    }
}
