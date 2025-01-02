<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase3;

/**
 * TExecuteSelectQuery3
 */
trait TExecuteSelectQuery3
{
	abstract protected function makeMockSTH(): \PDOStatement;
	abstract protected function makeMockPDO(): \PDO;
	abstract public static function assertEquals($a, $b);
	abstract public function expectException(string $classname): void;
	abstract public function expectExceptionMessage(string $message): void;

	public function testExcuteSelectWithoutPDOSet()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("pdo is null");

		$pdo = $this->makeMockPDO();
		$db = new DBHandlerBase3($pdo);
		DBHandlerBase3::resetPDO();
		$db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);
	}
	public function testPrepareFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("prepare sql failed");

		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn(false);
		$db = new DBHandlerBase3($pdo);
		$db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);
	}
	//setFetchMode は常に true を返すらしい？？？
	// public function testFetchmodeFail()
	// {
	// 	$this->expectException(Exception::class);
	// 	$this->expectExceptionMessage("fetch failed");

	// 	$sth = $this->makeMockSTH();
	// 	$sth->method('setFetchMode')
	// 		->willReturn(false);
	// 	$pdo = $this->makeMockPDO();
	// 	$pdo->method('prepare')
	// 		->willReturn($sth);

	// 	$db = new DBHandlerBase3($pdo);
	// 	$db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);
	// }
	public function testExecuteFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("statement execution failed");

		$sth = $this->makeMockSTH();
		$sth->method('setFetchMode')
			->willReturn(true);
		$sth->method('execute')
			->willReturn(false);
		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn($sth);

		$db = new DBHandlerBase3($pdo);
		$db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);
	}
	public function testFetchallSuccess()
	{
		$sth = $this->makeMockSTH();
		$sth->method('setFetchMode')
			->willReturn(true);
		$sth->method('execute')
			->willReturn(true);
		$dummyResult = [];
		$obja = new \stdClass();
		$obja->a = "hoge";
		$objb = new \stdClass();
		$objb->a = "fuga";
		$dummyResult[] = $obja;
		$dummyResult[] = $objb;
		$sth->method('fetchAll')
			->willReturn($dummyResult);
		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn($sth);

		$db = new DBHandlerBase3($pdo);
		$result = $db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);

		$this->assertEquals(2, count($result));
		$this->assertEquals("hoge", $result[0]->a);
		$this->assertEquals("fuga", $result[1]->a);
	}
}
