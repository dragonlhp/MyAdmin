<?php
/**
 * Copyright (c) 2016, SILK Software
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *    This product includes software developed by the SILK Software.
 * 4. Neither the name of the SILK Software nor the
 *   names of its contributors may be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY SILK Software ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL SILK Software BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * Created by PhpStorm.
 * User: Bob song <bob.song@silksoftware.com>
 * Date: 16-11-7
 * Time: 11:14
 */

namespace common\services;

use common\models\BsBdata;

class BdataServices
{
    /**
     * get industry type id
     */
    const INDUSTRY_BTYPE_ID = 1;

    /**
     * get incubator type id
     */
    const CARRIER_BTYPE_ID = 2;

    /**
     * get area type id
     */
    const AREA_BTYPE_ID = 3;

    /**
     * get capital type id
     */
    const CAPITAL_BTYPE_ID = 4;

    /**
     * get price type id
     */
    const PRICE_BTYPE_ID = 5;

    /**
     * get senior service type id
     */
    const CONTENT_BTYPE_ID = 6;

    /**
     * get bdata status value
     */
    const BDATA_STATUS_ID = 1;

    /**
     * get other industry id =8
     */
    const INDUSTRY_OTHER_ID = 8;

    /**
     * get senior status pending
     */
    const SENIOR_STATUS_START = 0;

    /**
     * get senior status pending
     */
    const SENIOR_STATUS_PENDING = 1;

    /**
     * get platform ptype = 1
     */
    const PLATFORM_PTYPE_PUBLIC = 1;

    /**
     * get platform ptype = 2
     */
    const PLATFORM_PTYPE_OTHER = 2;


    /**
     * get all data by type
     * @return \yii\db\ActiveQuery
     */
    public function getDataByType($type)
    {
        $data = BsBdata::find()
            ->where(array('btype' => $type, 'status' => self::BDATA_STATUS_ID))
            ->orderBy('rank ASC')
            ->all();
        return $data;
    }

    /**
     * get platform ptype value
     * @param string $str
     * @return int
     */
    public function getPlatformType($str = '')
    {
        if ($str == 'other') {
            return self::PLATFORM_PTYPE_OTHER;
        } else {
            return self::PLATFORM_PTYPE_PUBLIC;
        }
    }


    /**
     * get 融资用途 Financing Purposes checkbox array
     * @return array
     */
    public function getFinancingPurposesArr()
    {
        return array(
            '1' =>  '周转流动资金',
            '2' =>  '购买原材料',
            '3' =>  '购买生产设备',
            '4' =>  '扩大经营规模',
            '5' =>  '购置固定资产',
            'other'    =>  '其他',
        );
    }

    /**
     * get 供需方	 sup and req checkbox array
     * @return array
     */
    public function getSupReqArr()
    {
        return array(
            '1' =>  '无',
            '2' =>  '技术供给方(只卖出技术)',
            '3' =>  '技术需求方(只买入技术)',
            '4' =>  '技术供需方(根据项目不同，既购买过技术也卖出过技术)',
        );
    }

    /**
     * get 合作对象 cooperation checkbox array
     * @return array
     */
    public function getCooperationArr()
    {
        return array(
            '1' =>  '政府部门',
            '2' =>  '科研院所',
            '3' =>  '企业',
            '4' =>  '大专院校',
            '5' =>  '金融投资机构',
            'other'    =>  '其他',
        );
    }

    /**
     * get 合作类型 checkbox array
     * @return array
     */
    public function getCooperationTypeArr()
    {
        return array(
            '1' =>  '技术转移',
            '2' =>  '合作研发',
            '3' =>  '引进外资',
            '4' =>  '引进设备或人才',
            '5' =>  '承接外包业务',
            '6' =>  '出口产品',
            'other'    =>  '其他',
        );
    }

    /**
     * get 机构类型 service type array
     * @return array
     */
    public function getServiceTypeArr()
    {
        return array(
            '1' =>  '中介机构',
            '2' =>  '公共技术平台',
            '3' =>  '服务机构',
        );
    }

    /**
     * get 经济性质 Economic type array
     * @return array
     */
    public function getEconomicType()
    {
        return array(
            '1' =>  '事业单位',
            '2' =>  '企业',
            '3' =>  '民办非企业法人',
            'other' =>  '其他',
        );
    }

    /**
     * get 创新孵化载体级别 Incubator Level array
     * @return array
     */
    public function getIncubatorLevel()
    {
        return array(
            '1' =>  '国家级',
            '2' =>  '省级 ',
            '3' =>  '市级',
            '4' =>  '区级',
            'other' =>  '其它',
        );
    }

    /**
     * get 创新孵化载体类型 Incubator Type array
     * @return array
     */
    public function getIncubatorType()
    {
        return array(
            '1' =>  '创业苗圃',
            '2' =>  '综合孵化器',
            '3' =>  '专业孵化器',
            '4' =>  '加速器',
        );
    }

    /**
     * get 参与制定标准 Ipright array
     * @return array
     */
    public function getIprightArr()
    {
        return array(
            '0' =>  '无',
            '1' =>  '参与制定地方标准的第一承担企业',
            '2' =>  '参与制定行业标准的第一承担企业',
            '3' =>  '参与制定国家标准的第一承担企业',
            '4' =>  '参与制定国际标准的第一承担企业',
        );
    }

    /**
     * declare 企业类型
     * @return array
     */
    public function getCompanyType()
    {
        return array(
            '1' =>  '国家高新技术企业',
            '2' =>  '软件企业认定',
            '3' =>  '技术先进型服务企业',
            '4' =>  '上规入库企业',
        );
    }

    /**
     * declare 上市类型
     * @return array
     */
    public function getListedType()
    {
        return array(
            '1' =>  '上市企业',
            '2' =>  '全国中小企业股份转让系统挂牌',
            '3' =>  '成都（川藏）股权交易中心（融资板或交易板）挂牌',
        );
    }
}