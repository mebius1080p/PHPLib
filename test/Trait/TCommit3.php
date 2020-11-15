<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase3;

/**
 * TCommit3
 */
trait TCommit3
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

		$pdo = $this->makeMockPDO();
		$db = new DBHandlerBase3($pdo);
		DBHandlerBase3::resetPDO();
		$db->commit();
	}
	public function testCommitNotInTransaction()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("not in transaction commit");

		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(false);

		$db = new DBHandlerBase3($pdo);
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

		$db = new DBHandlerBase3($pdo);
		$db->commit();
	}
	public function testCommitSuccess()
	{
		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(true);
		$pdo->method('commit')
			->willReturn(true);

		$db = new DBHandlerBase3($pdo);
		$db->commit();

		$this->assertEquals("", "");//ここまで来ること
	}
}
