<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bs_bdata".
 *
 * @property integer $id
 * @property integer $btype
 * @property string $name
 * @property integer $rank
 * @property integer $status
 */
class BsBdata extends \yii\db\ActiveRecord

{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_bdata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['btype', 'rank', 'status'], 'integer'],
            [['name'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'btype' => 'Btype',
            'name' => 'Name',
            'rank' => 'Rank',
            'status' => 'Status',
        ];
    }
}
