<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_senior_company".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $incubator_id
 * @property integer $incubator_member_id
 * @property string $name
 * @property string $reg_date
 * @property string $reg_capital
 * @property string $act_capital
 * @property string $work_address
 * @property string $work_area
 * @property string $code
 * @property string $bl_num
 * @property string $rc_num
 * @property string $account_name
 * @property string $account_bank
 * @property string $account_num
 * @property string $legal_rep
 * @property string $mobile
 * @property string $work_tel
 * @property string $email
 * @property string $link_name
 * @property string $link_mobile
 * @property string $link_tel
 * @property string $link_email
 * @property string $link_fax
 * @property string $link_qq
 * @property integer $p_total
 * @property integer $p_n1
 * @property integer $p_n2
 * @property integer $p_n3
 * @property integer $p_n4
 * @property integer $p_n5
 * @property integer $ipright1
 * @property integer $ipright2
 * @property integer $ipright3
 * @property integer $ipright4
 * @property string $ipright5
 * @property string $f_equity_date
 * @property double $f_equity_total
 * @property string $f_debt_date
 * @property double $f_debt_total
 * @property double $ot1
 * @property string $ot2
 * @property string $ot3
 * @property integer $qu1
 * @property string $qu1_date
 * @property integer $qu2
 * @property string $qu2_date
 * @property integer $qu3
 * @property string $qu3_date
 * @property integer $qu4
 * @property string $qu4_date
 * @property integer $qu5
 * @property string $qu5_date
 * @property integer $qu6
 * @property string $qu6_date
 * @property integer $qu7
 * @property string $qu7_date
 * @property integer $financing_req
 * @property double $equity_financing
 * @property double $debt_financing
 * @property string $financing_purposes
 * @property integer $bank_loan
 * @property integer $policies
 * @property integer $it_transfer
 * @property integer $it_transfer_num
 * @property integer $it_transfer_num1
 * @property integer $it_transfer_num2
 * @property double $it_transfer_money
 * @property double $it_transfer_money1
 * @property double $it_transfer_money2
 * @property string $sup_req
 * @property integer $com_coo
 * @property string $com_coo_oo
 * @property string $com_coo_type
 * @property string $com_coo_country
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsSeniorCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_senior_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'incubator_id', 'incubator_member_id', 'p_total', 'p_n1', 'p_n2', 'p_n3', 'p_n4', 'p_n5', 'ipright1', 'ipright2', 'ipright3', 'ipright4', 'qu1', 'qu2', 'qu3', 'qu4', 'qu5', 'qu6', 'qu7', 'financing_req', 'bank_loan', 'policies', 'it_transfer', 'it_transfer_num', 'it_transfer_num1', 'it_transfer_num2', 'com_coo', 'status'], 'integer'],
            [['reg_date', 'created', 'updated'], 'safe'],
            [['f_equity_total', 'f_debt_total', 'ot1', 'equity_financing', 'debt_financing', 'it_transfer_money', 'it_transfer_money1', 'it_transfer_money2'], 'number'],
            [['ot3'], 'string'],
            [['name', 'work_address', 'code', 'account_name', 'account_bank', 'account_num', 'link_name', 'ipright5', 'ot2', 'sup_req'], 'string', 'max' => 255],
            [['reg_capital', 'act_capital', 'work_area', 'bl_num', 'rc_num', 'legal_rep', 'mobile', 'work_tel', 'email', 'link_mobile', 'link_tel', 'link_email', 'link_fax', 'link_qq', 'f_equity_date', 'f_debt_date', 'qu1_date', 'qu2_date', 'qu3_date', 'qu4_date', 'qu5_date', 'qu6_date', 'qu7_date', 'com_coo_country'], 'string', 'max' => 50],
            [['financing_purposes', 'com_coo_oo', 'com_coo_type'], 'string', 'max' => 1024]
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
            'incubator_id' => 'Incubator ID',
            'incubator_member_id' => 'Incubator Member ID',
            'name' => 'Name',
            'reg_date' => 'Reg Date',
            'reg_capital' => 'Reg Capital',
            'act_capital' => 'Act Capital',
            'work_address' => 'Work Address',
            'work_area' => 'Work Area',
            'code' => 'Code',
            'bl_num' => 'Bl Num',
            'rc_num' => 'Rc Num',
            'account_name' => 'Account Name',
            'account_bank' => 'Account Bank',
            'account_num' => 'Account Num',
            'legal_rep' => 'Legal Rep',
            'mobile' => 'Mobile',
            'work_tel' => 'Work Tel',
            'email' => 'Email',
            'link_name' => 'Link Name',
            'link_mobile' => 'Link Mobile',
            'link_tel' => 'Link Tel',
            'link_email' => 'Link Email',
            'link_fax' => 'Link Fax',
            'link_qq' => 'Link Qq',
            'p_total' => 'P Total',
            'p_n1' => 'P N1',
            'p_n2' => 'P N2',
            'p_n3' => 'P N3',
            'p_n4' => 'P N4',
            'p_n5' => 'P N5',
            'ipright1' => 'Ipright1',
            'ipright2' => 'Ipright2',
            'ipright3' => 'Ipright3',
            'ipright4' => 'Ipright4',
            'ipright5' => 'Ipright5',
            'f_equity_date' => 'F Equity Date',
            'f_equity_total' => 'F Equity Total',
            'f_debt_date' => 'F Debt Date',
            'f_debt_total' => 'F Debt Total',
            'ot1' => 'Ot1',
            'ot2' => 'Ot2',
            'ot3' => 'Ot3',
            'qu1' => 'Qu1',
            'qu1_date' => 'Qu1 Date',
            'qu2' => 'Qu2',
            'qu2_date' => 'Qu2 Date',
            'qu3' => 'Qu3',
            'qu3_date' => 'Qu3 Date',
            'qu4' => 'Qu4',
            'qu4_date' => 'Qu4 Date',
            'qu5' => 'Qu5',
            'qu5_date' => 'Qu5 Date',
            'qu6' => 'Qu6',
            'qu6_date' => 'Qu6 Date',
            'qu7' => 'Qu7',
            'qu7_date' => 'Qu7 Date',
            'financing_req' => 'Financing Req',
            'equity_financing' => 'Equity Financing',
            'debt_financing' => 'Debt Financing',
            'financing_purposes' => 'Financing Purposes',
            'bank_loan' => 'Bank Loan',
            'policies' => 'Policies',
            'it_transfer' => 'It Transfer',
            'it_transfer_num' => 'It Transfer Num',
            'it_transfer_num1' => 'It Transfer Num1',
            'it_transfer_num2' => 'It Transfer Num2',
            'it_transfer_money' => 'It Transfer Money',
            'it_transfer_money1' => 'It Transfer Money1',
            'it_transfer_money2' => 'It Transfer Money2',
            'sup_req' => 'Sup Req',
            'com_coo' => 'Com Coo',
            'com_coo_oo' => 'Com Coo Oo',
            'com_coo_type' => 'Com Coo Type',
            'com_coo_country' => 'Com Coo Country',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
