<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase2;

/**
 * TExecuteSelectQuery
 */
trait TExecuteSelectQuery
{
	abstract protected function makeMockSTH(): \PDOStatement;
	abstract protected function makeMockPDO(): \PDO;
	abstract public function assertEquals($a, $b);
	abstract public function expectException(string $classname): void;
	abstract public function expectExceptionMessage(string $message): void;

	public function testExcuteSelectWithoutPDOSet()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("pdo is null");

		$db = new DBHandlerBase2();
		$db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);
	}
	public function testPrepareFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("prepare sql failed");

		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn(false);
		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);
	}
	public function testFetchmodeFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("fetch failed");

		$sth = $this->makeMockSTH();
		$sth->method('setFetchMode')
			->willReturn(false);
		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn($sth);

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);
	}
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

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);
	}
	public function testFetchallFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("fetch all failed");

		$sth = $this->makeMockSTH();
		$sth->method('setFetchMode')
			->willReturn(true);
		$sth->method('execute')
			->willReturn(true);
		$sth->method('fetchAll')
			->willReturn(false);
		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn($sth);

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
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

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$result = $db->executeSelectQuery("SELECT * FROM hoge", \stdClass::class);

		$this->assertEquals(2, count($result));
		$this->assertEquals("hoge", $result[0]->a);
		$this->assertEquals("fuga", $result[1]->a);
	}
}