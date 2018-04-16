<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke\driver;


class Redis
{
    private $option = [
        'host'=>'127.0.0.1',
        'port'=>6379,
        'select'=>0
    ];

    private $handle;

    private $tag = '';

    private $prefix = '';

    public function __construct($option)
    {
        $this->option = array_merge($this->option, $option);
        $this->handle = new \Redis();

        $this->handle->connect($this->option['host'], $this->option['port']);

        $this->handle->select($this->option['select']);
    }


    /**
     * 设置标签
     * @param array ...$name
     * @return Redis
     */
    public function tag(...$name)
    {
        $this->tag = implode('_', $name);
        return $this;
    }


    /**
     * 设置前缀
     * @param array ...$name
     * @return Redis
     */
    public function prefix(...$name)
    {
        $this->prefix = implode('_', $name);
        return $this;
    }

    /**
     * 获取缓存标识
     * @param string &$k
     */
    private function getKey(&$k)
    {
        $k = md5($this->prefix . '_' . $k);
        if ($this->tag != '') {
            $this->handle->rPushx('__tag_list__' . $this->tag . '__', $k); // 加入标识到标签列表
        }
    }

    /**
     * 清空当前数据库
     * @param string $tag 设置标签名则清空标签下的数据
     */
    public function clear($tag = '')
    {
        if ($tag != '') {
            $this->handle->flushDB();
        } else {
            $key = '__tag_list__' . $tag . '__';
            $total = $this->handle->lLen($key);
            for($i = 0; $i < $total; $i++) {
                $k = $this->handle->lPop($key);
                $this->rm($k);
            }
            $this->rm($key);
        }
    }

    /**
     * 写入
     * @param string $key 键名
     * @param mixed $value 值
     * @param int $expire 有效时间,0为永久
     * @return bool
     */
    public function set($key, $value, $expire = 7200)
    {
        $this->getKey($key);

        if ($expire === 0) {
            return $this->handle->set($key, $value);
        } else {
            return $this->handle->setex($key, $expire, $value);
        }
    }


    /**
     * 判断key是否存在
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return $this->handle->exists($key);
    }


    /**
     * 读取
     * @param string $key 键名
     * @return null|string
     */
    public function get($key)
    {
        $this->getKey($key);
        if ($this->exists($key)) {
            return $this->handle->get($key);
        } else {
            return null;
        }
    }


    /**
     * 自增
     * @param string $key 键名
     * @param int $value 增长值
     * @return bool|int
     */
    public function inc($key, $value = 1)
    {
        if ($value == 1) {
            return $this->handle->incr($key);
        } else {
            return $this->handle->incrBy($key, $value);
        }
    }


    /**
     * 自减
     * @param string $key 键名
     * @param int $value 减少值
     * @return bool|int
     */
    public function dec($key, $value = 1)
    {
        if ($value == 1) {
            return $this->handle->decr($key);
        } else {
            return $this->handle->decrBy($key, $value);
        }
    }


    /**
     * 得到key的生存时间
     * @param string $key
     * @return int
     */
    public function ttl($key)
    {
        return $this->handle->ttl($key);
    }


    /**
     * 移除到期的key
     * @param string $key
     * @return bool
     */
    public function persist($key)
    {
        return $this->handle->persist($key);
    }




    /**
     * 删除指定key
     * @param string|array $key
     * @return int
     */
    public function rm($key)
    {
        return $this->handle->delete($key);
    }

}