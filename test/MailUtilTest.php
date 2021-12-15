<?php

use PHPUnit\Framework\TestCase;
use Mebius\Mail\MailUtil;

class MailUtilTest extends TestCase
{
	public function testEmptyDsn()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("mail DSN is empty");

		$dsn = "";

		MailUtil::createSymfonyMailerSmtp($dsn);
	}
	public function testNormalDSN()
	{
		$dsn = "smtp://user:pass@smtp.example.com:25";

		$transport = MailUtil::createSymfonyMailerSmtp($dsn);

		$this->assertEquals(0, 0);//ここまでくること
	}
}
