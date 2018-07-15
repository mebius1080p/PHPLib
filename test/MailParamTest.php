<?php
use PHPUnit\Framework\TestCase;
use Mebius\Mail\MailParam;

class MailParamTest extends TestCase
{
	public function testHoge()
	{
		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
		$this->assertEquals("dd@dd.com", $mp->getFrom());
		$this->assertEquals("aa@aa.com", $mp->getTo());
		$this->assertEquals("ほげー", $mp->getSubject());
		$message = $mp->getMessage();
		$mesArray = preg_split("/\r\n|\n/", $message);
		$this->assertEquals(2, count($mesArray));
		$this->assertEquals("メールテスト", $mesArray[0]);
		$this->assertEquals("だよ", $mesArray[1]);
	}
	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage invalid mail address
	 */
	public function testInvalidFrom()
	{
		$from = "ohno";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage invalid mail address
	 */
	public function testInvalidTo()
	{
		$from = "dd@dd.com";
		$to = "hey";
		$subject = "ほげー";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage empty subject
	 */
	public function testEmptySubject()
	{
		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage template file does not exist
	 */
	public function testInvalidFilePath()
	{
		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$filepath = __DIR__;
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
}
