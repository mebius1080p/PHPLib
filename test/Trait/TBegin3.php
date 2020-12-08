<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase3;

/**
 * TBegin3
 */
trait TBegin3
{
	abstract protected function makeMockSTH(): \PDOStatement;
	abstract protected function makeMockPDO(): \PDO;
	abstract public static function assertEquals($a, $b);
	abstract public function expectException(string $classname): void;
	abstract public function expectExceptionMessage(string $message): void;

	public function testBeginWithoutPDOSet()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("[begin]pdo is null");

		$pdo = $this->makeMockPDO();
		$db = new DBHandlerBase3($pdo);
		DBHandlerBase3::resetPDO();
		$db->begin();
	}
	public function testBeginAlreadyTransaction()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("nesting transaction not supported");

		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(true);

		$db = new DBHandlerBase3($pdo);
		$db->begin();
	}
	public function testBeginFailed()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("start transaction failed");

		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(false);
		$pdo->method('beginTransaction')
			->willReturn(false);

		$db = new DBHandlerBase3($pdo);
		$db->begin();
	}
	public function testBeginSuccess()
	{
		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(false);
		$pdo->method('beginTransaction')
			->willReturn(true);

		$db = new DBHandlerBase3($pdo);
		$db->begin();

		$this->assertEquals("", "");//ここまで来ること
	}
}
