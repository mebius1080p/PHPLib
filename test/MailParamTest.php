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
		$this->assertEquals("From: dd@dd.com", $mp->getHeader());
		$this->assertEquals("aa@aa.com", $mp->getTo());
		$this->assertEquals("ほげー", $mp->getSubject());
		$message = $mp->getBody();
		// $mesArray = preg_split("/\r\n|\n/", $message);
		$mesArray1 = explode("\r\n", $message);
		$this->assertEquals(2, count($mesArray1));
		$this->assertEquals("メールテスト", $mesArray1[0]);
		$this->assertEquals("だよ", $mesArray1[1]);

		$mp->setLF();
		$message1 = $mp->getBody();
		$mesArray2 = explode("\n", $message1);
		$this->assertEquals(2, count($mesArray2));
		$this->assertEquals("メールテスト", $mesArray2[0]);
		$this->assertEquals("だよ", $mesArray2[1]);

		$mp->setCRLF();
		$message2 = $mp->getBody();
		$mesArray3 = explode("\r\n", $message2);
		$this->assertEquals(2, count($mesArray3));
		$this->assertEquals("メールテスト", $mesArray3[0]);
		$this->assertEquals("だよ", $mesArray3[1]);

		$this->assertEquals("uni", $mp->getEncoding());
	}
	public function testSingleAttach()
	{
		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
		$mp->addAttach($filepath);
		$header = $mp->getHeader();
		$splitHeader = explode("\r\n", $header);
		$this->assertEquals("From: dd@dd.com", $splitHeader[0]);
		$this->assertEquals("Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"", $splitHeader[1]);
		$this->assertEquals("ja", $mp->getEncoding());

		$body = $mp->getBody();
		$splitBody = explode("\r\n", $body);
		// var_dump($splitBody);
		$this->assertEquals("--__BOUNDARY__", $splitBody[0]);
		$this->assertEquals("Content-Type: text/plain; charset=\"UTF-8\"", $splitBody[1]);
		$this->assertEquals("Content-Transfer-Encoding: base64", $splitBody[2]);
		$this->assertEquals("", $splitBody[3]);
		// $this->assertEquals("", $splitBody[4]);//b64
		$this->assertEquals("", $splitBody[5]);//chunk split で改行が入る模様
		$this->assertEquals("--__BOUNDARY__", $splitBody[6]);
		//環境によって mime の検出が想定通り出ないのでコメントアウト
		// $this->assertEquals("Content-Type: text/plain; name=\"mailtest.txt\"", $splitBody[7]);
		$this->assertEquals("Content-Disposition: attachment; filename=\"mailtest.txt\"", $splitBody[8]);
		$this->assertEquals("Content-Transfer-Encoding: base64", $splitBody[9]);
		$this->assertEquals("", $splitBody[10]);
		//b64 部分はスキップ
		$this->assertEquals("", $splitBody[12]);//chunk split で改行が入る模様
		$this->assertEquals("--__BOUNDARY__", $splitBody[13]);
	}
	public function testMultiAttach()
	{
		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
		$mp->addAttach($filepath);
		$mp->addAttach(__DIR__ . "/count.txt");

		$body = $mp->getBody();
		$splitBody = explode("\r\n", $body);
		// var_dump($splitBody);
		$this->assertEquals("--__BOUNDARY__", $splitBody[0]);
		$this->assertEquals("Content-Type: text/plain; charset=\"UTF-8\"", $splitBody[1]);
		$this->assertEquals("Content-Transfer-Encoding: base64", $splitBody[2]);
		$this->assertEquals("", $splitBody[3]);
		// $this->assertEquals("", $splitBody[4]);//b64
		$this->assertEquals("", $splitBody[5]);//chunk split で改行が入る模様
		$this->assertEquals("--__BOUNDARY__", $splitBody[6]);
		//環境によって mime の検出が想定通り出ないのでコメントアウト
		// $this->assertEquals("Content-Type: text/plain; name=\"mailtest.txt\"", $splitBody[7]);
		$this->assertEquals("Content-Disposition: attachment; filename=\"mailtest.txt\"", $splitBody[8]);
		$this->assertEquals("Content-Transfer-Encoding: base64", $splitBody[9]);
		$this->assertEquals("", $splitBody[10]);
		//b64 部分はスキップ
		$this->assertEquals("", $splitBody[12]);//chunk split で改行が入る模様
		$this->assertEquals("--__BOUNDARY__", $splitBody[13]);
		//環境によって mime の検出が想定通り出ないのでコメントアウト
		// $this->assertEquals("Content-Type: text/plain; name=\"count.txt\"", $splitBody[14]);
		$this->assertEquals("Content-Disposition: attachment; filename=\"count.txt\"", $splitBody[15]);
		$this->assertEquals("Content-Transfer-Encoding: base64", $splitBody[16]);
		$this->assertEquals("", $splitBody[17]);
		// $this->assertEquals("", $splitBody[18]);//b64
		$this->assertEquals("", $splitBody[19]);
		$this->assertEquals("--__BOUNDARY__", $splitBody[20]);
	}
	public function testInvalidFrom()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid mail address");

		$from = "ohno";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
	public function testInvalidTo()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid mail address");

		$from = "dd@dd.com";
		$to = "hey";
		$subject = "ほげー";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
	public function testEmptySubject()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("empty subject");

		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "";
		$filepath = __DIR__ . "/mailtest.txt";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
	public function testInvalidFilePath1()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("template file does not exist");

		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$filepath = "/path/to/virtual";
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
	public function testInvalidFilePath2()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("template path is not file");

		$from = "dd@dd.com";
		$to = "aa@aa.com";
		$subject = "ほげー";
		$filepath = __DIR__;
		$param = [];
		$mp = new MailParam($from, $to, $subject, $filepath, $param);
	}
}
