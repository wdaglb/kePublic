<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


use ke\driver\Redis;

/**
 * @method static Redis tag(string $tagname)
 * @method static Redis prefix(string $name)
 * @method static Cache get(string $key)
 * @method static Cache set(string $key, mixed $value)
 * @method static Cache inc(string $key, int $value)
 * @method static Cache dec(string $key, int $value)
 * @method static Cache ttl(string $key)
 * @method static Cache rm(string $key)
 * @method static Cache exists(string $key)
 * @method static Redis clear(string $tagname)
 */
class Cache
{
    /**
     * @var \Redis
     */
    private static $handle;

    public static function init($option = [])
    {
        self::$handle = new Redis($option);
    }


    public static function __callStatic($name, $arguments)
    {
        $method = strtolower($name);

        return call_user_func_array([self::$handle, $method], $arguments);
    }

}