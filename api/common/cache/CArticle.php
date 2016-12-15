<?php 
namespace common\cache;

use Yii;
use common\models\Article;
use common\models\Column;

/**
 * 文章的一些缓存数据
 * 
 * @author 雷震子
 *
 */

class CArticle{
    
    /**
     * 猜你喜欢,每8小时更新一次缓存
     * 
     * @return 
     */
    public static function guess(){
        $key = 'article_list_guess_key';
        
        $cache = Yii::$app->cache;
        $data = $cache->get($key);
        // if ($data === false) {
            $query = Article::find()
                ->orderBy('rand()')
                ->limit(10);
            $data = $query->all();
            $cache->set($key, $data, 28800);//8小时刷新一次缓存
        // }
        return $data;
    }
    
    /**
     * 通过Column获取文章
     * @param $channel_id 
     * @return 
     */
    public static function findColumnArticle($channel_id){
        $key = md5('zixunpindao_key'.$channel_id);
        $cache = Yii::$app->cache;
        $data = $cache->get($key);
        //if ($data === false) {
            //  $data 存放到缓存供下次使用
            $col = Column::find()->where(['channel_id' => $channel_id ])
                ->andWhere("articles <> ''")->one();
            if($col && is_string($col->articles)){
               $list = json_decode($col->articles);
                if(is_array($list)){
                    $data = [];
                    foreach($list as $k => $v){
                        if($tmp = Article::find()->andWhere(['id'=>$v->id])->one()){
                            $data[] = $tmp;
                        }
                    }
                    $cache->set($key, $data, 300);//五分钟刷新一次缓存
                } 
            }
        //}
        return $data?$data:[];
    }
    /**
     * 
     * @param $channel_id 
     * @return 
     */
    public static function findHotArticle($channel_id,$limit){
        $hotkey = md5('zixun_hot_key'.$channel_id.$limit);
        $cache = Yii::$app->cache;
        if(!($hot = $cache->get($hotkey))){
            $hot = Article::findChannelArticle( $channel_id  ,$limit ,'browse_shownum DESC');
            $cache->set($hotkey, $hot , 300);
        }
        return $hot;
    }
}
?>