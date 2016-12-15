<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cms_category".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $lan
 * @property integer $showtype
 * @property string $url
 * @property string $name
 * @property string $descriptions
 * @property string $created
 * @property integer $creater
 * @property string $updated
 * @property integer $updater
 */
class CmsCategory extends \yii\db\ActiveRecord
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
        return 'cms_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'lan', 'showtype', 'creater', 'updater','module'], 'integer'],
            [['descriptions'], 'string'],
            [['created', 'updated'], 'safe'],
            [['url', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父级ID',
            'lan' => '语言  1：中文 2：英文  3：韩文',
            'showtype' => '显示类型 1：列表显示  2：内容显示（显示分类下的第一篇文章内容） 3：跳转',
            'url' => '链接跳转地址',
            'name' => '分类名称',
            'descriptions' => '描述',
            'created' => '创建时间',
            'creater' => '创建者',
            'updated' => '更新时间',
            'updater' => '更新者',
        ];
    }
}
