<?php
/**
 * @auther lb
 * @date: 16/11/9
 * 功能说明:
 * 树形数据操作说明
 */

namespace common\services;


class SerTree {


    /**
     * 遍历分类树
     * @param $items
     * @param string $id
     * @param string $pid
     * @param string $son
     * @return array
     */
    static function formatTree($items, $id = 'id', $pid = 'pid', $son = 'children'){

        $tree = array();
        $tmpMap = array();

        foreach($items as $item) {
            $tmpMap[ $item[ $id ] ] = $item;
        }

        foreach($items as $item) {
            if(isset($tmpMap[ $item[ $pid ] ])) {
                $tmpMap[ $item[ $pid ] ][ $son ][] = &$tmpMap[ $item[ $id ] ];
            }else {
                if(isset($item[ $pid ])) {
                    $tree[] = &$tmpMap[ $item[ $id ] ];
                }
            }
        }
        return $tree;
    }

}
