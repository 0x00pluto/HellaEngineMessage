<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/30
 * Time: 下午3:35
 */

namespace hellaEngine\RPCMessage;

use hellaEngine\RPCMessage\Exceptions\encodeMessageException;


/**
 * Class RPCMessageEncode
 * @package RPCMessage
 */
class RPCMessageEncode
{
    /**
     * 加密秘钥长度
     *
     */
    const NOT_OR_KEY_LEN = 2;


    /**
     * 加密网络消息
     * @param array $messageArr
     * 消息队列,里面为RPCMessage 实例
     * @return string
     * @throws encodeMessageException
     */
    static function encodeMessages(array $messageArr)
    {
        $unJsonArray = [];
        foreach ($messageArr as $message) {
            if (!$message instanceof RPCMessage) {
                throw new encodeMessageException("encode Message Type Error");
            }
            $unJsonArray[] = $message->toArray();
        }


        $compressedData = gzcompress(json_encode($unJsonArray), 9);
        $orKey = "";
        for ($i = 0; $i < self::NOT_OR_KEY_LEN; $i++) {
            $orKey .= chr(mt_rand(1, 200));
        }
        $compressEncodeString = $orKey . $compressedData;
        for ($i = self::NOT_OR_KEY_LEN; $i < strlen($compressedData) + self::NOT_OR_KEY_LEN; $i++) {
            $compressEncodeString [$i] = $compressEncodeString [$i - self::NOT_OR_KEY_LEN] ^ $compressEncodeString [$i];
        }
        $compressMessage = base64_encode($compressEncodeString);
        return $compressMessage;

    }


    /**
     * 解密网络消息
     * @param $message_str
     * @param bool|true $alloc
     * true 返回 RPCMessage 实例数组 ,
     * false 返回数组
     * @return mixed|null
     */
    static function decodeMessages($message_str, $alloc = true)
    {
        try {
            // 解压缩数据
            $unBase64 = base64_decode($message_str);
            if ($unBase64 === FALSE) {
                return null;
            }

            // 解密
            for ($i = strlen($unBase64) - 1; $i >= self::NOT_OR_KEY_LEN; $i--) {
                $unBase64 [$i] = $unBase64 [$i - self::NOT_OR_KEY_LEN] ^ $unBase64 [$i];
            }

            $unBase64 = substr($unBase64, self::NOT_OR_KEY_LEN);
            $messagesData = gzuncompress($unBase64);
            if ($messagesData === FALSE) {
                return null;
            }

            $jsonDecodeMessages = json_decode($messagesData, true);
            $messages = [];

            if ($alloc) {
                foreach ($jsonDecodeMessages as $key => $jsonDecodeMessage) {
                    $messages[$key] = RPCMessage::createWithArray($jsonDecodeMessage);
                }
            } else {
                $messages = $jsonDecodeMessages;
            }
        } catch (\Exception $e) {
            return null;
        }

        return $messages;
    }
}