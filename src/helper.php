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



if (!function_exists('get_parent_list')) {

    /**
     * 深度优先遍历查找一个树形结构中，某个节点的所在位置
     * @param array $list 遍历的数组
     * @param string $id 遍历内容
     * @param string $pk 遍历字段名
     * @param string $pkn 上级关联字段
     * @return array
     */
    function get_parent_list($list, $id, $pk = 'id', $pkn = 'pid', $pathKeys = [])
    {
        if (count($pathKeys) == 0)
            $pathKeys[] = $id;
        foreach ($list as $g) {
            if ($g[$pk] == $id) {
                if ($g[$pkn] == 0) {
                    return $pathKeys;
                }
                array_unshift($pathKeys, $g[$pkn]);
                $pathKeys = get_parent_list($list, $g[$pkn], $pk, $pkn, $pathKeys);
            }
        }
        return $pathKeys;
    }
}



if (!function_exists('get_children_list')) {

    /**
     * 获取父节点的所有子节点id
     * @param array $list 遍历的数组
     * @param string $id 遍历内容
     * @param string $pk 遍历字段名
     * @param string $pkn 上级关联字段
     * @return array
     */

    function get_children_list($array, $id, $pk = 'id', $pkn = 'pid')
    {
        $arr = array($id);
        foreach ($array as $v) {
            if ($v[$pkn] == $id) {
                $arr[] = $v[$pk];
                $arr = array_unique(array_merge($arr, get_children_list($array, $v[$pk], $pk, $pkn)));
            };
        };
        return $arr;
    }
}
