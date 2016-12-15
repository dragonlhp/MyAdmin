<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 16/10/31
 * 功能说明: 测试工具,没有实际意义
 */

namespace app\services\common;


class DumpCode
{
    /**
     * 功能:数组调试函数
     * 参数:数组变量或数组
     */
    static function P($var, $echo = true, $label = null, $strict = true)
    {

        $str1 = '<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';

        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
                $output = $str1 . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo($output);

            return null;
        } else
            return $output;
    }

    static function classname($classname = null)
    {
        $arr = explode('\\', get_class($classname));
        $arr = substr(end($arr), 0, -10);
        $namearr = self::P($arr);
        return $namearr;
    }

    static function getMenu($items, $id = 'id', $pid = 'pid', $son = 'children')
    {
        $tree = array();
        $tmpMap = array();

        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                if ($item[$pid] == 0) {
                    $tree[] = &$tmpMap[$item[$id]];
                }
            }
        }

        return $tree;

    }
}