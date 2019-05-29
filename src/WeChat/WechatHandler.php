<?php


namespace  Alan\SwoftWechat\WeChat;

use EasyWeChat\Factory;
use Alan\SwoftWechat\Interfaces\IAccessToken;
use Alan\SwoftWechat\Interfaces\IWechatHandler;

/**
 * @Bean("wechat")
 * @package App\Helper
 */
class WechatHandler extends Factory
{
    /**
     * @var array
     */
    private static $accountList;
    /**
     * @var IAccessToken
     */
    private static $accessToken;
    /**
     * @var array
     */
    private static $accessTokenCnf;

    /**
     * @param $cnf
     * @throws \Exception
     */
    public static function init($cnf)
    {
        isset($cnf['accounts']) and self::$accountList = $cnf['accounts'];
        isset($cnf['accessToken']) and self::setAccessTokenCnf($cnf['accessToken']);
    }

    private static function setAccessTokenCnf($accessToken)
    {
        if (is_array($accessToken)){
            if (count($accessToken) !== 3){
                throw new \Exception("无效的access token 配置");
            }
            list($appId, $accessToken, $ttl) = $accessToken;
            self::$accessTokenCnf[$appId] = [$accessToken, $ttl];
        } elseif (is_callable($accessToken)){
            self::$accessTokenCnf = $accessToken;
        }
    }

    /**
     * @param string $appId
     * @return mixed
     * @throws \Exception
     */
    public static function getAccessToken($appId)
    {
        if (self::$accessToken && self::$accessToken->getExpireAt() <= time()){
            return self::$accessToken;
        }
        if (is_array(self::$accessTokenCnf) && isset(self::$accessTokenCnf[$appId])){
            list($accessToken, $ttl) = self::$accessTokenCnf;
        }elseif (is_callable(self::$accessTokenCnf)){
            list($accessToken, $ttl) = call_user_func(self::$accessTokenCnf, $appId);
        } else {
            throw new \Exception("获取access token 失败");
        }

        return self::$accessToken = AccessToken::instance($appId, $accessToken, $ttl);
    }



    public static function getAccountCnf($appId)
    {
        if (!isset(self::$accountList[$appId]) && self::$accountList[$appId]){
            return [];
        }
        $secret = self::$accountList[$appId];
        return [
            'app_id' => $appId,
            'secret' => $secret,
            'response_type' => 'array',
        ];
    }

    /**
     * @param IWechatHandler $wechatHandler
     * @return mixed
     * @throws \Exception
     */
    public function handler(IWechatHandler $wechatHandler)
    {
        $type     = $wechatHandler->getType();
        $property = $wechatHandler->getProperty();
        $method   = $wechatHandler->get("method");
        $params   = $wechatHandler->get("param");
        $appId    = $wechatHandler->get("appId");
        if (!in_array($type, [
            'miniProgram',
            'officialAccount',
        ])){
            throw new \Exception("不支持的类型:{$type}");
        }
        if(empty($appId)){
            throw new \Exception("appid不能为空");
        }

        $config = self::getAccountCnf($appId);
        if(!$config){
            throw new \Exception("未配置appid:{$appId}");
        }
//        var_dump($type, $property, $method, $params, $appId, $config);
        $handler = self::$type($config);

//        $redis = new Redis();
//        $redis->connect('10.105.201.137', 7001);
//        $redis->auth("pUD85cOEvX22");
//        $redis->select(0);
//
//        // 创建缓存实例
//        $cache = new RedisCache($redis);
//
//        // 替换应用中的缓存
//        $handler->rebind('cache', $cache);

        if(!property_exists(get_class($handler), $property)){
//            throw new \Exception("不支持的property:{$property}");
        }
        if(!method_exists($handler->$property, $method)){
            throw new \Exception("method:{$method}");
        }

//        var_dump($wechatHandler);exit;

//
//        if (($token = $wechatHandler->get("token")) && $token instanceof IAccessToken){
//            $handler->access_token->setToken($token->getAccessToken(), $token->getExpireAt());
////        }


//        $accessToken = self::getAccessToken($appId);
//        var_dump($accessToken);exit;
        if (self::$accessTokenCnf){
            /** @var IAccessToken $accessToken */
            $accessToken = self::getAccessToken($appId);
//            var_dump($accessToken->getAccessToken(), $accessToken->getExpireAt());exit;
            $handler->access_token->setToken($accessToken->getAccessToken(), $accessToken->getExpireAt());
        }

        return $handler->$property->$method(...$params);
    }
}