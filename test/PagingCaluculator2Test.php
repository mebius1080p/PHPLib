<?php
use PHPUnit\Framework\TestCase;
use Mebius\Paging\PagingCaluculator2;

class PagingCaluculator2Test extends TestCase
{
	public function testNormal()
	{
		$pc = new PagingCaluculator2(5, 1);
		$this->assertEquals(0, $pc->getOffset());

		$pc = new PagingCaluculator2(5, 2);
		$this->assertEquals(5, $pc->getOffset());

		$pc = new PagingCaluculator2(-5, -2);
		$this->assertEquals(0, $pc->getOffset());
	}
	public function testSearchResult()
	{
		$pc = new PagingCaluculator2(5, 1);
		$this->assertEquals(0, $pc->getOffset());

		$psr = $pc->getPagingSearchResult(10);
		$this->assertEquals(2, $psr->totalpage);

		$psr = $pc->getPagingSearchResult(-1);
		$this->assertEquals(0, $psr->totalpage);
	}
}
