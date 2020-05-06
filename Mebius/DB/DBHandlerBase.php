<?php

declare(strict_types=1);

namespace Mebius\DB;

/**
 * DBHandlerBase データマッパー用モデルクラスの基底クラス
 */
abstract class DBHandlerBase
{
	/**
	 * @var ?\PDO pdo のインスタンス
	 */
	protected ?\PDO $pdo = null;
	/**
	 * コンストラクタ
	 * @param string $dsn DB 接続の DSN 文字列
	 * @param string $user DB 接続のユーザー名
	 * @param string $pass DB 接続のパスワード
	 */
	public function __construct(string $dsn, string $user, string $pass)
	{
		$pdoOption = [];
		if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
			$pdoOption[\PDO::MYSQL_ATTR_MULTI_STATEMENTS] = false; // 複文禁止
		}
		$this->pdo = new \PDO($dsn, $user, $pass, $pdoOption);
		$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); // 静的プレースホルダを指定
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // 例外を投げる
	}
	/**
	 * デストラクタ
	 */
	public function __destruct()
	{
		$this->pdo = null;
	}
	/**
	 * select 系クエリを実行するメソッド
	 * @param string $sql sql 文
	 * @param string $classname 完全修飾クラス名
	 * @param array $placeHolder プレースホルダーに割り当てる配列
	 * @return array $classname のインスタンス配列
	 * @throws \Exception エラーで例外
	 */
	public function executeSelectQuery(string $sql, string $classname, array $placeHolder = []): array
	{
		if ($this->pdo === null) {
			throw new \Exception("pdo is null", 1);
		}
		$sth = $this->pdo->prepare($sql);
		if ($sth === false) {
			throw new \Exception("prepare sql failed", 1);
		}
		$hasFetched = $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $classname);
		if ($hasFetched === false) {
			throw new \Exception("fetch failed", 1);
		}
		$hasExecuted = $sth->execute($placeHolder);
		if ($hasExecuted === false) {
			throw new \Exception("statement execution failed", 1);
		}
		$fetchedData = $sth->fetchAll();
		if ($fetchedData === false) {
			throw new \Exception("fetch all failed", 1);
		}
		return $fetchedData;
	}
	/**
	 * シンプルな insert 文実行メソッド
	 * @param string $table テーブル名
	 * @param array $columns カラム名の配列
	 * @param array $values 挿入値 順番は $columns に合わせておく
	 * @throws \Exception エラーで例外
	 */
	public function executeInsertQuery(string $table, array $columns, array $values): int
	{
		if ($this->pdo === null) {
			throw new \Exception("pdo is null", 1);
		}
		$id = 0;
		$ih = new InsertHelper2($table, $columns);
		$sql = $ih->getInsertSQL();
		$this->pdo->beginTransaction();
		try {
			$sth = $this->pdo->prepare($sql);
			if ($sth === false) {
				throw new \Exception("prepare sql failed", 1);
			}
			$hasExecuted = $sth->execute($values);
			if ($hasExecuted === false) {
				throw new \Exception("statement execution failed", 1);
			}
			$id = intval($this->pdo->lastInsertId());
			$this->pdo->commit();
		} catch (\Exception $e) {
			$this->pdo->rollBack();
			throw $e;
		}
		return $id;
	}
}
