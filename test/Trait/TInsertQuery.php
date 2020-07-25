<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase2;

/**
 * TInsertQuery description...
 */
trait TInsertQuery
{
	abstract protected function makeMockSTH(): \PDOStatement;
	abstract protected function makeMockPDO(): \PDO;
	abstract public function assertEquals($a, $b);
	abstract public function expectException(string $classname): void;
	abstract public function expectExceptionMessage(string $message): void;

	public function testExcuteInsertWithoutPDOSet()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("pdo is null");

		$db = new DBHandlerBase2();
		$db->executeInsertQuery("INSERT INTO x (a, b) VALUES(?,?)", [1, 2]);
	}
	public function testInsertPrepareFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("prepare sql failed");

		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn(false);
		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$db->executeInsertQuery("INSERT INTO x (a, b) VALUES(?,?)", [1, 2]);
	}
	public function testInsertExecuteFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("statement execution failed");

		$sth = $this->makeMockSTH();
		$sth->method('execute')
			->willReturn(false);
		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn($sth);

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$db->executeInsertQuery("INSERT INTO x (a, b) VALUES(?,?)", [1, 2]);
	}
	public function testInsertSuccess()
	{
		$sth = $this->makeMockSTH();
		$sth->method('execute')
			->willReturn(true);
		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn($sth);
		$pdo->method('lastInsertId')
			->willReturn(5);

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$insId = $db->executeInsertQuery("INSERT INTO x (a, b) VALUES(?,?)", [1, 2]);

		$this->assertEquals(5, $insId);
	}
}
