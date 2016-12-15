<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kq_attendance".
 *
 * @property integer $aid
 * @property integer $id
 * @property integer $type
 * @property string $uid
 * @property string $username
 * @property string $date
 * @property string $subjct
 * @property integer $year
 * @property integer $month
 * @property integer $day
 * @property string $time
 */
class KqAttendance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kq_attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'type', 'year', 'month', 'day'], 'integer'],
            [['date'], 'safe'],
            [['uid'], 'string', 'max' => 32],
            [['username', 'subjct'], 'string', 'max' => 20],
            [['time'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'id' => 'ID',
            'type' => 'Type',
            'uid' => 'Uid',
            'username' => 'Username',
            'date' => 'Date',
            'subjct' => 'Subjct',
            'year' => 'Year',
            'month' => 'Month',
            'day' => 'Day',
            'time' => 'Time',
        ];
    }
}
