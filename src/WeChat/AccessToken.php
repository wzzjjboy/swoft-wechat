<?php


namespace  Alan\SwoftWechat\WeChat;

use Alan\SwoftWechat\Interfaces\IAccessToken as IAccessToken;

class AccessToken implements IAccessToken
{
    protected static $instance;

    /**
     * @var string
     */
    private $appId;
    private $accessToken;
    private $ttl;

    public function __construct(...$args)
    {
        $this->appId = $args[0];
        if (isset($args[1])){
            $this->accessToken = $args[1];
        }
        if (isset($args[2])){
            $this->ttl = $args[2];
        }
    }

    /**
     * 获取Token
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * 获取Token过期时间
     * @return int
     */
    public function getExpireAt(): int
    {
        return $this->ttl;
    }

    public static function instance(string $appId, ...$args)
    {
        if (empty(self::$instance)){
            $p = [$appId];
            if ($args){
                $p = array_merge($p, $args);
            }
            self::$instance = new self(...$p);
        }

        return self::$instance;
    }
//    public function getAccessTokenKey($appid)
//    {
//        return $appid . '_access_token';
//    }
//
//
//    public function getAccessTokenExpiresKey($appId)
//    {
//        return $this->getAccessTokenKey($appId) . '_expires_in';
//    }
}