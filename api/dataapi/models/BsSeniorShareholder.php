<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_senior_shareholder".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $senior_id
 * @property string $name
 * @property double $investment
 * @property double $equity_ratio
 * @property string $education
 * @property string $school
 * @property integer $workcond
 * @property string $investment_type
 * @property string $indate
 * @property string $created
 * @property string $updated
 */
class BsSeniorShareholder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_senior_shareholder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'senior_id', 'workcond'], 'integer'],
            [['investment', 'equity_ratio'], 'number'],
            [['indate', 'created', 'updated'], 'safe'],
            [['name', 'education', 'school', 'investment_type'], 'string', 'max' => 50]
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
            'name' => 'Name',
            'investment' => 'Investment',
            'equity_ratio' => 'Equity Ratio',
            'education' => 'Education',
            'school' => 'School',
            'workcond' => 'Workcond',
            'investment_type' => 'Investment Type',
            'indate' => 'Indate',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
