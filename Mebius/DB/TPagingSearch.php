<?php

declare(strict_types=1);

namespace Mebius\DB;

use Mebius\Paging\{PagingSearchResult, PagingCaluculator};

/**
 * TPagingSearch paging 情報付き検索用トレイト
 */
trait TPagingSearch
{
	abstract public function executeSelectQuery(string $sql, string $classname, array $placeHolder = []): array;

	/**
	 * ページング検索で使用する汎用メソッド
	 * DBHandlerBase2 を継承したクラスから呼び出すことを想定
	 * @param string $table テーブル名
	 * @param ConditionBuilder2 $cb 検索条件クラスインスタンス
	 * @param string $className 検索結果を保持するためのクラス名 Hoge::class などで指定
	 * @param int $perPage 一回の検索で取得する件数
	 * @param int $page 希望の取得ページ数
	 */
	public function searchEntity(
		string $table,
		ConditionBuilder2 $cb,
		string $className,
		int $perPage,
		int $page
	): PagingSearchResult {
		$countSQL = \sprintf("SELECT count(0) AS cnt FROM %s %s", $table, $cb->getWhere());
		$searchSQL = \sprintf("SELECT * FROM %s %s LIMIT ?,?", $table, $cb->getWhere());

		$countResults = $this->executeSelectQuery($countSQL, \stdClass::class, $cb->getPlaceholder());
		$count = $countResults[0]->cnt;

		$pc = new PagingCaluculator($count, $perPage, $page);
		$psr = $pc->getPagingSearchResult();

		$placeHolder = $cb->getPlaceholder();
		$placeHolder[] = $pc->getOffset();
		$placeHolder[] = $perPage;

		$searchResult = $this->executeSelectQuery($searchSQL, $className, $placeHolder);
		$psr->data = $searchResult;

		return $psr;
	}
}
