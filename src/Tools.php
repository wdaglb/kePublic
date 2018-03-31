<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


class Tools
{
    /**
     * 通配符匹配
     * @param $li
     * @param $str
     * @return bool
     */
    public static function match(array $li, $str)
    {
        if (empty($li)) {
            return false;
        }
        $replace = function ($str) {
            return '/' . str_replace(['/', '*'], ["\/", '.+?'], $str) . '/';
        };

        foreach ($li as $g) {
            if (preg_match($replace($g), $str)) {
                return true;
            }
        }
        return false;
    }

}