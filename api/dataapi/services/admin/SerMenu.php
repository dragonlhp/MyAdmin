<?php 
namespace app\services\admin;

use app\models\BasicMenu;


class  SerMenu{

        /** 
        * 
        * 获取无限极菜单树
        * 
        * @access public 
        * @since 1.0 
        * @param int $parent_id  父级id
        * @return array 
        */ 
        public static function getTree($parent_id=0){
            $menus=BasicMenu::find()->select("id,parent_id as pid,name as text")->andWhere(['parent_id'=>$parent_id])->asArray()->all();
            if($menus){
                foreach ($menus as $key => $value) {
                    $menus[$key]['children']=self::getTree($value['id']);
                }
            }
            return $menus; 
        }
}
?>