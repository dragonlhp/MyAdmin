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
 * Date: 16-11-22
 * Time: 14:08
 */

namespace common\services;

use common\models\BsQa;
use common\models\CmsCategory;
use common\models\CmsArticle;
use common\models\CmsFlink;
use common\services\SerRoute;

class CmsServices
{
	/**
	 * get show item count 20
	 */
	const SHOW_ITEM_COUNT = 20;

	/**
	 * get carrier type value
	 */
	const CARRIER_TYPE_VALUE = 1;

	/**
	 * get capital type value
	 */
	const CAPITAL_TYPE_VALUE = 2;

	/**
	 * get skill type value
	 */
	const SKILL_TYPE_VALUE = 3;

	/**
	 * get patent type value
	 */
	const PATENT_TYPE_VALUE = 4;

	/**
	 * get product type value
	 */
	const PRODUCT_TYPE_VALUE = 5;

	/**
     * get category module article
     */
    const CATEGORY_MODULE_ARTICLE =2;


    /**
     * get category module news
     */
    const CATEGORY_MODULE_NEWS =1;

     /**
     * Chinese language value
     */
    const LANGUAGE_CHINESE_VALUE = 1;

    /**
     * English language value
     */
    const LANGUAGE_ENGLISH_VALUE = 2;

    /**
     * Korean language value
     */
    const LANGUAGE_KOREAN_VALUE = 3;

	/**
	 * get category by id
	 * @param int $pid
	 * @param int $language
	 * @param string $module
	 * @return array|\yii\db\ActiveRecord[]
	 */
    public function getCategoryById($pid = 0, $language = self::LANGUAGE_CHINESE_VALUE,$module="")
    {
        // get sub category array
        $model = CmsCategory::find()
            ->where(array('pid' =>$pid, 'lan' => $language));

        if(!empty($module)){
        	$category=$model->andWhere(['module'=>$module])->orderBy("id asc")->asArray()->all();
        }else{
            $category=$model->orderBy("id asc")->asArray()->all();
        }

		if($category){
			foreach ($category as $key => $value) {
				$category[$key]['children']=$this->getCategoryById($value['id'],$language,$module);
			}
		}

        return $category;
    }

    /**
	 * get f by id
	 * @param int $pid
	 * @param int $language
	 * @param string $module
	 * @return array|\yii\db\ActiveRecord[]
	 */
    public function getFlinksById($pid = 0, $language = self::LANGUAGE_CHINESE_VALUE)
    {
        // get sub category array
        $model = CmsFlink::find()
            ->where(array('pid' =>$pid, 'lan' => $language,'status'=>2));

        $data=$model->orderBy("rank desc")->asArray()->all();

		if($data){
			foreach ($data as $key => $value) {
				$data[$key]['children']=$this->getFlinksById($value['id'],$language);
			}
		}

        return $data;
    }

	/**
	 * 获取分类导航
	 * @param int $id
	 * @param array $navi
	 * @return array
	 */
    public function getCategoryNavi($id = 0,$navi=array())
    {
        // get sub category array
        $category = CmsCategory::findOne($id);
        if(count($navi)>0){
        	array_unshift($navi, $category);
        }else{
        	$navi[]=$category;
        }
        
        if($category->pid > 0){
        	return $this->getCategoryNavi($category->pid,$navi);
        }else{
        	return $navi;
        }
    }

	/**
	 * get req and sup status = consent all items
	 * @param $model
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public function getItemsByReqSup($model, $language = BsSdServices::LANGUAGE_CHINESE_VALUE)
	{
		$items = $model::find()
			->where(array('status' => BsSdServices::SD_STATUS_CONSENT, 'lan' => $language))
			->limit(self::SHOW_ITEM_COUNT)
			->orderBy(array('id' => SORT_DESC))
			->all();
		return $items;
	}

	/**
	 * get qa items by type
	 * @param $type
	 * @param int $language
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public function getQaByType($type, $language = BsSdServices::LANGUAGE_CHINESE_VALUE)
	{
		$qas = BsQa::find()->select(array('content'))
			->where(array('qtype' => $type, 'status' => BsSdServices::SD_STATUS_CONSENT, 'lan' => $language))
			->limit(self::SHOW_ITEM_COUNT)
			->orderBy(array('id' => SORT_DESC))
			->all();
		return $qas;
	}


	/**
	 * 获取文章分类的链接
	 * @param $category_id
	 * @return string
	 */
	public function getCategoryUrl($category_id){
		$res="";
		$baseurl=\Yii::$app->params['home_url'];
		if($category_id >0){
			$category= CmsCategory::findOne($category_id);
			$showtype=$category->showtype;
			switch ($showtype) {
				case '1':
					$res=$baseurl."article/cat?id=".SerRoute::setParam($category_id);
					break;
				case '2':
					$child=CmsCategory::find()->where(['pid'=>$category_id])->one();
					if($child){
						$res=$this->getCategoryUrl($child->id);
					}else{
						$article=CmsArticle::find()->where(['category_id'=>$category_id,'status'=>2])->one();
						if($article){
							$res=$baseurl."article/view?id=".SerRoute::setParam($article->id);
						}
					}
					break;
				case '3':
					$res=$category->url;
					break;
				default:
					# code...
					break;
			}
		}
		return $res;
	}
	/**
	 * get chinese type by type
	 * @param $type
	 * @return string
	 */
	public function getChineseType($type)
	{
		switch ($type) {
			case 'carrier':
				return '载体';
				break;
			case 'capital':
				return '资金';
				break;
			case 'skill':
				return '技术';
				break;
			case 'patent':
				return '专利';
				break;
			case 'product':
				return '产品';
				break;
			default:
				return '载体';
		}
	}

	/**
	 * get new by category
	 * @param $categoryId
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public function getNewsByCategoryId($categoryId)
	{
		$article = CmsArticle::find()->select(array('id','title','created'))->where(array('category_id' => $categoryId, 'status'=> 2))->limit(10)->all();
		return $article;
	}
}