<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 16/8/15
 * Time: 下午2:28
 */

namespace hellaEngine\RPCMessage;

/**
 * Class RPCMessageReturn
 * @package hellaEngine\RPCMessage
 */
class RPCMessageReturn
{
    /**
     * 是否成功
     *
     * @var string
     */
    const DBKey_retsucc = "retsucc";

    /**
     * 返回code
     *
     * @var string
     */
    const DBKey_retcode = "retcode";

    /**
     * 返回数据
     *
     * @var string
     */
    const DBKey_retdata = "retdata";

    /**
     * 返回code str解释
     *
     * @var string
     */
    const DBKey_retcode_str = "retcode_str";

    /**
     *
     * 构建消息返回值
     *
     * @param bool $succ
     *            true or false
     * @param int $code
     *            代码
     * @param mixed $data
     *            扩展包含数据
     *
     * @param string $code_string
     * @return RPCMessageReturn
     */
    public static function Ret($succ, $code = 0, $data = null, $code_string = '')
    {
        $succ = boolval($succ);
        $code = intval($code);
        $code_string = strval($code_string);
        $retarr = new self ($succ, $code, $data, $code_string);

        return $retarr;
    }

    /**
     *
     * 构建消息返回值(成功)
     *
     * @param mixed $data
     *            扩展包含数据
     * @param int $code
     *            代码
     *
     * @return RPCMessageReturn
     */
    public static function createSucc($code = 0, $data = null, $code_string = '')
    {
        return RPCMessageReturn::Ret(true, $code, $data, $code_string);
    }

    /**
     *
     * 构建消息返回值(失败)
     *
     * @param mixed $data
     *            扩展包含数据
     * @param int $code
     *            代码
     *
     * @return RPCMessageReturn
     */
    public static function createFail($code = 0, $data = null, $code_string = '')
    {
        return RPCMessageReturn::Ret(false, $code, $data, $code_string);
    }


    private $_data = array();

    function __construct($succ, $code = 0, $data = null, $code_string = '')
    {
        $succ = boolval($succ);
        $code = intval($code);
        $code_string = strval($code_string);
        $this->_data [self::DBKey_retsucc] = $succ;
        $this->_data [self::DBKey_retcode] = $code;
        $this->_data [self::DBKey_retdata] = $data;
        $this->_data [self::DBKey_retcode_str] = $code_string;
    }

    /**
     * 获取返回码
     *
     * @return number
     */
    function get_retcode()
    {
        return intval($this->_data [self::DBKey_retcode]);
    }

    /**
     * 是否成功
     *
     * @return boolean
     */
    function is_succ()
    {
        return $this->get_retsucc();
    }

    /**
     * 是否失败
     *
     * @return boolean
     */
    function is_failed()
    {
        return !$this->is_succ();
    }

    /**
     * 获取是否成功
     *
     * @return boolean
     */
    function get_retsucc()
    {
        return boolval($this->_data [self::DBKey_retsucc]);
    }

    /**
     * 设置失败
     */
    function set_failed()
    {
        $this->_data [self::DBKey_retsucc] = FALSE;
    }

    /**
     * 设置成功
     */
    function set_succ()
    {
        $this->_data [self::DBKey_retsucc] = TRUE;
    }

    /**
     * 获取返回数据
     * @return mixed
     */
    function get_retdata()
    {
        return $this->_data [self::DBKey_retdata];
    }

    function set_retdata($data)
    {
        $this->_data [self::DBKey_retdata] = $data;
    }

    /**
     * 获取返回码说明
     *
     * @return string
     */
    function get_retcode_str()
    {
        return strval($this->_data [self::DBKey_retcode_str]);
    }

    /**
     * 获取原始数组数据
     */
    function toArray()
    {
        return $this->_data;
    }

    /**
     * 通过调用rpc调用返回
     *
     * @param RPCMessage $message
     * @return RPCMessageReturn
     */
    static function createWithRPCMessage(RPCMessage $message)
    {
        $ins = new self (false);
        $ins->_data = $message->getMessageBody();
        return $ins;
    }


}