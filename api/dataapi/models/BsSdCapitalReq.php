<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_sd_capital_req".
 *
 * @property integer $id
 * @property integer $lan
 * @property integer $owner
 * @property integer $owner_type
 * @property string $company
 * @property integer $capital_req
 * @property string $useing
 * @property string $legal
 * @property string $reg_date
 * @property string $captial
 * @property string $reg_address
 * @property string $industry
 * @property string $patent
 * @property string $pre_income
 * @property string $pre_profit
 * @property string $pre_loan
 * @property string $money_supply
 * @property string $bonding_company
 * @property string $loan_type
 * @property string $loan_date
 * @property string $return_date
 * @property string $rate
 * @property string $note
 * @property string $link_name
 * @property string $link_tel
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsSdCapitalReq extends \yii\db\ActiveRecord
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
        return 'bs_sd_capital_req';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lan', 'owner', 'owner_type', 'capital_req', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['company', 'useing', 'legal', 'reg_date', 'captial', 'reg_address', 'industry', 'patent', 'pre_income', 'pre_profit', 'pre_loan', 'money_supply', 'bonding_company', 'loan_type', 'loan_date', 'return_date', 'rate', 'note', 'link_name', 'link_tel'], 'string', 'max' => 255]
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
            'company' => 'Company',
            'capital_req' => 'Capital Req',
            'useing' => 'Useing',
            'legal' => 'Legal',
            'reg_date' => 'Reg Date',
            'captial' => 'Captial',
            'reg_address' => 'Reg Address',
            'industry' => 'Industry',
            'patent' => 'Patent',
            'pre_income' => 'Pre Income',
            'pre_profit' => 'Pre Profit',
            'pre_loan' => 'Pre Loan',
            'money_supply' => 'Money Supply',
            'bonding_company' => 'Bonding Company',
            'loan_type' => 'Loan Type',
            'loan_date' => 'Loan Date',
            'return_date' => 'Return Date',
            'rate' => 'Rate',
            'note' => 'Note',
            'link_name' => 'Link Name',
            'link_tel' => 'Link Tel',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
