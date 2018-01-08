<?php
use Mebius\IO\ValidateParamBuilder;
use Mebius\IO\ValidatorUtil;
use PHPUnit\Framework\TestCase;

/**
 * ValidateParamBuilderTest
 */
class ValidateParamBuilderTest extends TestCase
{
	/**
	*regex 通常テスト
	*/
	public function testRegex()
	{
		$origStr = "hoge";
		$origReg = "/\A.+\z/";
		$vpb = new ValidateParamBuilder();
		$vpb->addWithRegEx($origStr, $origReg);
		$param = $vpb->getParam();
		$this->assertEquals(1, count($param));
		$this->assertEquals($origStr, $param[0]["value1"]);
		$this->assertEquals("", $param[0]["value2"]);
		$this->assertEquals("", $param[0]["value3"]);
		$this->assertEquals($origReg, $param[0]["regex"]);
		$this->assertEquals("regex", $param[0]["mode"]);
		$this->assertEquals(true, $param[0]["isInclude"]);
		//exclude
		$vpb2 = new ValidateParamBuilder();
		$vpb2->addWithRegEx($origStr, $origReg, false);
		$param2 = $vpb2->getParam();
		$this->assertEquals(false, $param2[0]["isInclude"]);
	}
	/**
	*regex 文字が utf8 意外だったとき
	*/
	public function testNotUtf8()
	{
		$str = "ほげまつ";
		$sjisStr = mb_convert_encoding($str, "sjis");
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("パラメーターが UTF-8 ではありません");
		$vpb->addWithRegEx($sjisStr, "/\A.+\z/");
	}
	/**
	*regex が不正な値だった場合
	*/
	public function testInvalidRegEx()
	{
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("addWithRegEx : 第二引数はスラッシュで囲まれた正規表現リテラルにしてください");
		$vpb->addWithRegEx("hoge", "piyo");
	}
	/**
	*addWithRegEx 第三引数が不正な値だった場合
	*/
	public function testInvalidInclude()
	{
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("addWithRegEx : 第三引数は boolean にしてください");
		$vpb->addWithRegEx("hoge", "/\A.+\z/", 2);
	}
	//mail------------------------------------------
	/**
	*メール通常テスト
	*/
	public function testMail()
	{
		$mail = "hoge@dd.com";
		$vpb = new ValidateParamBuilder();
		$vpb->addMail($mail);
		$param = $vpb->getParam();
		$this->assertEquals(1, count($param));
		$this->assertEquals($mail, $param[0]["value1"]);
		$this->assertEquals("", $param[0]["value2"]);
		$this->assertEquals("", $param[0]["value3"]);
		$this->assertEquals("", $param[0]["regex"]);
		$this->assertEquals("mailutf8", $param[0]["mode"]);
		$this->assertEquals(true, $param[0]["isInclude"]);
		//非 utf8 チェックの場合
		$vpb2 = new ValidateParamBuilder();
		$vpb2->addMail($mail, false);
		$param2 = $vpb2->getParam();
		$this->assertEquals(1, count($param2));
		$this->assertEquals($mail, $param2[0]["value1"]);
		$this->assertEquals("", $param2[0]["value2"]);
		$this->assertEquals("", $param2[0]["value3"]);
		$this->assertEquals("", $param2[0]["regex"]);
		$this->assertEquals("mail", $param2[0]["mode"]);
		$this->assertEquals(true, $param2[0]["isInclude"]);
	}
	public function testMailInvalidFlag()
	{
		$mail = "hoge@dd.com";
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("addMail : 第二引数は boolean にしてください");
		$vpb->addMail($mail, 2);
	}
	/**
	*sjis のメールアドレス
	*/
	public function testSjisMail()
	{
		$mail = "ほげ@dd.com";
		$sjisMail = mb_convert_encoding($mail, "sjis");
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("パラメーターが UTF-8 ではありません");
		$vpb->addMail($sjisMail);
	}
	//between---------------------------------
	/**
	*between テスト
	*/
	public function testBetween()
	{
		$val1 = "2";
		$val2 = 1;
		$val3 = 5;
		$vpb = new ValidateParamBuilder();
		$vpb->addBetweenInt($val1, $val2, $val3);
		$param = $vpb->getParam();
		$this->assertEquals(1, count($param));
		$this->assertEquals(intval($val1), $param[0]["value1"]);
		$this->assertEquals($val2, $param[0]["value2"]);
		$this->assertEquals($val3, $param[0]["value3"]);
		$this->assertEquals("", $param[0]["regex"]);
		$this->assertEquals("compare", $param[0]["mode"]);
		$this->assertEquals(true, $param[0]["isInclude"]);
	}
	/**
	*between 第一引数が utf8 以外
	*/
	public function testNotUtf8Between()
	{
		$val1 = "ほげ";
		$sjisVal = mb_convert_encoding($val1, "sjis");
		$val2 = 1;
		$val3 = 5;
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("パラメーターが UTF-8 ではありません");
		$vpb->addBetweenInt($sjisVal, $val2, $val3);
	}
	/**
	*between 数値以外1
	*/
	public function testNotInt1()
	{
		$val1 = "2";
		$val2 = "もげら";
		$val3 = 5;
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($val2 . " : は数値ではありません");
		$vpb->addBetweenInt($val1, $val2);
	}
	/**
	*between 数値以外2
	*/
	public function testNotInt2()
	{
		$val1 = "2";
		$val2 = 1;
		$val3 = "ほげ";
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($val3 . " : は数値ではありません");
		$vpb->addBetweenInt($val1, $val2, $val3);
	}
	/**
	*between 比較数値が両方 null だった場合
	*/
	public function testDualNull()
	{
		$val1 = "2";
		$val2 = null;
		$val3 = null;
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("比較数値は最低でも片方は設定してください");
		$vpb->addBetweenInt($val1, $val2, $val3);
	}
	public function testInvalidCompare()
	{
		$val1 = "2";
		$val2 = 5;
		$val3 = 2;
		$vpb = new ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($val2 . " は " . $val3 . "よりも小さくしてください");
		$vpb->addBetweenInt($val1, $val2, $val3);
	}
}
