<?php


namespace Alan\SwoftWechat\Interfaces;


interface IAccessToken
{
    /**
     * 获取Token
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * 获取Token过期时间
     * @return int
     */
    public function getExpireAt(): int;

    /**
     * @param string $appId
     * @param array $args
     * @return self
     */
    public static function instance(string $appId, ...$args);
}