<?php

declare(strict_types=1);

namespace Mebius\Paging;

/**
* PagingSearchResult ページングを考慮した検索結果を納めるためのクラス
*/
class PagingSearchResult
{
	/**
	 * @var int 検索でヒットする全件数
	 */
	public int $total = 0;
	/**
	 * @var array 検索でヒットしたデータを格納
	 */
	public array $data = [];
	/**
	 * @var int 返しているページ。1 以上
	 */
	public int $page = 1;
	/**
	 * @var int 1 ページあたり何件七日の数値。1 以上
	 */
	public int $perpage = 1;
	/**
	 * @var int 検索で何ページ出力できるのかのページ数。1 以上
	 */
	public int $totalpage = 1;
	public function __construct()
	{
		//dd;
	}
}
