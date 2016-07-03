<?php
require_once(__DIR__ . "/../Mebius/IO/CheckParamBuilder.php");
if (file_exists("PHPUnit\Framework\TestCase.php")) {
	require_once("../vendor/autoload.php");
}

/**
 * CheckParamBuilderTest
 */
class CheckParamBuilderTest extends \PHPUnit\Framework\TestCase
{
	/**
	*エラーなしの通常テスト
	*/
	public function testValid()
	{
		$origStr = "hoge";
		$origReg = "/\A.+\z/";
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$cpb->add($origStr, $origReg);
		$param = $cpb->getParam();
		$this->assertEquals(1, count($param));
		$this->assertEquals($origStr, $param[0]["value"]);
		$this->assertEquals($origReg, $param[0]["regex"]);
		$this->assertEquals(true, $param[0]["isInclude"]);
	}
	/**
	*第二引数が不正な値だった場合
	*/
	public function testInvalid1()
	{
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("CheckParamBuilder2->add : 第二引数はスラッシュで囲まれた正規表現リテラルにしてください");
		$cpb->add("hoge", "piyo");
	}
	/**
	*第三引数が不正な値だった場合
	*/
	public function testInvalid2()
	{
		$cpb = new \Mebius\IO\CheckParamBuilder();
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("CheckParamBuilder2->add : 第三引数は boolean にしてください");
		$cpb->add("hoge", "/\A.+\z/", 2);
	}
}
