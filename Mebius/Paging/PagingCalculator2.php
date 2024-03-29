<?php

declare(strict_types=1);

namespace Mebius\Paging;

/**
 * PagingCalculator2 ページング周りのもろもろを計算して取り出せるようにするクラス
 */
class PagingCalculator2
{
	/**
	 * @var int $perPage 一回の検索で取り出す最大件数
	 */
	private int $perPage = 1;
	/**
	 * @var int $page 取り出すページ数。取り出すページが異常な値であってもそれを使用する
	 */
	private int $page = 1;
	/**
	 * @var int $count 検索結果のレコード数
	 */
	private int $count = 0;
	/**
	 * @var int $totalPage 検索結果を分割した場合の全ページ数
	 */
	private int $totalPage = 0;

	/**
	 * コンストラクタ
	 * @param int $perPage 一回の検索で表示するレコードの数 1 以上
	 * @param int $recordCount 検索結果数 0以上
	 * @param int $postedPage 投稿されてきた、取得する希望のページ数 <= 1
	 */
	public function __construct(int $perPage, int $recordCount, int $postedPage)
	{
		$this->perPage = $perPage <= 0 ? 1 : $perPage;
		$this->count = $recordCount < 0 ? 0 : $recordCount;
		$this->page = $postedPage <= 0 ? 1 : $postedPage;

		$this->totalPage = intval(ceil($this->count / $this->perPage));
		if ($this->totalPage === 0) {
			$this->totalPage = 1;
		}
		if ($postedPage > $this->totalPage) {
			//投稿されてきた希望ページ数が実ページ数をオーバーしていた場合は 1 に強制リセット
			$this->page = 1;
		}
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
	 * @return PagingSearchResult PagingSearchResult のインスタンス
	 */
	public function getPagingSearchResult(): PagingSearchResult
	{
		$psr = new PagingSearchResult();
		$psr->total = $this->count;
		$psr->page = $this->page;
		$psr->perpage = $this->perPage;
		$psr->totalpage = $this->totalPage;
		return $psr;
	}
}
