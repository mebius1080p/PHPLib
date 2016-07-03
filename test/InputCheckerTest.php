<?php
require_once(__DIR__ . "/../Mebius/IO/InputChecker.php");
require_once(__DIR__ . "/../Mebius/IO/CheckParamBuilder.php");
use PHPUnit\Framework\TestCase;

/**
 * InputCheckerTest
 */
class InputCheckerTest extends TestCase
{
	public function testEmpty()
	{
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("CheckParamBuilder オブジェクトの中身が空です");
		$ic = new \Mebius\IO\InputChecker($cpb);
	}
	public function testValidCase()
	{
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$cpb->add("hoge", "/\A[a-z]+\z/");
		$cpb->add("12", "/\A[0-9]+\z/");
		$cpb->add("hoge123", "/\A[a-z0-9]+\z/");
		$cpb->add("2015-11-11 10:10:50", "/\A.{1,20}\z/");//h! で使っているチェッカー
		$cpb->add("all", "/\A(all|\d+)\z/");//web で使っているチェッカー
		$cpb->add("205", "/\A(all|\d+)\z/");//web で使っているチェッカー
		$cpb->add("del", "/\A(del|insert)\z/");//web で使っているチェッカー
		$cpb->add("insert", "/\A(del|insert)\z/");//web で使っているチェッカー
		$cpb->add("25", "/\A\d+\z/");//web で使っているチェッカー
		$cpb->add("http://hoge.net", "/\Ahttps?:\/\/.+\z/");//web で使っているチェッカー
		$cpb->add("https://hoge.net", "/\Ahttps?:\/\/.+\z/");//web で使っているチェッカー
		$cpb->add("e", "/\A(e|p)\z/");//ask で使っているチェッカー
		$cpb->add("p", "/\A(e|p)\z/");//ask で使っているチェッカー
		$cpb->add("30", "/\A(30|60|all)\z/");//check order で使っているチェッカー
		$cpb->add("60", "/\A(30|60|all)\z/");//check order で使っているチェッカー
		$cpb->add("all", "/\A(30|60|all)\z/");//check order で使っているチェッカー
		$cpb->add("2000-10-10 22:10:32", "/\A\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\z/");//delrec で使っているチェッカー
		$cpb->add("7", "/\A(7|30|all)\z/");//view で使っているチェッカー
		$cpb->add("30", "/\A(7|30|all)\z/");//view で使っているチェッカー
		$cpb->add("all", "/\A(7|30|all)\z/");//view で使っているチェッカー
		$cpb->add("a", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$cpb->add("o", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$cpb->add("x", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$cpb->add("ao", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$cpb->add("all", "/\A(a|o|x|ao|all)\z/");//view で使っているチェッカー
		$cpb->add("2", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$cpb->add("5", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$cpb->add("9", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$cpb->add("10", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$cpb->add("12", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$cpb->add("15", "/\A([2-9]|1[0-5])\z/");//order で使っているチェッカー
		$cpb->add("銀行振り込み", "/\A(銀行振り込み|代金引き替え|クロネコwebコレクト)\z/");//order で使っているチェッカー
		$cpb->add("代金引き替え", "/\A(銀行振り込み|代金引き替え|クロネコwebコレクト)\z/");//order で使っているチェッカー
		$cpb->add("クロネコwebコレクト", "/\A(銀行振り込み|代金引き替え|クロネコwebコレクト)\z/");//order で使っているチェッカー
		$cpb->add("aef321bc65d04ef69a54e6f54a65e", "/\A[0-9a-f]+\z/");//order で使っているチェッカー
		$cpb->add("pc", "/\A(pc|sp)\z/");//order で使っているチェッカー
		$cpb->add("sp", "/\A(pc|sp)\z/");//order で使っているチェッカー
		try{
			$ic = new \Mebius\IO\InputChecker($cpb);
			$ic->validate();
		}catch(Exception $e){
			$this->fail("失敗 : " . $e->getMessage());
		}
	}
	public function testInvalidCase1()
	{
		$value = "123w";
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$cpb->add($value, "/\A[a-z]+\z/");
		$ic = new \Mebius\IO\InputChecker($cpb);
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($value . " : 値が不正です");
		$ic->validate();
	}
	public function testInvalidCase2()
	{
		$value = "abc1";
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$cpb->add($value, "/\A[0-9]+\z/");
		$ic = new \Mebius\IO\InputChecker($cpb);
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($value . " : 値が不正です");
		$ic->validate();
	}
	public function testInvalidEncoding()
	{
		$origValue = "ほげぴよ";
		$value = mb_convert_encoding($origValue, "SJIS");
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$cpb->add($value, "/\A.+\z/");
		$ic = new \Mebius\IO\InputChecker($cpb);
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($value . " : パラメーターが UTF-8 ではありません");
		$ic->validate();
	}
	public function testExclude1()
	{
		//prepare
		$testString = "hoge'@dd.com";
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$cpb->add($testString, "/[\"']/", false);
		$ic = new \Mebius\IO\InputChecker($cpb);
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($testString . " : 不正な値が含まれています");
		$ic->validate();
	}
	public function testValidMailAddress()
	{
		$mailAddress = "hoge@abc.com";
		$checkObj = \Mebius\IO\InputChecker::checkMailAddress($mailAddress);
		if (!$checkObj->result) {
			$this->fail($checkObj->message);
		}
	}
	public function testControlString()
	{
		$invalidMailAddress = "hoge\ndd@xyz.com";
		$checkObj = \Mebius\IO\InputChecker::checkMailAddress($invalidMailAddress);
		if ($checkObj->result) {
			$this->fail("判定失敗");
		}
		//改行コード入り
		$anotherMailAddress = <<< STR
hoge
@
aaa.com
STR;
		$checkObj2 = \Mebius\IO\InputChecker::checkMailAddress($anotherMailAddress);
		if ($checkObj2->result) {
			$this->fail("判定失敗");
		}
	}
}
