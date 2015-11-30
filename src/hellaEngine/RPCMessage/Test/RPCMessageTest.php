<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/30
 * Time: 下午3:05
 */

namespace hellaEngine\RPCMessage\Test;


use hellaEngine\RPCMessage\RPCMessage;

require_once __DIR__ . '/../../../../vendor/autoload.php';


class RPCMessageTest extends \PHPUnit_Framework_TestCase
{


    public function testCreate()
    {
        $message = RPCMessage::create();
        $message->setMessageHeaderProperty('verify', '1233123');

        $this->assertInstanceOf(RPCMessage::class, $message);

        $this->assertEquals(1, $message->getMessageHeaderProperty(RPCMessage::Msg_HeadKey_verMajor));

        $this->assertEquals(1, $message->getMessageHeaderProperty(RPCMessage::Msg_HeadKey_verMin));

        $this->assertEquals(RPCMessage::MESSAGE_TYPE_DATA, $message->getMessageHeaderProperty(RPCMessage::Msg_HeadKey_MessageType));

        $this->assertEquals('1233123', $message->getMessageHeaderProperty('verify'));

    }

    public function testCreateRPCMessage()
    {
        $message = RPCMessage::createWithRpc('a.a', ['b' => 123], 2);


        $this->assertInstanceOf(RPCMessage::class, $message);

        $this->assertEquals('a.a', $message->getMessageBodyProperty(RPCMessage::Msg_RPC_FunctionName));

        $this->assertEquals(['b' => 123], $message->getMessageBodyProperty(RPCMessage::Msg_PPC_FunctionParams));

        $this->assertEquals(2, $message->getMessageBodyProperty(RPCMessage::Msg_RPC_FunctionId));
    }


}
