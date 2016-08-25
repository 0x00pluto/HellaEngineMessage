<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/28
 * Time: 下午5:13
 */

namespace hellaEngine\RPCMessage;


    /**
     * Class RPCMessage struct
     * @package HellaEngine\RPCMessage
     */
/**
 * Class RPCMessage
 * @package HellaEngine\RPCMessage
 */
class RPCMessage
{
    /**
     * normal Message Uncompressed
     *
     */
    const MESSAGE_TYPE_DATA = 0;

    /**
     * normal Message Compress With Gzip
     */
    const MESSAGE_TYPE_GZIP = 1;

    /**
     * error Message Type
     *
     */
    const MESSAGE_TYPE_ERROR = 2;


    /**
     * MessageHeader
     */
    const MessageData_Head = 'header';
    /**
     * MessageBody
     */
    const MessageData_Body = 'data';


    /**
     * 大版本号
     */
    const Msg_HeadKey_verMajor = "verMajor";
    /**
     * 小版本号
     */
    const Msg_HeadKey_verMin = "verMin";

    /**
     * 消息类型
     */
    const Msg_HeadKey_MessageType = 'msgType';


    /**
     * RPC 函数名称
     */
    const Msg_RPC_FunctionName = 'cmd';
    /**
     * RPC 消息序号
     */
    const Msg_RPC_FunctionId = 'cmdid';
    /**
     * RPC 消息参数
     */
    const Msg_PPC_FunctionParams = 'params';

    /**
     * MessageData
     * @var array
     */
    private $_messageData = [
        self::MessageData_Head => [],
        self::MessageData_Body => []
    ];


    /**
     * 设置大版本号
     * @param $value
     * @return RPCMessage
     */
    public function setVerMajor($value)
    {
        return $this->setMessageHeaderProperty(self::Msg_HeadKey_verMajor, $value);
    }

    /**
     * 设置小版本号
     * @param $value
     * @return RPCMessage
     */
    public function setVerMin($value)
    {
        return $this->setMessageHeaderProperty(self::Msg_HeadKey_verMin, $value);
    }

    /**
     * 设置消息类型
     * @param $value
     * @return RPCMessage
     */

    public function setMessageType($value)
    {
        return $this->setMessageHeaderProperty(self::Msg_HeadKey_MessageType, $value);
    }

    /**
     * 设置消息头属性
     * @param $key
     * @param $value
     * @return RPCMessage
     */
    public function setMessageHeaderProperty($key, $value)
    {
        $header = $this->getMessageHeader();
        $header[$key] = $value;
        return $this->setMessageHeader($header);
    }


    /**
     * 设置消息头
     * @param array $header
     * @return $this
     */
    protected function setMessageHeader(array $header)
    {
        $this->_messageData[self::MessageData_Head] = $header;
        return $this;
    }

    /**
     * 获取消息头
     * @return array
     */
    protected function getMessageHeader()
    {

        return isset($this->_messageData[self::MessageData_Head]) ? $this->_messageData[self::MessageData_Head] : [];
    }

    /**
     * 获取消息头部属性
     * @param $key
     * @param null $defaultValue
     * @return null
     */
    public function getMessageHeaderProperty($key, $defaultValue = null)
    {
        $header = $this->getMessageHeader();
        if (isset($header, $key)) {
            return $header[$key];
        }
        return $defaultValue;
    }

    /**
     * 设置消息体属性
     * @param $key
     * @param $value
     * @return RPCMessage
     */
    public function setMessageBodyProperty($key, $value)
    {
        $messageBody = $this->getMessageBody();
        $messageBody[$key] = $value;
        return $this->setMessageBody($messageBody);
    }

    /**
     * 获取消息体属性
     * @param $key
     * @param null $defaultValue
     * @return null
     */
    public function getMessageBodyProperty($key, $defaultValue = null)
    {
        $messageBody = $this->getMessageBody();
        if (isset($messageBody[$key])) {
            return $messageBody[$key];
        }
        return $defaultValue;
    }

    /**
     * 设置消息体
     * @param array $messageBody
     * @return $this
     */
    protected function setMessageBody(array $messageBody)
    {
        $this->_messageData [self::MessageData_Body] = $messageBody;
        return $this;
    }

    /**
     * 获取消息体
     * @return array
     */
    public function getMessageBody()
    {
        return isset($this->_messageData[self::MessageData_Body]) ? $this->_messageData[self::MessageData_Body] : [];
    }

    /**
     * 序列化到数组
     * @return array
     */
    public function toArray()
    {
        return $this->_messageData;
    }

    /**
     * 通过数组反序列话
     * @param array $arr
     */
    public function fromArray(array $arr)
    {
        $this->_messageData = $arr;
    }


    /**
     * 普通消息创建
     * @param array $messageBody
     * @param int $verMajor
     * @param int $verMin
     * @param int $messageType
     * @return RPCMessage
     */
    static function create($messageBody = [], $verMajor = 1, $verMin = 1, $messageType = self::MESSAGE_TYPE_DATA)
    {
        $message = new self();
        $message->setVerMajor($verMajor)
            ->setVerMin($verMin)
            ->setMessageType($messageType)
            ->setMessageBody($messageBody);
        return $message;
    }

    /**
     * 通过RPC创建消息
     * @param $RpcFunctionName
     * @param array $functionParams
     * @param int $functionId
     * @return RPCMessage
     */
    static function createWithRpc($RpcFunctionName, array $functionParams = [], $functionId = 1)
    {
        $message = self::create([]);
        $message->setMessageBodyProperty(self::Msg_RPC_FunctionName, $RpcFunctionName)
            ->setMessageBodyProperty(self::Msg_RPC_FunctionId, $functionId)
            ->setMessageBodyProperty(self::Msg_PPC_FunctionParams, $functionParams);
        return $message;
    }

    /**
     * 通过数组创建消息
     * @param array $arr
     * @return RPCMessage
     */
    static function createWithArray(array $arr)
    {
        $message = new self();
        $message->fromArray($arr);
        return $message;
    }


    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->getMessageBody();
    }

    public function getFunctionName()
    {
        return $this->getMessageBodyProperty(self::Msg_RPC_FunctionName);
    }

    public function getFunctionParams()
    {
        return $this->getMessageBodyProperty(self::Msg_PPC_FunctionParams);
    }


    /**
     * 加密形成网络消息
     * @return string
     */
    public function encode()
    {
        return RPCMessageEncode::encodeMessages([$this]);
    }

    /**
     * @param $netMessage
     * @return RPCMessage[]|null
     */
    static public function decode($netMessage)
    {
        return RPCMessageEncode::decodeMessages($netMessage, true);
    }

    /**
     * 返回RPC调用
     * @return RPCMessageReturn
     */
    public function toRpcMessageReturn()
    {
        return RPCMessageReturn::createWithRPCMessage($this);
    }


}