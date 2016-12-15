<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_gb_expert".
 *
 * @property integer $id
 * @property integer $ctype
 * @property string $org_name
 * @property string $name
 * @property string $position
 * @property string $tel
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsGbExpert extends \yii\db\ActiveRecord
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
        return 'bs_gb_expert';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctype', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['org_name', 'position'], 'string', 'max' => 255],
            [['name', 'tel'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ctype' => 'Ctype',
            'org_name' => 'Org Name',
            'name' => 'Name',
            'position' => 'Position',
            'tel' => 'Tel',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
