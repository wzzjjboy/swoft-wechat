<?php


namespace Alan\SwoftWechat\Interfaces;


/**
 * 微信运维时所需要的实现的配置接口
 * Interface Setting
 * @package App\Lib\TarsMessage\WeChat
 */
interface IWechatType
{
    /**
     * @return string
     */
    public function getType(): string ;

    /**
     * @return string
     */
    public function getProperty() : string ;

}