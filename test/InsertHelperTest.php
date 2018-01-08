<?php
use Mebius\DB\InsertHelper;
use PHPUnit\Framework\TestCase;
class InsertHelperTest extends TestCase
{
	public function testNormal()
	{
		$columns = [
			"hoge",
			"fuga",
			"piyo",
		];
		$ih = new InsertHelper($columns);
		$col = $ih->getColumnStr();
		$ph = $ih->getPlaceHolderStr();
		$upd = $ih->getUpdateStr();

		$this->assertEquals("hoge,fuga,piyo", $col);
		$this->assertEquals("?,?,?", $ph);
		$this->assertEquals("hoge=?,fuga=?,piyo=?", $upd);
	}
	public function testEmpty()
	{
		$columns = [];
		$ih = new InsertHelper($columns);
		$col = $ih->getColumnStr();
		$ph = $ih->getPlaceHolderStr();
		$upd = $ih->getUpdateStr();

		$this->assertEquals("", $col);
		$this->assertEquals("", $ph);
		$this->assertEquals("", $upd);
	}
}
