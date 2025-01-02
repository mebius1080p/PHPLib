<?php

use Mebius\IO\FileHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * FileHandlerTest
 */
#[CoversClass(FileHandler::class)]
class FileHandlerTest extends TestCase
{
	private static $countFile = "";
	private static $sampleFile = "";
	public static function setUpBeforeClass(): void
	{
		self::$countFile = __DIR__ . "/count.txt";
		self::$sampleFile = __DIR__ . "/sample.txt";
		file_put_contents(self::$countFile, "1");
		file_put_contents(self::$sampleFile, "サンプル");
	}
	public static function tearDownAfterClass(): void
	{
		file_put_contents(self::$countFile, "1");
		file_put_contents(self::$sampleFile, "サンプル");
	}
	/**
	 * カウントアップ 通常テスト
	 */
	public function testFileCount()
	{
		$beforeCount = file_get_contents(self::$countFile);
		$fh = new FileHandler(self::$countFile);
		$fh->countUp();
		$newCount = file_get_contents(self::$countFile);
		$intBefore = (int)$beforeCount;
		$intNew = (int)$newCount;
		$this->assertEquals($intBefore + 1, $intNew);
	}
	/**
	 * 中身書き換えテスト
	 */
	public function testContentUpdate()
	{
		$fh2 = new FileHandler(self::$sampleFile);
		$string = $fh2->getString();
		$orig = "サンプル";
		$this->assertEquals($orig, $string);
		//書き換え
		$change = "書き換えテスト";
		$fh2->update($change);
		$this->assertEquals($change, $fh2->getString());
	}
	/**
	 * ファイルなし
	 */
	public function testNoFile()
	{
		$file = __DIR__ . "/hoge.txt";
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($file . " が存在しません。");
		$cfh = new FileHandler($file);
	}
	public function testNotFile()
	{
		$file = __DIR__;
		$this->expectException("Exception");//例外発生をテストするときは必ず書く！
		$this->expectExceptionMessage($file . " が存在しません。");
		$cfh = new FileHandler($file);
	}
}
