<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/30
 * Time: 下午4:18
 */

namespace hellaEngine\RPCMessage\Test;
require_once __DIR__ . '/../../../../vendor/autoload.php';

use hellaEngine\RPCMessage\RPCMessage;
use hellaEngine\RPCMessage\RPCMessageEncode;

class RPCMessageEncodeTest extends \PHPUnit_Framework_TestCase
{

    public function testEncodeOrDecode()
    {
        $messages = [];
        $messages[] = RPCMessage::createWithRpc('a.a', ['b' => 1]);
        $messages[] = RPCMessage::createWithRpc('b.b', ['c' => 1]);


        $messageString = RPCMessageEncode::encodeMessages($messages);


        $this->assertNotEmpty($messageString);


        $decodeMessages = RPCMessageEncode::decodeMessages($messageString);


        $this->assertTrue(is_array($decodeMessages));

        $this->assertCount(2, $decodeMessages);

        foreach ($decodeMessages as $decodeMessage) {
            $this->assertInstanceOf(RPCMessage::class, $decodeMessage);
        }

        $decodeMessage = $decodeMessages[0];
        if ($decodeMessage instanceof RPCMessage) {

            $this->assertEquals('a.a', $decodeMessage->getMessageBodyProperty(RPCMessage::Msg_RPC_FunctionName));
            $this->assertEquals(['b' => 1], $decodeMessage->getMessageBodyProperty(RPCMessage::Msg_PPC_FunctionParams));
        }
        $decodeMessage = $decodeMessages[1];
        if ($decodeMessage instanceof RPCMessage) {

            $this->assertEquals('b.b', $decodeMessage->getMessageBodyProperty(RPCMessage::Msg_RPC_FunctionName));
            $this->assertEquals(['c' => 1], $decodeMessage->getMessageBodyProperty(RPCMessage::Msg_PPC_FunctionParams));
        }


    }
}
