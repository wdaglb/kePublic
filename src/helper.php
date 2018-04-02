<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


if (!function_exists('check_mobile')) {
    /**
     * 匹配指定字符串或数组里的手机号是否准确
     * @param $input
     * @return bool
     */
    function check_mobile($input) {
        $ruleCheck = function ($mobile) {
            return preg_match('/^1\d{10}$/', $mobile);
        };
        if (is_array($input)) {
            foreach ($input as $m) {
                if (!call_user_func($ruleCheck, $m)) {
                    return false;
                }
            }
            return true;
        } else {
            return boolval(call_user_func($ruleCheck, $input));
        }
    }
}


if (!function_exists('get_order_id')) {
    /**
     * 生成订单ID
     * @return string
     */
    function get_order_id()
    {
        $s = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        return $s[date('Y') - 2018] . str_pad(mt_rand(0,999999), 6, '0', STR_PAD_LEFT) . $_SERVER['REQUEST_TIME'];
    }
}

if (!function_exists('list_to_tree')) {
    /**
     * 数组树形化
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     */
    function list_to_tree($list, $pk='id', $pid = 'pid', $child = 'children', $root = 0) {
        $tree = array();
        if (is_array($list)) {
            $refer = array();

            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }

            foreach ($list as $key => $data) {
                $parantId = $data[$pid];

                if ($root == $parantId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parantId])) {
                        $parent = &$refer[$parantId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }

        return $tree;
    }
}

if (!function_exists('rand_letter')) {
    /**
     * 生成随机字符串
     * @param $length
     * @return string
     */
    function rand_letter($length)
    {
        $str='abcdefghijklmnopqrstuvwxyz0123456789';
        $max=strlen($str);
        $ret='';

        for($i=0;$i<$length;$i++){
            $ret.=substr($str,mt_rand(1,$max),1);
        }
        return $ret;
    }
}
