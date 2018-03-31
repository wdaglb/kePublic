<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


class HttpRequest
{

    // 当前实例
    private $cu;
    // 超时时间
    private $timeout = 10;
    /**
     * 构造函数
     * HttpRequest constructor.
     */
    public function __construct($url = '')
    {
        $this->cu = curl_init($url);
        if (!$this->cu) {
            throw new Error('curl创建失败');
        }
    }

    /**
     * 实例销毁时关闭curl
     */
    public function __destruct()
    {
        curl_close($this->cu);
        $this->cu = null;
    }

    /**
     * 是否返回变量
     * @param int $bool
     * @return $this
     */
    public function setResponse($bool = 1)
    {
        curl_setopt($this->cu, CURLOPT_RETURNTRANSFER, intval($bool));
        return $this;
    }

    /**
     * 设置伪装agent
     * @param string $str
     * @return $this
     */
    public function agent($str = '')
    {
        curl_setopt($this->cu, CURLOPT_USERAGENT, $str ? $str : 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        return $this;
    }

    /**
     * 设置ssl证书
     * @param string $cert
     * @return $this
     */
    public function ssl($cert = '')
    {
        if (is_callable($cert)) {
            call_user_func($cert, $this->cu);
            return $this;
        } else {
            if (empty($cert)) {
                curl_setopt($this->cu, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
                curl_setopt($this->cu, CURLOPT_SSL_VERIFYHOST, FALSE);
            } else {
                // 严格认证
                curl_setopt($this->cu, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书
                curl_setopt($this->cu, CURLOPT_CAINFO, $cert); // CA根证书（用来验证的网站证书是否是CA颁布）
                curl_setopt($this->cu, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
            }
        }
        return $this;
    }

    /**
     * 设置POST
     * @param $data
     * @return $this
     */
    public function post($data)
    {
        curl_setopt($this->cu, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($this->cu, CURLOPT_POST, 1);
        curl_setopt($this->cu, CURLOPT_POSTFIELDS, $data);
        return $this;
    }

    /**
     * 设置超时时间
     * @param $time
     * @return $this
     */
    public function setExpireTime($time)
    {
        $this->timeout = $time;
        return $this;
    }

    /**
     * 设置请求url
     * @param $url
     * @param array $query
     * @return $this
     */
    public function url($url, array $query = [])
    {
        curl_setopt($this->cu, CURLOPT_URL, $url . (empty($query) ? '' : '?' . http_build_query($query)));
        return $this;
    }

    /**
     * 执行请求
     * @return mixed
     */
    public function send()
    {
        curl_setopt($this->cu, CURLOPT_TIMEOUT, $this->timeout);
        return curl_exec($this->cu);
    }


}