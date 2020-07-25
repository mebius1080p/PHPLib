<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase2;

/**
 * TCommit
 */
trait TCommit
{
	abstract protected function makeMockSTH(): \PDOStatement;
	abstract protected function makeMockPDO(): \PDO;
	abstract public function assertEquals($a, $b);
	abstract public function expectException(string $classname): void;
	abstract public function expectExceptionMessage(string $message): void;

	public function testCommitWithoutPDOSet()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("[commit]pdo is null");

		$db = new DBHandlerBase2();
		$db->commit();
	}
	public function testCommitNotInTransaction()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("not in transaction commit");

		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(false);

		DBHandlerBase2::setPDO($pdo);

		$db = new DBHandlerBase2();
		$db->commit();
	}
	public function testCommitFailed()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("commit failed");

		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(true);
		$pdo->method('commit')
			->willReturn(false);

		DBHandlerBase2::setPDO($pdo);

		$db = new DBHandlerBase2();
		$db->commit();
	}
	public function testCommitSuccess()
	{
		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(true);
		$pdo->method('commit')
			->willReturn(true);

		DBHandlerBase2::setPDO($pdo);

		$db = new DBHandlerBase2();
		$db->commit();

		$this->assertEquals("", "");//ここまで来ること
	}
}
