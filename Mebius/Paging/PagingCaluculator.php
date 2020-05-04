<?php

declare(strict_types=1);

namespace Mebius\Paging;

/**
 * PagingCaluculator ページング周りのもろもろを計算して取り出せるようにするクラス
 */
class PagingCaluculator
{
	/**
	 *@var int 検索したときにヒットする件数
	 */
	private int $recordCount = 0;
	/**
	 * @var int 一回の検索で取り出す最大件数
	 */
	private int $perPage = 1;
	/**
	 * @var int 投稿されてきた、取り出す希望のページ数
	 */
	private int $postedPage = 1;

	/**
	 * @var int 返すページ数
	 */
	private int $outPage = 1;
	/**
	 * @var int データのページ数
	 */
	private int $totalPage = 1;
	/**
	 * @var int sql で使うデータ取得オフセット値。0 から始まる
	 */
	private int $offset = 0;
	/**
	 * コンストラクタ
	 * @param int $recordCount 検索してヒットするレコード数 0 でもよい
	 * @param int $perPage 一回の検索で表示するレコードの数 1 以上
	 * @param int $postedPage 投稿されてきた、取得する希望のページ数 <= 1
	 */
	public function __construct(int $recordCount, int $perPage, int $postedPage)
	{
		if ($recordCount < 0) {
			throw new \Exception("rec count must be great or equal than 0", 1);
		}
		if ($perPage <= 0) {
			throw new \Exception("per page must be great or equal than 1", 1);
		}
		if ($postedPage <= 0) {
			throw new \Exception("posted page must be great or equal than 1", 1);
		}
		$this->recordCount = $recordCount;
		$this->perPage = $perPage;
		$this->postedPage = $postedPage;
		$this->calcPage();
	}
	/**
	 * 取得するページ数 1 以上を返す
	 * @return int 取得するページ数
	 */
	public function getOutPage(): int
	{
		return $this->outPage;
	}
	/**
	 * sql で使うオフセット値を返す
	 * @return int sql で使うオフセット値
	 */
	public function getOffset(): int
	{
		return $this->offset;
	}
	/**
	 * トータルページ数を返す
	 * @return int トータルページ数
	 */
	public function getTotalPage(): int
	{
		return $this->totalPage;
	}
	/**
	 * PagingSearchResult のインスタンスを返すメソッド
	 * @return PagingSearchResult PagingSearchResult のインスタンス
	 */
	public function getPagingSearchResult(): PagingSearchResult
	{
		$psr = new PagingSearchResult();
		$psr->total = $this->recordCount;
		$psr->page = $this->outPage;
		$psr->perpage = $this->perPage;
		$psr->totalpage = $this->totalPage;
		return $psr;
	}
	//--------------------------
	/**
	 * トータルページ数やレコード取り出しに使う sql のオフセットの値を計算するメソッド
	 */
	private function calcPage(): void
	{
		if ($this->perPage >= $this->recordCount) {
			return;
		}
		$totalPage = floor($this->recordCount / $this->perPage);
		if ($this->recordCount % $this->perPage !== 0) {
			$totalPage++;
		}
		$this->totalPage = (int)$totalPage;

		if ($this->totalPage >= $this->postedPage) {//トータルページ以内
			$this->outPage = $this->postedPage;
		}

		$this->offset = $this->perPage * ($this->outPage - 1);
	}
}
