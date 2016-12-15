<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_member".
 *
 * @property integer $id
 * @property string $login_name
 * @property string $pwd
 * @property string $nick_name
 * @property integer $mtype
 * @property string $email
 * @property integer $email_status
 * @property integer $level
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mtype', 'email_status', 'level', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['login_name',  'nick_name', 'email'], 'string', 'max' => 255],
            [['login_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login_name' => 'Login Name',
            'pwd' => 'Pwd',
            'nick_name' => 'Nick Name',
            'mtype' => 'Mtype',
            'email' => 'Email',
            'email_status' => 'Email Status',
            'level' => 'Level',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
