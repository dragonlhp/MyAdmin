<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_senior_industry".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $senior_id
 * @property integer $industry_id
 * @property string $note
 * @property string $updated
 */
class BsSeniorIndustry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_senior_industry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'senior_id', 'industry_id'], 'integer'],
            [['updated'], 'safe'],
            [['note'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'senior_id' => 'Senior ID',
            'industry_id' => 'Industry ID',
            'note' => 'Note',
            'updated' => 'Updated',
        ];
    }
}
