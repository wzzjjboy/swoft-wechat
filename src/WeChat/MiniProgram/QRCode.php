<?php


namespace Alan\SwoftWechat\WeChat\MiniProgram;


use Alan\SwoftWechat\Interfaces\IWechatHandler;
use Alan\SwoftWechat\WeChat\BaseWeChat;

class QRCode extends BaseWeChat implements IWechatHandler
{

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return "app_code";
    }

    /**
     * 适用于需要的码数量较少的业务场景
     * @param $appId
     * @param string $path
     * @param int $width
     * @param array $line_color
     * @return array
     */
    public function getLimit($appId, string $path, $width = 650, array $line_color = [])
    {
        $param = [
            $path,
            [
                'width' => $width,
                'line_color' => $line_color
            ]
        ];
        $response = $this
            ->withParam($param)
            ->withAppId($appId)
            ->withMethod("get")
            ->withResponseCallback(function ($response) {
                return self::ResponseCallback($response);
            })
            ->handler();
        return $response;
    }

    /**
     * 适用于需要的码数量极多，或仅临时使用的业务场景
     * @param string $appId
     * @param string $scene
     * @param string $page
     * @param int $width
     * @return array
     */
    public function getUnlimit(string $appId, string $scene, $page = '', $width = 600)
    {
        $param = [
           $scene,
           [
               'page'  => $page,
               'width' => $width
           ]
        ];
        return $this
            ->withParam($param)
            ->withAppId($appId)
            ->withMethod("getUnlimit")
            ->withResponseCallback(function ($response) {
               return self::ResponseCallback($response);
            })
            ->handler();
    }

    /**
     * @param \EasyWeChat\Kernel\Http\StreamResponse $response
     */
    public static function ResponseCallback($response)
    {
        $contentType = $response->getHeader("Content-Type")[0];
        if (false !== stripos($contentType, 'application/json')){
            return json_decode($response->getBodyContents(), true);
        }
        return base64_encode($response->getBodyContents());
    }


}