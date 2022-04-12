<?php

use PHPUnit\Framework\TestCase;
use Mebius\Data\InputValidator;

class InputValidatorxTest extends TestCase
{
	public function testValidateOK()
	{
		$io = $this->makeSampleInputObj();

		$result = $io->validate();

		$this->assertTrue($result);
		//$this->assertEquals(0, 0);
	}
	public function testValidatePostal()
	{
		$io = $this->makeSampleInputObj();
		$io->postal = "1122580";//ハイフン梨バージョン

		$result = $io->validate();

		$this->assertTrue($result);
		//$this->assertEquals(0, 0);
	}
	public function testSjis()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("パラメーターが UTF-8 ではありません");

		$io = $this->makeSampleInputObj();
		$io->name = mb_convert_encoding($io->name, "sjis", "utf8");

		$result = $io->validate();
	}
	public function testInvalidProperty()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("property does not exist:namex");

		$io = $this->makeSampleInputObjBAD();

		$result = $io->validate();
	}
	public function testInvalidPropertyErrors()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("cant use errors property");

		$io = $this->makeSampleInputObjBADErrors();

		$result = $io->validate();
	}
	public function testInvalidDate()
	{
		$io = $this->makeSampleInputObj();
		$io->date = "hoge";

		$result = $io->validate();
		$errors = $io->getErrors();
		$errorObj = $io->getErrorObject();

		$this->assertFalse($result);
		$this->assertEquals("date", $errors[0]);
		$this->assertTrue(property_exists($errorObj, "date"));
	}
	public function testEmptyName()
	{
		$io = $this->makeSampleInputObj();
		$io->name = "";

		$result = $io->validate();
		$errors = $io->getErrors();

		$this->assertFalse($result);
		$this->assertEquals("name", $errors[0]);
	}
	public function testLongName()
	{
		$io = $this->makeSampleInputObj();
		$io->name = "ホゲフガマッスル";

		$result = $io->validate();
		$errors = $io->getErrors();

		$this->assertFalse($result);
		$this->assertEquals("name", $errors[0]);
	}
	public function testShortName()
	{
		$io = $this->makeSampleInputObj();
		$io->password = "piyo";

		$result = $io->validate();
		$errors = $io->getErrors();

		$this->assertFalse($result);
		$this->assertEquals("password", $errors[0]);
	}
	public function testLengthOver()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid len argument");

		$io = $this->makeSampleInputObjInvalidLen();

		$result = $io->validate();
	}
	public function testLengthOver2()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid len argument");

		$io = $this->makeSampleInputObjInvalidLen2();

		$result = $io->validate();
	}
	public function testInvalidRange()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("invalid from_to argument");

		$io = $this->makeSampleInputObjInvalidRange();

		$result = $io->validate();
	}
	public function testRangeOver()
	{
		$io = $this->makeSampleInputObj();
		$io->rangex = 100;

		$result = $io->validate();
		$errors = $io->getErrors();

		$this->assertFalse($result);
		$this->assertEquals("rangex", $errors[0]);
	}
	public function testInvalidMail()
	{
		$io = $this->makeSampleInputObj();
		$io->mail = "100";
		$io->mail_r = "200";

		$result = $io->validate();
		$errors = $io->getErrors();

		$this->assertFalse($result);
		$this->assertEquals("mail", $errors[0]);
		$this->assertEquals("mail_r", $errors[1]);
	}
	public function testInvalidMail2()
	{
		$io = $this->makeSampleInputObjSimpleMail();
		$io->mail = "100";

		$result = $io->validate();
		$errors = $io->getErrors();

		$this->assertFalse($result);
		$this->assertEquals("mail", $errors[0]);
	}
	public function testNotMatchRegex()
	{
		$io = $this->makeSampleInputObjForRegex();

		$result = $io->validate();
		$errors = $io->getErrors();

		$this->assertFalse($result);
		$this->assertEquals("rre", $errors[0]);
	}

	private function makeSampleInputObj()
	{
		$io = new class extends InputValidator {
			public string $name = "ほげ";
			public string $password = "hogefugapiyo";
			public string $tel = "110-2253";
			public string $postal = "225-8854";
			public string $mail = "hoge@example.com";
			public string $mail_r = "hoge@example.com";
			public int $rangex = 5;
			public string $date = "2020-05-05";
			public function validate(): bool
			{
				$this->mandatory("name");
				$this->length("name", 5);
				$this->overLength("password", 8);
				$this->tel("tel");
				$this->postal("postal");
				$this->mail("mail");
				$this->same("mail", "mail_r");
				$this->numRange("rangex", 2, 10);
				$this->date("date");
				return count($this->getErrors()) === 0;
			}
		};

		return $io;
	}
	private function makeSampleInputObjBAD()
	{
		$io = new class extends InputValidator {
			public string $name = "ほげ";
			public function validate(): bool
			{
				$this->mandatory("namex");//不正プロパティ
				return count($this->getErrors()) === 0;
			}
		};

		return $io;
	}
	private function makeSampleInputObjBADErrors()
	{
		$io = new class extends InputValidator {
			public string $name = "ほげ";
			public function validate(): bool
			{
				$this->mandatory("errors");//不正プロパティ
				return count($this->getErrors()) === 0;
			}
		};

		return $io;
	}
	private function makeSampleInputObjInvalidLen()
	{
		$io = new class extends InputValidator {
			public string $name = "ほげ";
			public function validate(): bool
			{
				$this->length("name", -1);
				return count($this->getErrors()) === 0;
			}
		};

		return $io;
	}
	private function makeSampleInputObjInvalidLen2()
	{
		$io = new class extends InputValidator {
			public string $name = "ほげ";
			public function validate(): bool
			{
				$this->overLength("name", -1);
				return count($this->getErrors()) === 0;
			}
		};

		return $io;
	}
	private function makeSampleInputObjInvalidRange()
	{
		$io = new class extends InputValidator {
			public int $num = 3;
			public function validate(): bool
			{
				$this->numRange("num", 5, 2);
				return count($this->getErrors()) === 0;
			}
		};

		return $io;
	}
	private function makeSampleInputObjSimpleMail()
	{
		$io = new class extends InputValidator {
			public string $mail = "hoge@example.com";
			public function validate(): bool
			{
				$this->mail("mail", false);
				return count($this->getErrors()) === 0;
			}
		};

		return $io;
	}
	private function makeSampleInputObjForRegex()
	{
		$io = new class extends InputValidator {
			public string $rre = "hoge";
			public function validate(): bool
			{
				$this->regex("rre", "/\A[0-9a-z]{10}\z/");
				return count($this->getErrors()) === 0;
			}
		};

		return $io;
	}
}
