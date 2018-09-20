<?php
// +----------------------------------------------------------------------
// | Jwt KE 接口令牌数据
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------


namespace ke;


/**
 * Class Jwt 无状态权限控制
 * @package ke
 */
class Jwt
{
    private $info = [];

    private $secret = '';

    private $tok = '';

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    private function base64UrlEncode($r)
    {
        return urlencode(base64_encode($r));
    }

    private function base64UrlDecode($r)
    {
        return base64_decode(urldecode($r));
    }

    /**
     * 取原令牌数据
     * @return string
     */
    public function tok()
    {
        return $this->tok;
    }

    /**
     * 获取令牌内info数据
     * @return array
     */
    public function getData($key = null)
    {
        if ($key) {
            return isset($this->info[$key]) ? $this->info[$key] : null;
        }
        return $this->info;
    }


    /**
     * 验证令牌是否匹配
     * @param string $token
     * @return bool
     */
    public function check($token)
    {
        if (empty($token)) {
            return false;
        }
        $this->tok = $token;
        $this->info = [];
        try {
            list($alg, $info, $sign) = explode('.', $token);
            $alg = json_decode($this->base64UrlDecode($alg), true);
            $info = json_decode($this->base64UrlDecode($info), true);

            if (empty($alg['alg']) || empty($alg['typ'])) {
                return false;
            }
            // 不是JWT直接false
            if ($alg['typ'] !== 'JWT') {
                return false;
            }
            $algs = $alg;
            $infos = $info;
            // 重加密匹配
            ksort($alg);
            ksort($info);
            $data = [];
            $data[] = $this->base64UrlEncode(json_encode($alg));
            $data[] = $this->base64UrlEncode(json_encode($info));
            $signs = hash_hmac($algs['alg'], implode('.', $data), $this->secret);
            if ($sign !== $signs) {
                return false;
            }
            $this->info = $infos;
            if (isset($infos['exp']) && $infos['exp'] < $_SERVER['REQUEST_TIME']) {
                return false;
            }
            return true;
        }catch (\Exception $e) {
            return false;
        }

    }
	
    /**
     * 获取加密后的令牌
     * @param array $info
     * @param int $exp 令牌有效时间
     * @param string $type 加密方式
     * @return string
     */
    public function make(array $info, $exp = 0, $type = 'sha256')
    {
        $alg = [
            'alg'=>$type,
            'typ'=>'JWT'
        ];
        if (isset($info['exp'])) {
            unset($info['exp']);
        }
        if ($exp > 0) {
            $info['exp'] = time() + $exp;
        }
        ksort($alg);
        ksort($info);
        $data[] = $this->base64UrlEncode(json_encode($alg));
        $data[] = $this->base64UrlEncode(json_encode($info));
        $data[] = hash_hmac($type, implode('.', $data), $this->secret);
        return implode('.', $data);
    }


    /**
     * 刷新token
     * @param int $cyc 刷新周期
     * @return string
     */
    public function refresh($cyc = 900)
    {
        // 刷新
        if (isset($this->info['exp']) && ($this->info['exp'] - $_SERVER['REQUEST_TIME']) % $cyc === 0) {
            $this->tok = $this->make($this->info, $this->info['exp']);
            return $this->tok;
        }

        return $this->tok;
    }


}