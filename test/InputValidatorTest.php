<?php
require_once(__DIR__ . "/../Mebius/IO/ValidateParamBuilder.php");
require_once(__DIR__ . "/../Mebius/IO/InputValidator.php");
require_once(__DIR__ . "/../Mebius/IO/ValidatorUtil.php");
use PHPUnit\Framework\TestCase;

/**
 * InputValidatorTest
 */
class InputValidatorTest extends TestCase
{
	/**
	*普通に使ったときのテスト
	*/
	public function testNormal()
	{
		$origStr = "hoge";
		$origReg = "/\A.+\z/";
		$vpb = new \Mebius\IO\ValidateParamBuilder();
		$vpb->addWithRegEx($origStr, $origReg);
		$vpb->addWithRegEx("hoge", "/\A[a-z]+\z/");
		$vpb->addWithRegEx("12", "/\A[0-9]+\z/");
		$vpb->addWithRegEx("hoge123", "/\A[a-z0-9]+\z/");
		$vpb->addWithRegEx("2015-11-11 10:10:50", "/\A.{1,20}\z/");//h! で使っているチェッカー
		$vpb->addWithRegEx("all", "/\A(all|\d+)\z/");//web で使っているチェッカー
		$vpb->addWithRegEx("205", "/\A(all|\d+)\z/");//web で使っているチェッカー
		$vpb->addWithRegEx("del", "/\A(del|insert)\z/");//web で使っているチェッカー
		$vpb->addWithRegEx("insert", "/\A(del|insert)\z/");//web で使っているチェッカー
		$vpb->addWithRegEx("25", "/\A\d+\z/");//web で使っているチェッカー
		$vpb->addWithRegEx("http://hoge.net", "/\Ahttps?:\/\/.+\z/");//web で使っているチェッカー
		$vpb->addWithRegEx("https://hoge.net", "/\Ahttps?:\/\/.+\z/");//web で使っているチェッカー
		$vpb->addWithRegEx("e", "/\A(e|p)\z/");//ask で使っているチェッカー
		$vpb->addWithRegEx("p", "/\A(e|p)\z/");//ask で使っているチェッカー
		$vpb->addWithRegEx("30", "/\A(30|60|all)\z/");//check order で使っているチェッカー
		$vpb->addWithRegEx("60", "/\A(30|60|all)\z/");//check order で使っているチェッカー
		$vpb->addWithRegEx("all", "/\A(30|60|all)\z/");//check order で使っているチェッカー
		$vpb->addWithRegEx("2000-10-10 22:10:32", "/\A\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\z/");//delrec で使っているチェッカー
		$vpb->addWithRegEx("7", "/\A(7|30|all)\z/");//view で使っているチェッカー
		$vpb->addWithRegEx("30", "/\A(7|30|all)\z/");//view で使っているチェッカー
		$vpb->addWithRegEx("all", "/\A(7|30|all)\z/");//view で使っているチェッカー
		$vpb->addWithRegEx("a", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$vpb->addWithRegEx("o", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$vpb->addWithRegEx("x", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$vpb->addWithRegEx("ao", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$vpb->addWithRegEx("all", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$vpb->addWithRegEx("2", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("5", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("9", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("10", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("12", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("15", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("銀行振り込み", "/\A(銀行振り込み|代金引き替え|クロネコwebコレクト)\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("代金引き替え", "/\A(銀行振り込み|代金引き替え|クロネコwebコレクト)\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("クロネコwebコレクト", "/\A(銀行振り込み|代金引き替え|クロネコwebコレクト)\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("aef321bc65d04ef69a54e6f54a65e", "/\A[0-9a-f]+\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("pc", "/\A(pc|sp)\z/");//order で使っているチェッカー
		$vpb->addWithRegEx("sp", "/\A(pc|sp)\z/");//order で使っているチェッカー
		$vpb->addMail("hoge@dd.com");
		$vpb->addMail("hoge@dd.com", false);
		$vpb->addMail("ほげ._-@d_dマッスル.com");//正しい！
		$vpb->addBetweenInt("3", 2, 5);
		$vpb->addBetweenInt("4", 2);
		$vpb->addBetweenInt("5", null, 6);
		$vpb->addBetweenInt("8", 2, 5, false);
		$vpb->addBetweenInt("1", 2, null, false);
		$vpb->addBetweenInt("8", null, 6, false);
		$iv = new \Mebius\IO\InputValidator($vpb);
	}
	/**
	*バリデーターパラムが 0 のとき
	*/
	public function testEmpty()
	{
		$vpb = new \Mebius\IO\ValidateParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("ValidateParamBuilder オブジェクトの中身が空です");
		$iv = new \Mebius\IO\InputValidator($vpb);
	}
	/**
	*正規表現と一致しない場合
	*/
	public function testRegInvalidCase1()
	{
		$value = "123w";
		$vpb = new \Mebius\IO\ValidateParamBuilder();
		$vpb->addWithRegEx($value, "/\A[a-z]+\z/");
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($value . " : 値が不正です");
		$iv = new \Mebius\IO\InputValidator($vpb);
	}
	/**
	*不正なメールアドレス1 utf8 非許可
	*/
	public function testMailInvalidCase1()
	{
		$value = "ほげ@dd.com";
		$vpb = new \Mebius\IO\ValidateParamBuilder();
		$vpb->addMail($value, false);
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($value . " : 不正なメールアドレスです。");
		$iv = new \Mebius\IO\InputValidator($vpb);
	}
	/**
	*制御文字入りメールアドレス
	*/
	public function testMailInvalidCase2()
	{
		//制御文字入り
		$val2 = <<< STR
hoge@a
aa.com

STR;
		$vpb2 = new \Mebius\IO\ValidateParamBuilder();
		$vpb2->addMail($val2);
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($val2 . " : 不正なメールアドレスです。");
		$iv2 = new \Mebius\IO\InputValidator($vpb2);
	}
	public function testInclude1()
	{
		$vpb = new \Mebius\IO\ValidateParamBuilder();
		$vpb->addBetweenInt("2", 3, 8);
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("2 は 3 と 8 の間にありません");
		$iv = new \Mebius\IO\InputValidator($vpb);
	}
}
