<?php

use PHPUnit\Framework\TestCase;
use Mebius\DB\DBHandlerBase2;

class DBHandlerBase2Test extends TestCase
{
	use TExecuteSelectQuery;
	use TInsertQuery;
	use TBegin;
	use TCommit;
	use TRollback;

	protected function tearDown(): void
	{
		//メソッドごとにリセット
		DBHandlerBase2::resetPDO();
	}
	public function testbuildMysqlDSN()
	{
		$dsn = DBHandlerBase2::buildMysqlDSN("hoge", "fuga", "utf8");
		$this->assertEquals("mysql:host=hoge;dbname=fuga;charset=utf8", $dsn);
	}
	public function testbuildMysqlDSNSocket()
	{
		$dsn = DBHandlerBase2::buildMysqlDSNSocket("hoge", "/path/to/socket", "utf8");
		$this->assertEquals("mysql:unix_socket=/path/to/socket;dbname=hoge;charset=utf8", $dsn);
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
