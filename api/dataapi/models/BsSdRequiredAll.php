<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_sd_required_all".
 *
 * @property integer $id
 * @property integer $owner_type
 * @property integer $status
 * @property integer $lan
 * @property integer $owner
 * @property string $created
 * @property string $pro_type
 */
class BsSdRequiredAll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_sd_required_all';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'owner_type', 'status', 'lan', 'owner', 'pro_type'], 'integer'],
            [['created'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_type' => 'Owner Type',
            'status' => 'Status',
            'lan' => 'Lan',
            'owner' => 'Owner',
            'created' => 'Created',
            'pro_type' => 'Pro Type',
        ];
    }
}
