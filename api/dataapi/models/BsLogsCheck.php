<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_logs_check".
 *
 * @property integer $id
 * @property string $ctable
 * @property string $cid
 * @property string $cstatsu
 * @property string $reason
 * @property string $tel
 * @property integer $creater
 * @property string $created
 */
class BsLogsCheck extends \yii\db\ActiveRecord
{
    /**
     * 自动添加创建和更新人以及时间
     * @return array
     */
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => TimestampBehavior::className(),
//                'createdAtAttribute' => 'created',
//
//                'value' => date("Y-m-d H:i:s")
//            ]
//        ];
//    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_logs_check';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reason'], 'string'],
            [['creater'], 'integer'],
            [['created'], 'safe'],
            [['ctable', 'cid', 'cstatus', 'tel'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ctable' => 'Ctable',
            'cid' => 'Cid',
            'cstatus' => 'Cstatus',
            'reason' => 'Reason',
            'tel' => 'Tel',
            'creater' => 'Creater',
            'created' => 'Created',
        ];
    }
}
