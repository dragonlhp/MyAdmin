<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bs_gb_service_content}}".
 *
 * @property integer $id
 * @property integer $gb_service_id
 * @property integer $content_id
 * @property string $updated
 */
class BsGbServiceContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bs_gb_service_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gb_service_id', 'content_id'], 'integer'],
            [['updated'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gb_service_id' => 'Gb Service ID',
            'content_id' => 'Content ID',
            'updated' => 'Updated',
        ];
    }
}
