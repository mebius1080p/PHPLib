<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase3;

/**
 * TInsertQuery3 description...
 */
trait TInsertQuery3
{
	abstract protected function makeMockSTH(): \PDOStatement;
	abstract protected function makeMockPDO(): \PDO;
	abstract public static function assertEquals($a, $b);
	abstract public function expectException(string $classname): void;
	abstract public function expectExceptionMessage(string $message): void;

	public function testExcuteInsertWithoutPDOSet()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("pdo is null");

		$pdo = $this->makeMockPDO();
		$db = new DBHandlerBase3($pdo);
		DBHandlerBase3::resetPDO();
		$db->executeInsertQuery("INSERT INTO x (a, b) VALUES(?,?)", [1, 2]);
	}
	public function testInsertPrepareFail()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("prepare sql failed");

		$pdo = $this->makeMockPDO();
		$pdo->method('prepare')
			->willReturn(false);

		$db = new DBHandlerBase3($pdo);
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

		$db = new DBHandlerBase3($pdo);
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
			->willReturn("5");

		$db = new DBHandlerBase3($pdo);
		$insId = $db->executeInsertQuery("INSERT INTO x (a, b) VALUES(?,?)", [1, 2]);

		$this->assertEquals(5, $insId);
	}
}
