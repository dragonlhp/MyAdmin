<?php
namespace common\models;
use Yii;
class BsMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_member';
    }
}
