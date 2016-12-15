<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_setting".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $vdata
 */
class BasicSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'string', 'max' => 50],
            [['vdata'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'vdata' => 'Vdata',
        ];
    }
}
