<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_sd_capital_sup".
 *
 * @property integer $id
 * @property integer $lan
 * @property integer $owner
 * @property integer $owner_type
 * @property string $product
 * @property string $bonding_type
 * @property string $supply
 * @property string $loan_months
 * @property string $loan_total
 * @property string $return_type
 * @property string $loan_rate
 * @property string $areas
 * @property string $max_loan_months
 * @property string $max_loan_total
 * @property string $bonding_company
 * @property string $link_name
 * @property string $link_tel
 * @property string $loan_date
 * @property string $return_date
 * @property string $note
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsSdCapitalSup extends \yii\db\ActiveRecord
{
    /**
     * 自动添加创建和更新人以及时间
     * @return array
     */
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
        return 'bs_sd_capital_sup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lan', 'owner', 'owner_type', 'status'], 'integer'],
            [['note'], 'string'],
            [['created', 'updated'], 'safe'],
            [['product', 'bonding_type', 'supply', 'loan_months', 'loan_total', 'return_type', 'loan_rate', 'areas', 'max_loan_months', 'max_loan_total', 'bonding_company', 'link_name', 'link_tel', 'loan_date', 'return_date'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lan' => 'Lan',
            'owner' => 'Owner',
            'owner_type' => 'Owner Type',
            'product' => 'Product',
            'bonding_type' => 'Bonding Type',
            'supply' => 'Supply',
            'loan_months' => 'Loan Months',
            'loan_total' => 'Loan Total',
            'return_type' => 'Return Type',
            'loan_rate' => 'Loan Rate',
            'areas' => 'Areas',
            'max_loan_months' => 'Max Loan Months',
            'max_loan_total' => 'Max Loan Total',
            'bonding_company' => 'Bonding Company',
            'link_name' => 'Link Name',
            'link_tel' => 'Link Tel',
            'loan_date' => 'Loan Date',
            'return_date' => 'Return Date',
            'note' => 'Note',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
