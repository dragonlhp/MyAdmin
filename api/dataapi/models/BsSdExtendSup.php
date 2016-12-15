<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_sd_extend_sup".
 *
 * @property integer $id
 * @property integer $pro_type
 * @property integer $pro_id
 * @property string $name
 * @property string $value
 * @property integer $rank
 * @property string $updated
 */
class BsSdExtendSup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_sd_extend_sup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pro_type', 'pro_id'], 'required'],
            [['pro_type', 'pro_id', 'rank'], 'integer'],
            [['value'], 'string'],
            [['updated'], 'safe'],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pro_type' => 'Pro Type',
            'pro_id' => 'Pro ID',
            'name' => 'Name',
            'value' => 'Value',
            'rank' => 'Rank',
            'updated' => 'Updated',
        ];
    }
}
