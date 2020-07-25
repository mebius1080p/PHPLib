<?php

use PHPUnit\Framework\TestCase;
use Mebius\Mail\{MailSenderSwift2, MailParamWithTwig};

class MailSenderSwift2Test extends TestCase
{
	protected function tearDown(): void
	{
		//メソッドごとにリセット
		MailSenderSwift2::resetSwiftMailer();
	}
	public function testNormalSend()
	{
		$param = $this->makeMockMailParam();
		$swift = $this->makeSwiftStub();
		$swift->method('send')
			->willReturn(5);

		MailSenderSwift2::setSwiftMailer($swift);
		$mss = new MailSenderSwift2();
		$sendResult = $mss->send($param);
		$this->assertEquals(true, $sendResult);
	}
	public function testSwiftNull()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("swift mailer is null");

		$param = $this->makeMockMailParam();
		$mss = new MailSenderSwift2();
		$sendResult = $mss->send($param);
	}
	public function testSendmail()
	{
		$param = $this->makeMockMailParam();
		MailSenderSwift2::setupSwiftSendmail();

		//実際に送信されないように上書き---------
		$swift = $this->makeSwiftStub();
		$swift->method('send')
			->willReturn(5);
		MailSenderSwift2::setSwiftMailer($swift);
		//---------

		$mss = new MailSenderSwift2();
		$sendResult = $mss->send($param);
		$this->assertEquals(true, $sendResult);
	}
	public function testSMTPSuccess()
	{
		$param = $this->makeMockMailParam();
		MailSenderSwift2::setupSwiftSmtp("localhost", 25);

		//実際に送信されないように上書き---------
		$swift = $this->makeSwiftStub();
		$swift->method('send')
			->willReturn(5);
		MailSenderSwift2::setSwiftMailer($swift);
		//---------

		$mss = new MailSenderSwift2();
		$sendResult = $mss->send($param);
		$this->assertEquals(true, $sendResult);
	}
	public function testSMTPEmptyHost()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid ip or host");

		$param = $this->makeMockMailParam();
		MailSenderSwift2::setupSwiftSmtp("", 25);

		$mss = new MailSenderSwift2();
		$sendResult = $mss->send($param);
	}
	public function testSMTPEmptyMinusPort()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid port number");

		$param = $this->makeMockMailParam();
		MailSenderSwift2::setupSwiftSmtp("localhost", -2);

		$mss = new MailSenderSwift2();
		$sendResult = $mss->send($param);
	}
	public function testSMTPEmptyLargePort()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid port number");

		$param = $this->makeMockMailParam();
		MailSenderSwift2::setupSwiftSmtp("localhost", 123456789);

		$mss = new MailSenderSwift2();
		$sendResult = $mss->send($param);
	}
	//-------------------
	private function makeSwiftStub(): Swift_Mailer
	{
		$stub = $this->createMock(Swift_Mailer::class);
		return $stub;
	}
	private function makeMockMailParam(): MailParamWithTwig
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
