<?php

use PHPUnit\Framework\TestCase;
use Mebius\Net\Slack\{SlackCommunicator, SlackException};
use Mebius\Net\CurlCommunicatorCore;
use Mebius\Util\PHPUtil;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CurlCommunicatorCore::class)]
#[CoversClass(SlackCommunicator::class)]
#[CoversClass(SlackException::class)]
#[CoversClass(PHPUtil::class)]
class SlackCommunicatorTest extends TestCase
{
	public function testSendMessage()
	{
		$okResponse = '{"ok":true}';
		$core = $this->makeMockCommunicatorCore($okResponse);
		$sc = new SlackCommunicator("xxxxxxxxx", $core);
		$sc->setTimeout(30);

		$response = $sc->sendMessage("aa", "bb");

		$this->assertEquals(true, $response->ok);
	}
	public function testEmptyToken()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("token is empty");

		$okResponse = '{"ok":true}';
		$core = $this->makeMockCommunicatorCore($okResponse);
		$sc = new SlackCommunicator("", $core);
	}
	public function testMethodFamilyEmpty()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("method family is empty");

		$okResponse = '{"ok":true}';
		$core = $this->makeMockCommunicatorCore($okResponse);
		$sc = new SlackCommunicator("ddddddddd", $core);

		$sc->sendWrapper("", "");
	}
	public function testJsonEmpty()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("json is empty");

		$okResponse = '{"ok":true}';
		$core = $this->makeMockCommunicatorCore($okResponse);
		$sc = new SlackCommunicator("ddddddddd", $core);

		$sc->sendWrapper("dddddddd", "");
	}
	public function testResponseJsonError()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage('json decode failed:{"ok":tru');

		$response = '{"ok":tru';
		$core = $this->makeMockCommunicatorCore($response);
		$sc = new SlackCommunicator("ddddddddd", $core);

		$sc->sendWrapper("dddddddd", "[0,1,2]");
	}
	public function testResponseErrorArray()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage('json is not object:[0,1,2]');

		$response = '[0,1,2]';
		$core = $this->makeMockCommunicatorCore($response);
		$sc = new SlackCommunicator("ddddddddd", $core);

		$sc->sendWrapper("dddddddd", "[0,1,2]");
	}
	public function testResponseOkPropNotExist()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage('property ok not exist');

		$response = '{"bad":true}';
		$core = $this->makeMockCommunicatorCore($response);
		$sc = new SlackCommunicator("ddddddddd", $core);

		$sc->sendWrapper("dddddddd", "[0,1,2]");
	}
	public function testResponseBadStatus()
	{
		try {
			$response = '{"ok":false}';
			$core = $this->makeMockCommunicatorCore($response);
			$sc = new SlackCommunicator("ddddddddd", $core);

			$sc->sendWrapper("dddddddd", "[0,1,2]");
		} catch (SlackException $se) {
			$this->assertEquals("ステータスエラー", $se->getMessage());
			$json = $se->getJson();
			$this->assertEquals(true, property_exists($json, "ok"));
			$this->assertEquals(false, $json->ok);
		} catch (Exception $e) {
			throw $e;
		}
	}

	private function makeMockCommunicatorCore(string $response): CurlCommunicatorCore
	{
		$stub = $this->createMock(CurlCommunicatorCore::class);
		$stub->method("send")->willReturn($response);

		return $stub;
	}
}
