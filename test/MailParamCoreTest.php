<?php

use PHPUnit\Framework\TestCase;
use Mebius\Mail\MailParamCore;

class MailParamCoreTest extends TestCase
{
	public function testNormalParameter()
	{
		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$pc = new MailParamCore($from, $to, $subject);
		$this->assertEquals($from, $pc->getFrom());
		$this->assertEquals($to, $pc->getTo());
		$this->assertEquals($subject, $pc->getSubject());
	}
	public function testInvalidFrom()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid mail address");

		$from = "xxx";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$pc = new MailParamCore($from, $to, $subject);
	}
	public function testInvalidTo()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid mail address");

		$from = "dd@dd.com";
		$to = "sssssss";
		$subject = "ほげー";
		$pc = new MailParamCore($from, $to, $subject);
	}
	public function testInvalidSubject()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("empty subject");

		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "";
		$pc = new MailParamCore($from, $to, $subject);
	}
}
