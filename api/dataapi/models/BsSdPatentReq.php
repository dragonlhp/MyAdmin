<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bs_sd_patent_req".
 *
 * @property integer $id
 * @property integer $lan
 * @property integer $owner
 * @property integer $owner_type
 * @property string $company
 * @property string $ptype
 * @property string $name
 * @property string $req_name
 * @property string $areas
 * @property string $price
 * @property string $link_name
 * @property string $link_tel
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class BsSdPatentReq extends \yii\db\ActiveRecord
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
        return 'bs_sd_patent_req';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lan', 'owner', 'owner_type', 'status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['company', 'ptype', 'name', 'req_name', 'areas', 'price', 'link_name', 'link_tel'], 'string', 'max' => 255]
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
            'ptype' => 'Ptype',
            'name' => 'Name',
            'req_name' => 'Req Name',
            'areas' => 'Areas',
            'price' => 'Price',
            'link_name' => 'Link Name',
            'link_tel' => 'Link Tel',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
