<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 16/11/1
 * 功能说明:
 */

namespace app\services\common;


class Cmscommon {


    /**
     * 遍历分类树
     * @param $items
     * @param string $id
     * @param string $pid
     * @param string $son
     * @return array
     */
    static function Getcat($items, $id = 'id', $pid = 'pid', $son = 'children'){

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

    /**
     * 遍历分类树及所有目录
     * @param $items
     * @param string $id
     * @param string $pid
     * @param string $son
     * @return array
     */
    static function GetcatAll($items, $id = 'id', $pid = 'pid', $son = 'children'){

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



    /**
     * 文件大小判定
     * @param $size
     * @return string
     */
    static function formatBytes($size){

        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for($i = 0; $size >= 1024 && $i < 4; $i ++)
            $size /= 1024;
        return round($size, 2) . $units[ $i ];
    }

}
