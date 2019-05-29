<?php


namespace  Alan\SwoftWechat\WeChat\MiniProgram;

use Alan\SwoftWechat\WeChat\BaseWeChat;
use Alan\SwoftWechat\Interfaces\IWechatHandler;

/**
 * Class TemplateMessage
 */
class TemplateMessage extends BaseWeChat implements IWechatHandler
{
    /**
     * @param $appId
     * @param string $touser 消息接收者
     * @param string $template_id 模板ID
     * @param string $form_id 表单ID
     * @param string $page 小程序路径
     * @param array $data 数据内容 ["keyword1"=>"value1","keyword2"=>"value2" ...]
     * @return mixed
     */
    public function send($appId, $touser, $template_id, $form_id, $page, $data = [])
    {
        $param = [
            'touser'        => $touser,
            'template_id'   => $template_id,
            'page'          => $page,
            'form_id'       => $form_id,
            'data'          => $data
        ];

        return $this
            ->withMethod('send')
            ->withAppId($appId)
            ->withParam($param)
            ->handler();
    }

    public function getProperty(): string
    {
        return "template_message";
    }
}