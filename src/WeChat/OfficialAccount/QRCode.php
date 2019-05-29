<?php


namespace  Alan\SwoftWechat\WeChat\OfficialAccount;

use Alan\SwoftWechat\WeChat\BaseWeChat;
use Alan\SwoftWechat\Interfaces\IWechatHandler;


class QRCode extends BaseWeChat implements IWechatHandler
{
    /**
     * 创建临时二维码
     * @param string $appId
     * @param integer|string $sceneValue 场景值ID 如果为整性最大值了100000，如果不字符串长度限制为1到64
     * @return array
     */
    public function temporary(string $appId, $sceneValue)
    {
        $param = [
            $sceneValue
        ];
        return $this
            ->withParam($param)
            ->withAppId($appId)
            ->withMethod("temporary")
            ->handler();
    }

    /**
     * 创建永久二维码
     * @param string $appId
     * @param integer|string $sceneValue 场景值ID 如果为整性最大值了100000，如果不字符串长度限制为1到64
     * @param null $expireSeconds
     * @return array
     */
    public function forever(string $appId, $sceneValue, $expireSeconds = null)
    {
        $param = [
            $sceneValue,
            $expireSeconds
        ];
        return $this
            ->withParam($param)
            ->withAppId($appId)
            ->withMethod("forever")
            ->handler();
    }

    /**
     * 获取二维码网址
     * @param string $appId
     * @param string $ticket
     * @return array
     */
    public function url(string $appId, $ticket)
    {
        $param = [
            $ticket
        ];

        return $this
            ->withParam($param)
            ->withAppId($appId)
            ->withMethod("url")
            ->handler();
    }


    /**
     * @return string
     */
    public function getProperty(): string
    {
        return "qrcode";
    }
}