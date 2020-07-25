<?php

use PHPUnit\Framework\TestCase;
use Mebius\Json\JsonValidatorBase;
use DummyClass\SampleJsonValidator;

class JsonValidatorBaseTest extends TestCase
{
	public function testEmptyArray()
	{
		$json = "[]";

		$sjv = new SampleJsonValidator($json);

		$this->assertTrue($sjv->getResult());
		$this->assertEquals([], $sjv->getErrors());
		$this->assertEquals([], $sjv->getJson());
	}
	public function testIntArray()
	{
		$json = "[1,2,3]";

		$sjv = new SampleJsonValidator($json);

		$this->assertTrue($sjv->getResult());
		$this->assertEquals([], $sjv->getErrors());
		$this->assertEquals([1, 2, 3], $sjv->getJson());
	}
	public function testStringArray()
	{
		$json = '["1","2","3"]';

		$sjv = new SampleJsonValidator($json);

		$this->assertFalse($sjv->getResult());
		$this->assertTrue(count($sjv->getErrors()) > 0);//項目ごとに細かくエラーが出るので簡易的に assert
		$this->assertEquals(["1","2","3"], $sjv->getJson());
	}
	// public function testEx()
	// {
	// 	// @expectedException Exception
	// 	// @expectedExceptionMessage hoge-
	// }
}
