<?php

use PHPUnit\Framework\TestCase;
use Mebius\Mail\{MailUtil, MailSenderSwift3};

class MailUtilTest extends TestCase
{
	public function testCreateSwiftMailer()
	{
		$swift1 = MailUtil::createSwiftSendmail();
		$swift2 = MailUtil::createSwiftSmtp("localhost", 25);

		$mss = new MailSenderSwift3($swift1);
		$mailerx = MailSenderSwift3::getSwiftMailer();

		$this->assertTrue($swift1 === $mailerx);

		MailSenderSwift3::replaceSwiftMailer($swift2);
		$mailery = MailSenderSwift3::getSwiftMailer();
		$this->assertTrue($swift2 === $mailery);
	}
	public function testInvalidHost()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("empty ip or host");

		$swift = MailUtil::createSwiftSmtp("", 25);
	}
	public function testInvalidPort0()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid port number");
		$swift = MailUtil::createSwiftSmtp("localhost", 0);
	}
	public function testInvalidPortMinus()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid port number");
		$swift = MailUtil::createSwiftSmtp("localhost", -5);
	}
	public function testInvalidPortLarge()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid port number");
		$swift = MailUtil::createSwiftSmtp("localhost", 80000);
	}
}
