<?php

use PHPUnit\Framework\TestCase;
use Mebius\Paging\PagingCalculator2;

class PagingCaluculator2Test extends TestCase
{
	public function testGetOffset()
	{
		$pc = new PagingCalculator2(5, 10, 1);
		$this->assertEquals(0, $pc->getOffset());

		$pc = new PagingCalculator2(5, 10, 2);
		$this->assertEquals(5, $pc->getOffset());

		$pc = new PagingCalculator2(-5, 10, -2);
		$this->assertEquals(0, $pc->getOffset());
	}
	public function testSearchResultNormal()
	{
		$pc = new PagingCalculator2(5, 4, 1);
		$this->assertEquals(0, $pc->getOffset());

		$psr = $pc->getPagingSearchResult();
		$this->assertEquals(4, $psr->total);
		$this->assertEquals(1, $psr->page);
		$this->assertEquals(5, $psr->perpage);
		$this->assertEquals(1, $psr->totalpage);
	}
	public function testSearchResultNormalB()
	{
		$pc = new PagingCalculator2(5, 8, 2);
		$this->assertEquals(5, $pc->getOffset());

		$psr = $pc->getPagingSearchResult();
		$this->assertEquals(8, $psr->total);
		$this->assertEquals(2, $psr->page);
		$this->assertEquals(5, $psr->perpage);
		$this->assertEquals(2, $psr->totalpage);
	}
	public function testSearchResult要求ページ数超過()
	{
		//2 ページ分しか取得できないところ、6 ページ目を希望した場合
		$pc = new PagingCalculator2(5, 10, 6);
		$this->assertEquals(0, $pc->getOffset());

		$psr = $pc->getPagingSearchResult();
		$this->assertEquals(10, $psr->total);
		$this->assertEquals(1, $psr->page);//1 にリセットされる
		$this->assertEquals(5, $psr->perpage);
		$this->assertEquals(2, $psr->totalpage);
	}
}
