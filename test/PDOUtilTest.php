<?php

use PHPUnit\Framework\TestCase;
use Mebius\DB\PDOUtil;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PDOUtil::class)]
class PDOUtilTest extends TestCase
{
	public function testbuildMysqlDSN()
	{
		$dsn = PDOUtil::buildMysqlDSN("hoge", "fuga", "utf8");
		$this->assertEquals("mysql:host=hoge;dbname=fuga;charset=utf8", $dsn);
	}
	public function testbuildMysqlDSNSocket()
	{
		$dsn = PDOUtil::buildMysqlDSNSocket("hoge", "/path/to/socket", "utf8");
		$this->assertEquals("mysql:unix_socket=/path/to/socket;dbname=hoge;charset=utf8", $dsn);
	}
}
