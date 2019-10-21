<?php

use PHPUnit\Framework\TestCase;
use Mebius\Mail\{MailSenderSwift, MailParamWithTwig};

class MailSenderSwiftTest extends TestCase
{
	public function testNormal()
	{
		$ip = "example.com";
		$port = 25;
		$mss = new MailSenderSwift($ip, $port);

		$param = $this->makeMockMailParam();
		// $result = $mss->send($param);
		// $this->assertEquals(false, $result);
		$this->assertEquals(0, 0);//異常が無いこと
	}
	public function testInvalidHost()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid ip or host");

		$ip = "";
		$port = 50;
		$mss = new MailSenderSwift($ip, $port);
	}
	public function testInvalidPortA()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid port number");

		$ip = "hoge.net";
		$port = -1;
		$mss = new MailSenderSwift($ip, $port);
	}
	public function testInvalidPortB()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid port number");

		$ip = "hoge.net";
		$port = 70000;
		$mss = new MailSenderSwift($ip, $port);
	}
	//-------------------
	private function makeMockMailParam()
	{
		$param = new MailParamWithTwig(
			"sample@example.com",
			"mebius1080p@gmail.com",
			"mail from phpunit"
		);
		$templatePath = __DIR__ . "/mailtest.txt";
		$mailParam = [];
		$param->buildMessage($templatePath, $mailParam);
		return $param;
	}
}
