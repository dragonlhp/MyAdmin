<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_senior_sales".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $senior_id
 * @property string $year
 * @property double $income
 * @property double $profits
 * @property double $tax
 * @property string $created
 * @property string $updated
 */
class BsSeniorSales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_senior_sales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'senior_id'], 'integer'],
            [['income', 'profits', 'tax'], 'number'],
            [['created', 'updated'], 'safe'],
            [['year'], 'string', 'max' => 50]
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
            'year' => 'Year',
            'income' => 'Income',
            'profits' => 'Profits',
            'tax' => 'Tax',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
