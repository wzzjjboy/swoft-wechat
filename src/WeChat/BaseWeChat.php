<?php


namespace  Alan\SwoftWechat\WeChat;



use Alan\SimpleResponse\SResponse;
use Alan\SwoftWechat\Interfaces\IWechatHandler;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use Alan\SwoftWechat\Interfaces\IAccessToken;

abstract class BaseWeChat implements IWechatHandler
{

    /**
     * @var WechatHandler
     */
    protected $wechat;

    protected $_cnf;

    public function __construct()
    {
        if(empty($this->wechat)){
            $this->wechat = new WechatHandler();
        }
    }
    /**
     * @var array
     */
    public $data;

    /**
     * @return array
     */
    protected function handler()
    {
        try{
            /** @var \EasyWeChat\Kernel\Http\StreamResponse $response */
            $response = $this->wechat->handler($this);
            if (($responseCallback = $this->get("responseCallback")) && is_callable($responseCallback)){
                $response = call_user_func($responseCallback, $response);
            }
            if (isset($response['errcode']) && $response['errcode']){
                return SResponse::fail($response['errmsg'] ?? null);
            }
            return SResponse::ok($response);
        }catch (InvalidArgumentException $invalidArgumentException){
            return SResponse::fail($invalidArgumentException->getMessage());
        }catch (\Exception $exception){
            return SResponse::fail([
                'getMessage' => $exception->getMessage(),
                'getFile'  =>$exception->getFile(),
                'getLine'  =>$exception->getLine(),
            ]);
        }
    }

    public function withParam(array $param)
    {
        $this->set("param", $param);
        return $this;
    }

    public function withAppId(string $appId){
        $this->set("appId", $appId);
        return $this;
    }

    public function withMethod(string $method){
        $this->set("method", $method);
        return $this;
    }

    public function withAccessToken(IAccessToken $accessToken){
        $this->set("token", $accessToken);
        return $this;
    }

    public function withResponseCallback(\Closure $closure)
    {
        $this->set("responseCallback", $closure);
        return $this;
    }

    public function set(string $key, $val)
    {
        $this->_cnf[$key] = $val;
    }

    public function get(string  $key){
        return $this->_cnf[$key] ?? null;
    }

    /**
 * @return string
 */
    public function getType(): string
    {
        $namespace = get_called_class();
        if (stripos($namespace, "miniprogram")){
            return "miniProgram";
        } else {
            return "officialAccount";
        }
    }
}