<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_senior_incubator".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $reg_date
 * @property string $reg_capital
 * @property string $act_capital
 * @property string $work_address
 * @property string $code
 * @property string $bl_num
 * @property string $rc_num
 * @property string $legal_rep
 * @property string $mobile
 * @property string $email
 * @property string $link_name
 * @property string $link_mobile
 * @property string $link_email
 * @property string $link_fax
 * @property string $m_type
 * @property string $m_level
 * @property string $m_inc_type
 * @property string $acc_time
 * @property string $unit
 * @property double $area1
 * @property double $area2
 * @property double $area3
 * @property double $area4
 * @property double $area5
 * @property integer $p_total
 * @property integer $p_n1
 * @property integer $p_n2
 * @property integer $p_n3
 * @property integer $p_n4
 * @property integer $p_n5
 * @property double $p_n6
 * @property double $rent_price
 * @property double $tube_fee
 * @property double $income1
 * @property double $income2
 * @property integer $py_co1
 * @property string $py_co1_list
 * @property integer $py_co2
 * @property string $py_co2_list
 * @property integer $py_co3
 * @property double $py_co4
 * @property integer $py_co5
 * @property double $py_co6
 * @property integer $py_co7
 * @property double $py_co8
 * @property double $py_co9
 * @property double $py_co10
 * @property integer $py_co11
 * @property double $py_co12
 * @property double $py_co13
 * @property integer $py_co14
 * @property double $py_co15
 * @property integer $zh1
 * @property integer $zh2
 * @property integer $zh3
 * @property integer $zh4
 * @property integer $zh5
 * @property integer $zh6
 * @property integer $zh7
 * @property integer $zh8
 * @property integer $zh9
 * @property integer $zh10
 * @property integer $zh11
 * @property integer $zh12
 * @property integer $zh13
 * @property string $note
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsSeniorIncubator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_senior_incubator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'p_total', 'p_n1', 'p_n2', 'p_n3', 'p_n4', 'p_n5', 'py_co1', 'py_co2', 'py_co3', 'py_co5', 'py_co7', 'py_co11', 'py_co14', 'zh1', 'zh2', 'zh3', 'zh4', 'zh5', 'zh6', 'zh7', 'zh8', 'zh9', 'zh10', 'zh11', 'zh12', 'zh13', 'status'], 'integer'],
            [['reg_date', 'created', 'updated'], 'safe'],
            [['area1', 'area2', 'area3', 'area4', 'area5', 'p_n6', 'rent_price', 'tube_fee', 'income1', 'income2', 'py_co4', 'py_co6', 'py_co8', 'py_co9', 'py_co10', 'py_co12', 'py_co13', 'py_co15'], 'number'],
            [['py_co1_list', 'py_co2_list', 'note'], 'string'],
            [['name', 'work_address', 'code', 'link_name', 'm_type', 'm_level', 'm_inc_type', 'unit'], 'string', 'max' => 255],
            [['reg_capital', 'act_capital', 'bl_num', 'rc_num', 'legal_rep', 'mobile', 'email', 'link_mobile', 'link_email', 'link_fax', 'acc_time'], 'string', 'max' => 50]
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
            'name' => 'Name',
            'reg_date' => 'Reg Date',
            'reg_capital' => 'Reg Capital',
            'act_capital' => 'Act Capital',
            'work_address' => 'Work Address',
            'code' => 'Code',
            'bl_num' => 'Bl Num',
            'rc_num' => 'Rc Num',
            'legal_rep' => 'Legal Rep',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'link_name' => 'Link Name',
            'link_mobile' => 'Link Mobile',
            'link_email' => 'Link Email',
            'link_fax' => 'Link Fax',
            'm_type' => 'M Type',
            'm_level' => 'M Level',
            'm_inc_type' => 'M Inc Type',
            'acc_time' => 'Acc Time',
            'unit' => 'Unit',
            'area1' => 'Area1',
            'area2' => 'Area2',
            'area3' => 'Area3',
            'area4' => 'Area4',
            'area5' => 'Area5',
            'p_total' => 'P Total',
            'p_n1' => 'P N1',
            'p_n2' => 'P N2',
            'p_n3' => 'P N3',
            'p_n4' => 'P N4',
            'p_n5' => 'P N5',
            'p_n6' => 'P N6',
            'rent_price' => 'Rent Price',
            'tube_fee' => 'Tube Fee',
            'income1' => 'Income1',
            'income2' => 'Income2',
            'py_co1' => 'Py Co1',
            'py_co1_list' => 'Py Co1 List',
            'py_co2' => 'Py Co2',
            'py_co2_list' => 'Py Co2 List',
            'py_co3' => 'Py Co3',
            'py_co4' => 'Py Co4',
            'py_co5' => 'Py Co5',
            'py_co6' => 'Py Co6',
            'py_co7' => 'Py Co7',
            'py_co8' => 'Py Co8',
            'py_co9' => 'Py Co9',
            'py_co10' => 'Py Co10',
            'py_co11' => 'Py Co11',
            'py_co12' => 'Py Co12',
            'py_co13' => 'Py Co13',
            'py_co14' => 'Py Co14',
            'py_co15' => 'Py Co15',
            'zh1' => 'Zh1',
            'zh2' => 'Zh2',
            'zh3' => 'Zh3',
            'zh4' => 'Zh4',
            'zh5' => 'Zh5',
            'zh6' => 'Zh6',
            'zh7' => 'Zh7',
            'zh8' => 'Zh8',
            'zh9' => 'Zh9',
            'zh10' => 'Zh10',
            'zh11' => 'Zh11',
            'zh12' => 'Zh12',
            'zh13' => 'Zh13',
            'note' => 'Note',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
