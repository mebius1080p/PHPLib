<?php
use Mebius\IO\{ValidateParamBuilder, InputValidator};
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
		$this->assertEquals($origStr, $param[0]->stringValue);
		$this->assertEquals($origReg, $param[0]->regex);
		$this->assertEquals(InputValidator::MODE_REGEX, $param[0]->mode);
		$this->assertEquals(true, $param[0]->isInclude);
		//exclude
		$vpb2 = new ValidateParamBuilder();
		$vpb2->addWithRegEx($origStr, $origReg, false);
		$param2 = $vpb2->getParam();
		$this->assertEquals(false, $param2[0]->isInclude);
	}
	/**
	 * regex 文字が utf8 意外だったとき
	 */
	public function testNotUtf8()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("パラメーターが UTF-8 ではありません");

		$str = "ほげまつ";
		$sjisStr = mb_convert_encoding($str, "sjis");
		$vpb = new ValidateParamBuilder();
		$vpb->addWithRegEx($sjisStr, "/\A.+\z/");
	}
	/**
	 * regex が不正な値だった場合
	 */
	public function testInvalidRegEx()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("addWithRegEx : 第二引数はスラッシュで囲まれた正規表現リテラルにしてください");

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
		$this->assertEquals($mail, $param[0]->stringValue);
		$this->assertEquals(InputValidator::MODE_MAIL_UTF8, $param[0]->mode);
		$this->assertEquals(true, $param[0]->isInclude);
		//非 utf8 チェックの場合
		$vpb2 = new ValidateParamBuilder();
		$vpb2->addMail($mail, false);
		$param2 = $vpb2->getParam();
		$this->assertEquals(1, count($param2));
		$this->assertEquals($mail, $param2[0]->stringValue);
		$this->assertEquals(InputValidator::MODE_MAIL, $param2[0]->mode);
		$this->assertEquals(true, $param2[0]->isInclude);
	}
	/**
	 * sjis のメールアドレス
	 */
	public function testSjisMail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("パラメーターが UTF-8 ではありません");

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
		$this->assertEquals($val1, $param[0]->intValue);
		$this->assertEquals($val2, $param[0]->min);
		$this->assertEquals($val3, $param[0]->max);
		$this->assertEquals(InputValidator::MODE_BETWEEN, $param[0]->mode);
		$this->assertEquals(true, $param[0]->isInclude);
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
	public function testCheckUTF8()
	{
		$str = "ほげ123abc漢字";
		ValidateParamBuilder::checkUTF8($str);
		$this->assertTrue(true);//例外が出ないこと
	}
	public function testShiftjisToCheckUTF8()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("パラメーターが UTF-8 ではありません");

		$str = "ほげ123abc漢字";
		ValidateParamBuilder::checkUTF8(mb_convert_encoding($str, "sjis", "UTF-8"));
	}
}
