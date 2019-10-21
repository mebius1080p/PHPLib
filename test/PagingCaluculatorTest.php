<?php

use PHPUnit\Framework\TestCase;
use Mebius\Paging\{PagingCaluculator, PagingSearchResult};

class PagingCaluculatorTest extends TestCase
{
	public function testHoge()
	{
		$count = 25;
		$perPage = 10;
		$page = 2;
		$pc = new PagingCaluculator($count, $perPage, $page);
		$psr = $pc->getPagingSearchResult();
		$this->assertEquals(2, $pc->getOutPage());
		$this->assertEquals(10, $pc->getOffset());
		$this->assertEquals(3, $pc->getTotalPage());

		$this->assertTrue($psr instanceof PagingSearchResult);
		$this->assertEquals(25, $psr->total);
		$this->assertEquals(0, count($psr->data));
		$this->assertEquals(2, $psr->page);
		$this->assertEquals(10, $psr->perpage);
		$this->assertEquals(3, $psr->totalpage);
	}
	public function testCountLessThanPerpage()
	{
		$count = 5;
		$perPage = 10;
		$page = 2;
		$pc = new PagingCaluculator($count, $perPage, $page);
		$this->assertEquals(1, $pc->getOutPage());
		$this->assertEquals(0, $pc->getOffset());
		$this->assertEquals(1, $pc->getTotalPage());
	}
	public function testMinusRecCount()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("rec count must be great or equal than 0");

		$count = -3;
		$perPage = 10;
		$page = 2;
		$pc = new PagingCaluculator($count, $perPage, $page);
	}
	public function testMinusPerPage()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("per page must be great or equal than 1");

		$count = 25;
		$perPage = -5;
		$page = 2;
		$pc = new PagingCaluculator($count, $perPage, $page);
	}
	public function testMinusPage()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("posted page must be great or equal than 1");

		$count = 25;
		$perPage = 10;
		$page = -1;
		$pc = new PagingCaluculator($count, $perPage, $page);
	}
}
