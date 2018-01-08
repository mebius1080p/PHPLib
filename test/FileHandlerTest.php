<?php
use Mebius\IO\FileHandler;
use PHPUnit\Framework\TestCase;

/**
 * FileHandlerTest
 */
class FileHandlerTest extends TestCase
{
	/**
	*regex 通常テスト
	*/
	public function testFileCount()
	{
		$file = __DIR__ . "/count.txt";
		$file2 = __DIR__ . "/sample.txt";
		$currentCount = file_get_contents($file);
		$fh = new FileHandler($file);
		$fh->countUp();
		$newCount = file_get_contents($file);
		$intOld = (int)$currentCount;
		$intNew = (int)$newCount;
		$this->assertEquals($intOld + 1, $intNew);
		//その 2
		$fh2 = new FileHandler($file2);
		$string = $fh2->getString();
		$orig = "サンプル";
		$this->assertEquals($orig, $string);
		//書き換え
		$change = "書き換えテスト";
		$fh2->update($change);
		$this->assertEquals($change, $fh2->getString());
		//後始末
		$fh->update("1");
		$fh2->update($orig);
	}
	public function testNoFile()
	{
		$file = __DIR__ . "/hoge.txt";
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($file . " が存在しません。");
		$cfh = new FileHandler($file);
	}
}
