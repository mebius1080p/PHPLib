<?php

use PHPUnit\Framework\TestCase;
use Mebius\Mail\MailParamCore;
use DummyClass\SampleMailParam;

class MailParamCoreTest extends TestCase
{
	public static function setUpBeforeClass(): void
	{
		$sampleClassPath = __DIR__ . DIRECTORY_SEPARATOR . "DummyClass" . DIRECTORY_SEPARATOR . "SampleMailParam.php";
		require_once($sampleClassPath);
	}
	public function testNormalParameter()
	{
		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";

		$pc = new SampleMailParam($from, $to, $subject);
		$message = $pc->getSwiftMessage();

		$this->assertEquals($from, $pc->getFrom());
		$this->assertEquals($to, $pc->getTo());
		$this->assertEquals($subject, $pc->getSubject());
		$this->assertEquals("", $pc->getMessage());

		$fromAssoc = $message->getFrom();
		$this->assertTrue(array_key_exists($from, $fromAssoc));
		$toAssoc = $message->getTo();
		$this->assertTrue(array_key_exists($to, $toAssoc));
		$this->assertEquals($subject, $message->getSubject());
		$this->assertEquals("", $message->getBody());
	}
	public function testInvalidFrom()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid mail address");

		$from = "xxx";
		$to = "aa@aa.com";
		$subject = "ほげー";

		$pc = new SampleMailParam($from, $to, $subject);
	}
	public function testInvalidTo()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid mail address");

		$from = "dd@dd.com";
		$to = "sssssss";
		$subject = "ほげー";

		$pc = new SampleMailParam($from, $to, $subject);
	}
	public function testInvalidSubject()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("empty subject");

		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "";

		$pc = new SampleMailParam($from, $to, $subject);
	}
}
