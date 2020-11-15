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

	public function testReplacePDO()
	{
		$pdo1 = $this->makeMockPDO();
		$pdo2 = $this->makeMockPDO();

		$db = new DBHandlerBase3($pdo1);
		DBHandlerBase3::replacePDO($pdo2);
		$pdox = DBHandlerBase3::getPDO();
		$this->assertTrue($pdo1 !== $pdox);
		$this->assertTrue($pdo2 === $pdox);
	}
	public function testResetPDO()
	{
		$pdo1 = $this->makeMockPDO();
		$db = new DBHandlerBase3($pdo1);
		DBHandlerBase3::resetPDO();

		$pdox = DBHandlerBase3::getPDO();

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
