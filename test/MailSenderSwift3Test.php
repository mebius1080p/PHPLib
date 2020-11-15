<?php

use PHPUnit\Framework\TestCase;
use Mebius\Mail\{MailSenderSwift3, MailParamWithTwig};

class MailSenderSwift3Test extends TestCase
{
	protected function tearDown(): void
	{
		//メソッドごとにリセット
		MailSenderSwift3::resetSwiftMailer();
	}
	public function testNormalSend()
	{
		$param = $this->makeMockMailParam();
		$swift = $this->makeSwiftStub();
		$swift->method('send')
			->willReturn(5);

		$mss = new MailSenderSwift3($swift);
		$sendResult = $mss->send($param);
		$this->assertEquals(true, $sendResult);
	}
	public function testSwiftNull()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("swift mailer is null");

		$param = $this->makeMockMailParam();
		$swift = $this->makeSwiftStub();
		$mss = new MailSenderSwift3($swift);
		MailSenderSwift3::resetSwiftMailer();
		$sendResult = $mss->send($param);
	}
	public function testSendmail()
	{
		$param = $this->makeMockMailParam();
		//実際に送信されないように上書き---------
		$swift = $this->makeSwiftStub();
		$swift->method('send')
			->willReturn(5);
		//---------

		$mss = new MailSenderSwift3($swift);
		$sendResult = $mss->send($param);
		$this->assertEquals(true, $sendResult);
	}
	public function testGetAndReplaceMailer()
	{
		$swift1 = $this->makeSwiftStub();
		$swift1->method('send')
			->willReturn(5);
		$swift2 = $this->makeSwiftStub();
		$swift2->method('send')
			->willReturn(5);

		$mss = new MailSenderSwift3($swift1);
		$mailerx = MailSenderSwift3::getSwiftMailer();

		$this->assertTrue($swift1 === $mailerx);

		MailSenderSwift3::replaceSwiftMailer($swift2);
		$mailery = MailSenderSwift3::getSwiftMailer();
		$this->assertTrue($swift2 === $mailery);
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
