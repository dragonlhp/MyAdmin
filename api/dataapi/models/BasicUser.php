<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "basic_user".
 *
 * @property integer $id
 * @property string $role_ids
 * @property string $login_name
 * @property string $pwd
 * @property string $name
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BasicUser extends \yii\db\ActiveRecord
{
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
        return 'basic_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['role_ids'], 'string', 'max' => 1024],
            [['login_name', 'name'], 'string', 'max' => 64],
            [['pwd'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_ids' => 'Role Ids',
            'login_name' => 'Login Name',
            'pwd' => 'Pwd',
            'name' => 'Name',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
