<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


use ke\driver\DriverRedis;

/**
 * @method static DriverRedis select(int $index)
 * @method static DriverRedis tag(string $tagname)
 * @method static DriverRedis prefix(string $name)
 * @method static Cache get(string $key)
 * @method static Cache set(string $key, mixed $value)
 * @method static Cache inc(string $key, int $value)
 * @method static Cache dec(string $key, int $value)
 * @method static Cache ttl(string $key)
 * @method static Cache rm(string $key)
 * @method static Cache exists(string $key)
 * @method static DriverRedis clear(string $tagname)
 */
class Cache
{
    /**
     * @var \Redis
     */
    private static $handle;

    public static function init($option = [])
    {
        self::$handle = new DriverRedis($option);
    }


    public static function __callStatic($name, $arguments)
    {
        $method = strtolower($name);

        return call_user_func_array([self::$handle, $method], $arguments);
    }

}