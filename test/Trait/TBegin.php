<?php

declare(strict_types=1);

use Mebius\DB\DBHandlerBase2;

/**
 * TBegin
 */
trait TBegin
{
	abstract protected function makeMockSTH(): \PDOStatement;
	abstract protected function makeMockPDO(): \PDO;
	abstract public function assertEquals($a, $b);
	abstract public function expectException(string $classname): void;
	abstract public function expectExceptionMessage(string $message): void;

	public function testBeginWithoutPDOSet()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("[begin]pdo is null");

		$db = new DBHandlerBase2();
		$db->begin();
	}
	public function testBeginAlreadyTransaction()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("nesting transaction not supported");

		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(true);

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
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

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$db->begin();
	}
	public function testBeginSuccess()
	{
		$pdo = $this->makeMockPDO();
		$pdo->method('inTransaction')
			->willReturn(false);
		$pdo->method('beginTransaction')
			->willReturn(true);

		DBHandlerBase2::setPDO($pdo);
		$db = new DBHandlerBase2();
		$db->begin();

		$this->assertEquals("", "");//ここまで来ること
	}
}
