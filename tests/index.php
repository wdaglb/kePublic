<?php
// +----------------------------------------------------------------------
// | KE扩展
// +----------------------------------------------------------------------
// | Author: King east <1207877378@qq.com>
// +----------------------------------------------------------------------

ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../vendor/autoload.php';
/*
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
*/

echo '<p>手机号验证</p>';

// $phone = ['18377700000', '2345649687'];
$phone = '2345649687';

var_dump(check_mobile($phone));


echo '<p>cache</p>';
$cache = new \ke\driver\Redis([]);

use ke\Cache;
Cache::init();

Cache::set('s', '无设置');
var_dump(Cache::get('s'));

Cache::tag('test')->set('s', '测试标签');
var_dump(Cache::tag('test')->get('s'));


Cache::tag('test')->prefix('test')->set('s', '前缀');
var_dump(Cache::tag('test')->prefix('test')->get('s'));


Cache::tag('test')->prefix('test')->set('s', '前缀');
var_dump(Cache::tag('test')->prefix('test')->get('s'));

Cache::clear('test');





var_dump(Cache::tag('test')->prefix('test')->get('s'));

