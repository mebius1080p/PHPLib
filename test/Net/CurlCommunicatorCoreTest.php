<?php

use PHPUnit\Framework\TestCase;
use Mebius\Net\CurlCommunicatorCore;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CurlCommunicatorCore::class)]
class CurlCommunicatorCoreTest extends TestCase
{
	public function testHoge()
	{
		$this->assertEquals(0, 0);
	}
	public function testCurlInitFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Could not resolve host: hoge");

		$ci = $this->makeInstance();
		$ci->send("hoge", []);
	}

	private function makeInstance(): CurlCommunicatorCore
	{
		$ci = new CurlCommunicatorCore();

		return $ci;
	}
}
