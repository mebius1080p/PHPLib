<?php

use PHPUnit\Framework\TestCase;
use Mebius\Mail\MailParamWithTwig;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MailParamWithTwig::class)]
class MailParamWithTwigTest extends TestCase
{
	public function testHoge()
	{
		$twigMP = $this->getMailParam();

		$templatePath = __DIR__ . "/mailtest.txt";
		$mailParam = [];
		$twigMP->buildMessage($templatePath, $mailParam);
		$this->assertEquals("メールテスト\nだよ", $twigMP->getMessage());
	}
	public function testTemplateFileNotExist()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("template file does not exist");

		$twigMP = $this->getMailParam();
		$twigMP->buildMessage("/hoge", $mailParam = []);
	}
	public function testTemplatePathIsDirectory()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("template path is not file");

		$twigMP = $this->getMailParam();
		$twigMP->buildMessage(dirname(__FILE__), $mailParam = []);
	}
	//------------------
	private function getMailParam()
	{
		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";
		return new MailParamWithTwig($from, $to, $subject);
	}
}
