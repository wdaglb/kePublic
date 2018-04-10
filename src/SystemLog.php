<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;



class SystemLog
{
    private static $root;
    private static $path = '';

    public static function init($root)
    {
        self::$root = $root;
    }

    private static function setPath($type)
    {
        if (!self::$root)
            throw new \Exception('root未设置');
        self::$path = self::$root . 'systemlog/' . $type . '/' . date('Ymd') . '/';
        if (!is_dir(self::$path))
            mkdir(self::$path, 0777, true);
        self::$path .=  date('H') . '.log';
    }


    public static function write($type, $content)
    {
        self::setPath($type);
        $str = (is_array($content) ? json_encode($content) : $content);
        file_put_contents(self::$path, $str . "\r\n===========================================\r\n\r\n", FILE_APPEND);
    }

}