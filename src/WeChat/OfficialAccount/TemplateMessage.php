<?php


namespace  Alan\SwoftWechat\WeChat\OfficialAccount;

use Alan\SwoftWechat\WeChat\BaseWeChat;
use Alan\SwoftWechat\Interfaces\IWechatHandler;

/**
 * Class TemplateMessage
 */
class TemplateMessage extends BaseWeChat implements IWechatHandler
{
    /**
     * @param string $appId
     * @param string $touser 消息接收者
     * @param string $template_id 模板ID
     * @param array $data 数据内容 ["first"=>["value"=>"xxx", "color"=>"xxx"],"keyword1"=>["value"=>"xxx", "color"=>"xxx"] ...]
     * @param string $url URL地址
     * @param array $miniprogram 小程序相关信息 ["appid":"xxxx","pagepath":"xxxx"]
     * @return mixed
     */
    public function send($appId, $touser, $template_id, $data = [], $url = '', $miniprogram = [])
    {
        $param = [
            'touser' => $touser,
            'template_id' => $template_id,
            'url' => $url,
            'data' => $data,
            'miniprogram' => $miniprogram,
        ];
        return $this
            ->withParam($param)
            ->withAppId($appId)
            ->withMethod("send")
            ->handler();
    }


    public function getProperty(): string
    {
        return "template_message";
    }
}