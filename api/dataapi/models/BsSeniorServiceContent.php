<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_senior_service_content".
 *
 * @property integer $id
 * @property integer $senior_service_id
 * @property integer $content_id
 * @property string $created
 * @property string $updated
 */
class BsSeniorServiceContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_senior_service_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['senior_service_id', 'content_id'], 'integer'],
            [['created', 'updated'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'senior_service_id' => 'Senior Service ID',
            'content_id' => 'Content ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
