<?php

declare(strict_types=1);

namespace Mebius\Paging;

/**
 * PagingCalculator2 ページング周りのもろもろを計算して取り出せるようにするクラス
 */
class PagingCalculator2
{
	/**
	 * @var int 一回の検索で取り出す最大件数
	 */
	private $perPage = 1;
	/**
	 * @var int 取り出すページ数。取り出すページが異常な値であってもそれを使用する
	 */
	private $page = 1;
	/**
	 * @var int sql で使うデータ取得オフセット値。0 から始まる
	 */
	private $offset = 0;

	/**
	 * コンストラクタ
	 * @param int $perPage 一回の検索で表示するレコードの数 1 以上
	 * @param int $postedPage 投稿されてきた、取得する希望のページ数 <= 1
	 */
	public function __construct(int $perPage, int $postedPage)
	{
		$this->perPage = $perPage <= 0 ? 1 : $perPage;
		$this->page = $postedPage <= 0 ? 1 : $postedPage;
	}
	/**
	 * sql で使うオフセット値を返す
	 * @return int sql で使うオフセット値
	 */
	public function getOffset(): int
	{
		return $this->perPage * ($this->page - 1);
	}
	/**
	 * PagingSearchResult のインスタンスを返すメソッド
	 * @param int $recordCount 検索結果数
	 * @return PagingSearchResult PagingSearchResult のインスタンス
	 */
	public function getPagingSearchResult(int $recordCount)
	{
		$count = $recordCount < 0 ? 0 : $recordCount;
		$totalPage = ceil($count / $this->perPage);

		$psr = new PagingSearchResult();
		$psr->total = $count;
		$psr->page = $this->page;
		$psr->perpage = $this->perPage;
		$psr->totalpage = (int)$totalPage;
		return $psr;
	}
}
