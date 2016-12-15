<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_senior_platform".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $senior_id
 * @property integer $ptype
 * @property string $name
 * @property string $build_date
 * @property string $note
 * @property double $invest
 * @property string $created
 * @property string $updated
 */
class BsSeniorPlatform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_senior_platform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'senior_id', 'ptype'], 'integer'],
            [['invest'], 'number'],
            [['created', 'updated'], 'safe'],
            [['name', 'build_date'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 255]
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
            'ptype' => 'Ptype',
            'name' => 'Name',
            'build_date' => 'Build Date',
            'note' => 'Note',
            'invest' => 'Invest',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
