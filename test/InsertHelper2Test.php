<?php
use PHPUnit\Framework\TestCase;
use Mebius\DB\InsertHelper2;

class InsertHelper2Test extends TestCase
{
	public function testNormal()
	{
		$table = "ttx";
		$columns = [
			"hoge",
			"fuga",
			"piyo",
		];
		$ih = new InsertHelper2($table, $columns);
		$this->assertEquals("hoge,fuga,piyo", $ih->getColumnStr());
		$this->assertEquals("?,?,?", $ih->getPlaceHolderStr());
		$this->assertEquals("hoge=VALUES(hoge),fuga=VALUES(fuga),piyo=VALUES(piyo)", $ih->getUpdateStr());
		$this->assertEquals("INSERT INTO ttx (hoge,fuga,piyo) VALUES(?,?,?)", $ih->getInsertSQL());
		$this->assertEquals("INSERT INTO ttx (hoge,fuga,piyo) VALUES(?,?,?) ON DUPLICATE KEY UPDATE hoge=VALUES(hoge),fuga=VALUES(fuga),piyo=VALUES(piyo)", $ih->getOnDuplicateSQL());
	}
	public function testEmptyTableName()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("empty table name");

		$table = "";
		$columns = [
			"hoge",
			"fuga",
			"piyo",
		];
		$ih = new InsertHelper2($table, $columns);
	}
	public function testEmptyColumns()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("empty columns");

		$table = "tts";
		$columns = [];
		$ih = new InsertHelper2($table, $columns);
	}
}
