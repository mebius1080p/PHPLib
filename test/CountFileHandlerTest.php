<?php
require_once(__DIR__ . "/../Mebius/IO/CountFileHandler.php");
if (!class_exists("\PHPUnit\Framework\TestCase")) {
	require_once(__DIR__ . "/../vendor/autoload.php");
}

/**
 * CounterFileHandlerTest
 */
class CounterFileHandlerTest extends \PHPUnit\Framework\TestCase
{
	/**
	*regex 通常テスト
	*/
	public function testFileCount()
	{
		$file = __DIR__ . "/count.txt";
		$currentCount = file_get_contents($file);
		$cfh = new \Mebius\IO\CountFileHandler($file);
		$cfh->countUp();
		$newCount = file_get_contents($file);
		$intOld = (int)$currentCount;
		$intNew = (int)$newCount;
		$this->assertEquals($intOld + 1, $intNew);
		//後始末
		file_put_contents($file, "1");
	}
	public function testNoFile()
	{
		$file = __DIR__ . "/hoge.txt";
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage("開こうとするファイルが存在しません。");
		$cfh = new \Mebius\IO\CountFileHandler($file);
	}
}
