<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 16/12/2
 * 功能说明:
 */

namespace app\services\common;


class ComStarred
{
    static private $f10 = 10;
    static private $f20 = 20;
    static private $f30 = 30;
    static private $f40 = 40;
    static private $Data = 40;


    /**
     * 人员评分
     */
    static private function Personnel()
    {

        $reData = self::$Data;
        $fen[] = $reData['p_n1'] > 0 ? self::$f30 : 0;  //科学院院士
        $fen[] = $reData['p_n2'] > 0 ? self::$f10 : 0;  //留学硕士人员
        $fen[] = $reData['p_n3'] > 0 ? self::$f20 : 0;  //国家千人计划
        $fen[] = $reData['p_n4'] > 0 ? self::$f10 : 0;  //国内博士人员
        $fen[] = $reData['p_n5'] > 0 ? self::$f30 : 0;  //知名企业骨干人员


        return $fen[array_search(max($fen), $fen)];

    }

    /**
     * 知识产权
     */
    static private function Property()
    {
        $reData = self::$Data;

        $fen[] = (int)$reData['ipright1'] == 1 ? self::$f10 : 0;  //获得授权的1项发明专利
        $sum1 = (int)$reData['ipright2'];  //实用新型专利
        $sum1 += (int)$reData['ipright3'];  //获得外观专利
        $sum1 += (int)$reData['ipright4'];  //获得软件著作权
        $sum1 += (int)$reData['ipright5'];  //其他(知识产权)
        $fen[] = $sum1 >= 5 ? self::$f10 : 0; //以上四项纸盒不少于5项

        $fen[] = (int)$reData['ipright1'] == 2 ? self::$f20 : 0;  //获得授权的2项发明专利
        $sum2 = (int)$reData['ipright2'];  //实用新型专利
        $sum2 += (int)$reData['ipright3'];  //获得外观专利
        $sum2 += (int)$reData['ipright4'];  //获得软件著作权
        $sum2 += (int)$reData['ipright5'];  //其他(知识产权)
        $fen[] = $sum2 >= 10 ? self::$f20 : 0; //以上四项纸盒不少于10项

        $fen[] = (int)$reData['ipright1'] == 3 ? self::$f30 : 0;  //获得授权的3项发明专利
        $sum3 = (int)$reData['ipright2'];  //实用新型专利
        $sum3 += (int)$reData['ipright3'];  //获得外观专利
        $sum3 += (int)$reData['ipright4'];  //获得软件著作权
        $sum3 += (int)$reData['ipright5'];  //其他(知识产权)
        $fen[] = $sum3 >= 15 ? self::$f30 : 0; //以上四项纸盒不少于15项

        $fen[] = (int)$reData['ipright1'] >= 4 ? self::$f40 : 0;  //获得授权的4项发明专利
        $sum4 = (int)$reData['ipright2'];  //实用新型专利
        $sum4 += (int)$reData['ipright3'];  //获得外观专利
        $sum4 += (int)$reData['ipright4'];  //获得软件著作权
        $sum4 += (int)$reData['ipright5'];  //其他(知识产权)
        $fen[] = $sum4 >= 20 ? self::$f40 : 0; //以上四项纸盒不少于20项


        return $fen[array_search(max($fen), $fen)];


    }

    /**
     * 市场前景
     */
    static private function Market()
    {
        $reData = self::$Data;
        $reData = $reData['sales'];
        $data = [];
        foreach ($reData as $key => $value) {
            $data[$value['year']] = $value;
        }
        $datas = $data[date('Y') - 1];
        $fen[] = $datas['income'] >= 200 ? self::$f10 : 0;     //≥200万元
        $fen[] = $datas['income'] >= 500 ? self::$f20 : 0;     //≥500万元
        $fen[] = $datas['income'] >= 1000 ? self::$f30 : 0;    //≥1000万元
        $fen[] = $datas['income'] >= 2000 ? self::$f40 : 0;    //≥2000万元

        return $fen[array_search(max($fen), $fen)];
    }

    /**
     * 企业资质
     */
    static private function Qualification()
    {
        $reData = self::$Data;


        $fen[] = $reData['qu1'] == 1 ? self::$f20 : 0;//企业资质-国家高新技术企业（1：否  2：是）
        $fen[] = $reData['qu2'] == 1 ? self::$f20 : 0;//企业资质-技术先进型服务企业（1：否  2：是）
        $fen[] = $reData['qu3'] == 1 ? self::$f30 : 0;//企业资质-软件企业认定（1：否  2：是）
        $fen[] = $reData['qu4'] == 1 ? self::$f10 : 0;//企业资质-上规入库企业（1：否  2：是）
        $fen[] = $reData['qu5'] == 1 ? self::$f40 : 0;//企业资质-上市企业 （1：否  2：是）
        $fen[] = $reData['qu6'] == 1 ? self::$f30 : 0;//企业资质-全国中小企业股份转让系统挂牌 （1：否  2：是）
        $fen[] = $reData['qu7'] == 1 ? self::$f20 : 0;//企业资质-成都（川藏）股权交易中心（融资板或交易板）挂牌 （1：否  2：是）

        return $fen[array_search(max($fen), $fen)];
    }

    /**
     * 所有项目求和
     * @return array|int
     */
    static private function Sum()
    {
        $sum[] = self::Personnel();
        $sum[] = self::Property();
        $sum[] = self::Market();
        $sum[] = self::Qualification();
        $ret = array_sum($sum);
        if ($ret > 100) {
            return 100;
        } else {
            return $ret;
        }

    }

    /**
     * 评星级
     */
    static public function Grade($data)
    {
        self::$Data = $data;
        $sum = self::Sum();
        $grade[] = $sum >= 60 || $sum < 70 ? 1 : 0;
        $grade[] = $sum >= 70 || $sum < 80 ? 2 : 0;
        $grade[] = $sum >= 80 || $sum < 90 ? 3 : 0;
        $grade[] = $sum >= 90 || $sum < 100 ? 4 : 0;
        $grade[] = $sum >= 100 ? 5 : 0;
        return $grade[array_search(max($grade), $grade)];
    }
}
