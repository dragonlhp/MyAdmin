<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_sd_apply".
 *
 * @property integer $id
 * @property integer $apply_id
 * @property integer $sup_id
 * @property integer $sup_role
 * @property integer $pro_cat
 * @property integer $pro_type
 * @property integer $pro_id
 * @property string $cooperation
 * @property integer $check_status
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsSdApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_sd_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apply_id', 'sup_id', 'sup_role', 'pro_cat', 'pro_type', 'pro_id', 'check_status', 'status'], 'integer'],
            [['pro_type', 'pro_id', 'cooperation'], 'required'],
            [['cooperation', 'created', 'updated'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apply_id' => 'Apply ID',
            'sup_id' => 'Sup ID',
            'sup_role' => 'Sup Role',
            'pro_cat' => 'Pro Cat',
            'pro_type' => 'Pro Type',
            'pro_id' => 'Pro ID',
            'cooperation' => 'Cooperation',
            'check_status' => 'Check Status',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
