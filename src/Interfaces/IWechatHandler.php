<?php


namespace Alan\SwoftWechat\Interfaces;


interface IWechatHandler extends IWechatType
{
    /**
     * @param array $param
     * @return $this
     */
    public function withParam(array $param);

    /**
     * @param string $appId
     * @return $this
     */
    public function withAppId(string $appId);

    /**
     * @param string $method
     * @return $this
     */
    public function withMethod(string $method);

    /**
     * @param IAccessToken $accessToken
     * @return $this
     */
    public function withAccessToken(IAccessToken $accessToken);

    /**
     * @param string $key
     * @param $val
     * @return void
     */
    public function set(string $key, $val);

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);
}