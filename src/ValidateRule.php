<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


class ValidateRule
{
    /**
     * 验证身份证正确性
     * @return boolean|string
     */
    public static function cardid($v)
    {
        if (strlen($v) !== 18) {
            return '身份证格式错误';
        }
        $code = substr($v, 0, 17);
        $key = substr($v, -1);

        function sum($Ci) {
            $vi = [7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2]; // 加权因子
            $li = [];
            for ($i = 0; $i < 17; $i++) {
                $li[] = substr($Ci, $i, 1) * $vi[$i];
            }
            return array_sum($li);
        }
        $Ys = '10X98765432';
        $Y = sum($code) % 11;

        return substr($Ys, $Y, 1) === $key ? true : '身份证格式错误';
    }

}