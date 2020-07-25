<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase2;

/**
 * TRollback
 */
trait TRollback
{
	abstract protected function makeMockSTH(): \PDOStatement;
	abstract protected function makeMockPDO(): \PDO;
	abstract public function assertEquals($a, $b);
	abstract public function expectException(string $classname): void;
	abstract public function expectExceptionMessage(string $message): void;

	public function testRollbackWithoutPDOSet()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("[rollback]pdo is null");

		$db = new DBHandlerBase2();
		$db->rollback();
	}
	public function testRollbackNotInTransaction()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("not in transaction rollback");

		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(false);

		DBHandlerBase2::setPDO($pdo);

		$db = new DBHandlerBase2();
		$db->rollback();
	}
	public function testRollbackFailed()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("rollback failed");

		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(true);
		$pdo->method('rollBack')
			->willReturn(false);

		DBHandlerBase2::setPDO($pdo);

		$db = new DBHandlerBase2();
		$db->rollback();
	}
	public function testRollbackSuccess()
	{
		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(true);
		$pdo->method('rollBack')
			->willReturn(true);

		DBHandlerBase2::setPDO($pdo);

		$db = new DBHandlerBase2();
		$db->rollback();

		$this->assertEquals("", "");//ここまで来ること
	}
}
