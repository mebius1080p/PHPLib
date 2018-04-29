<?php
use Mebius\IO\{ValidateParamBuilder, ValidatorUtil};
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
	 * regex 文字が utf8 意外だったとき
	 * @expectedException Exception
	 * @expectedExceptionMessage パラメーターが UTF-8 ではありません
	 */
	public function testNotUtf8()
	{
		$str = "ほげまつ";
		$sjisStr = mb_convert_encoding($str, "sjis");
		$vpb = new ValidateParamBuilder();
		$vpb->addWithRegEx($sjisStr, "/\A.+\z/");
	}
	/**
	 * regex が不正な値だった場合
	 * @expectedException Exception
	 * @expectedExceptionMessage addWithRegEx : 第二引数はスラッシュで囲まれた正規表現リテラルにしてください
	 */
	public function testInvalidRegEx()
	{
		$vpb = new ValidateParamBuilder();
		$vpb->addWithRegEx("hoge", "piyo");
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
	/**
	 * sjis のメールアドレス
	 * @expectedException Exception
	 * @expectedExceptionMessage パラメーターが UTF-8 ではありません
	 */
	public function testSjisMail()
	{
		$mail = "ほげ@dd.com";
		$sjisMail = mb_convert_encoding($mail, "sjis");
		$vpb = new ValidateParamBuilder();
		$vpb->addMail($sjisMail);
	}
	//between---------------------------------
	/**
	*between テスト
	*/
	public function testBetween()
	{
		$val1 = 2;
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
	public function testInvalidCompare()
	{
		$val1 = 2;
		$val2 = 5;
		$val3 = 2;
		$vpb = new ValidateParamBuilder();
		// メッセージ内容が動的なのでこちらに記述
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($val2 . " は " . $val3 . " よりも小さくしてください");
		$vpb->addBetweenInt($val1, $val2, $val3);
	}
}
