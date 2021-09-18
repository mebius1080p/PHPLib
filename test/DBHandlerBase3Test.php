<?php

use PHPUnit\Framework\TestCase;
use Mebius\DB\DBHandlerBase3;

class DBHandlerBase3Test extends TestCase
{
	use TExecuteSelectQuery3;
	use TInsertQuery3;
	use TBegin3;
	use TCommit3;
	use TRollback3;

	protected function tearDown(): void
	{
		//メソッドごとにリセット
		DBHandlerBase3::resetPDO();
	}

	public function testEmptyConnectionName()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("empty connection name");

		$pdo1 = $this->makeMockPDO();
		$db = new DBHandlerBase3($pdo1, "");
	}
	public function testResetPDO()
	{
		$pdo1 = $this->makeMockPDO();
		$db = new DBHandlerBase3($pdo1);
		DBHandlerBase3::resetPDO();

		$pdox = $db->getPDO();

		$this->assertTrue($pdox === null);
	}

	//----------------------
	protected function makeMockPDO(): \PDO
	{
		$stub = $this->createMock(\PDO::class);
		return $stub;
	}
	protected function makeMockSTH(): \PDOStatement
	{
		$stub = $this->createMock(\PDOStatement::class);
		return $stub;
	}
}
