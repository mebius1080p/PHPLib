<?php

declare(strict_types=1);

namespace Mebius\DB;

/**
 * TCommit commit メソッドのトレイト
 */
trait TCommit
{
	abstract public function executeInsertQuery(string $sql, array $placeHolder = []): int;
	abstract public function begin(): void;
	abstract public function commit(): void;
	abstract public function rollback(): void;

	/**
	 * 単一のレコードをトランザクションを使って書き込むメソッド
	 * @param string $table テーブル名
	 * @param object $obj 挿入するデータを収めたオブジェクト。プロパティはテーブルのカラムと 1:1 であること
	 * @return int insertid 使うかどうかは使用元で決めること
	 * @throws \Exception エラーで例外
	 */
	public function simpleCommit(string $table, object $obj): int
	{
		$columns = [];
		$placeHolder = [];
		$ref = new \ReflectionClass($obj);
		$properties = $ref->getProperties();
		foreach ($properties as $prop) {
			$propName = $prop->getName();
			$columns[] = $propName;
			$placeHolder[] = $obj->$propName;
		}

		$ih = new InsertHelper2($table, $columns);
		$sql = $ih->getOnDuplicateSQL();
		$this->begin();
		try {
			$id = $this->executeInsertQuery($sql, $placeHolder);
			$this->commit();
		} catch (\Exception $e) {
			$this->rollback();
			throw $e;
		}
		return $id;
	}
}
