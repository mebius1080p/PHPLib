<?php

declare(strict_types=1);

namespace Mebius\DB;

/**
 * TGetById 主キーでデータを取り出すメソッドをもつトレイト
 */
trait TGetById
{
	abstract public function executeSelectQuery(string $sql, string $classname, array $placeHolder = []): array;

	/**
	 * 主キーなどの id から単一のデータを取り出すメソッド
	 * ジェネリクスを使用して書くと、getById<T>(...): T
	 * @param string $sql sql 文
	 * @param string $classname 完全修飾クラス名。stdClass::class でも可
	 * @param int $id 主キー
	 * @param string $itemname 例外メッセージに設定するアイテム名
	 * @return mixed $classname のインスタンス
	 * @throws \Exception データが無かったときなど、エラーで例外
	 */
	public function getById(string $sql, string $classname, int $id, string $itemname): mixed
	{
		$records = $this->executeSelectQuery($sql, $classname, [$id]);
		if (count($records) === 0) {
			throw new \Exception("${itemname} がみつかりません:" . (string)$id, 1);
		}
		return $records[0];
	}
}
