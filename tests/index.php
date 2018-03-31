<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------

require '../vendor/autoload.php';
try {
    $http = new \ke\HttpRequest('http://huawei.cysmsc.cn');
    $http->setResponse();
    $ret = $http->send();
    var_dump($ret);
}catch (Error $e) {
    var_dump($e->getMessage());
}catch (Exception $e) {
    var_dump($e->getMessage());
}
