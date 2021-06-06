<?php

use PHPUnit\Framework\TestCase;
use Mebius\Util\PHPUtil;

class PHPUtilTest extends TestCase
{
	public function testObjectPropertyExist()
	{
		$obj = $this->makeSampleObject();

		PHPUtil::propCheck($obj, "hoge");

		$this->assertEquals(0, 0);//ここまで来ること
	}
	public function testObjectPropertyNotExist()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("property piyo not exist");

		$obj = $this->makeSampleObject();

		PHPUtil::propCheck($obj, "piyo");
	}

	private function makeSampleObject(): stdClass
	{
		$obj = new stdClass();
		$obj->hoge = "fuga";

		return $obj;
	}
}
