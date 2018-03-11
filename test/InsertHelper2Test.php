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
		$this->assertEquals("hoge=?,fuga=?,piyo=?", $ih->getUpdateStr());
		$this->assertEquals("INSERT INTO ttx (hoge,fuga,piyo) VALUES(?,?,?)", $ih->getInsertSQL());
		$this->assertEquals("INSERT INTO ttx (hoge,fuga,piyo) VALUES(?,?,?) ON DUPLICATE KEY UPDATE hoge=?,fuga=?,piyo=?", $ih->getOnDuplicateSQL());
	}
	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage empty table name
	 */
	public function testEmptyTableName()
	{
		$table = "";
		$columns = [
			"hoge",
			"fuga",
			"piyo",
		];
		$ih = new InsertHelper2($table, $columns);
	}
	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage empty columns
	 */
	public function testEmptyColumns()
	{
		$table = "tts";
		$columns = [];
		$ih = new InsertHelper2($table, $columns);
	}
}
