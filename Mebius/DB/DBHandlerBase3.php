<?php

declare(strict_types=1);

namespace Mebius\DB;

/**
 * DBHandlerBase3 データマッパー用モデルクラスの基底クラス
 * 単独でも機能するが、継承して使用することを想定している
 * PDO のスタブを使ってテストできるようにした
 */
class DBHandlerBase3
{
	/**
	 * @var ?\PDO pdo のインスタンス
	 */
	protected static ?\PDO $pdo = null;

	/**
	 * コンストラクタ
	 * @param \PDO $pdo pdo オブジェクト
	 */
	public function __construct(\PDO $pdo)
	{
		if (self::$pdo === null) {
			self::$pdo = $pdo;
		}
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
		if (self::$pdo === null) {
			throw new \Exception("pdo is null", 1);
		}

		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$sth = self::$pdo->prepare($sql);
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
	 * トランザクション開始メソッド
	 * @throws \Exception pdo 未設定や多重トランザクションスタートで例外
	 */
	public function begin(): void
	{
		if (self::$pdo === null) {
			throw new \Exception("[begin]pdo is null", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		if (self::$pdo->inTransaction()) {
			//入れ子トランザクションはサポートせず
			throw new \Exception("nesting transaction not supported", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$transactionBegun = self::$pdo->beginTransaction();
		if (!$transactionBegun) {
			throw new \Exception("start transaction failed", 1);
		}
	}
	/**
	 * コミットメソッド
	 * @throws \Exception pdo 未設定やトランザクションでない場合に例外
	 */
	public function commit(): void
	{
		if (self::$pdo === null) {
			throw new \Exception("[commit]pdo is null", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		if (!self::$pdo->inTransaction()) {//非トランザクション
			throw new \Exception("not in transaction commit", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$hasCommit = self::$pdo->commit();
		if (!$hasCommit) {
			throw new \Exception("commit failed", 1);
		}
	}
	/**
	 * ロールバックメソッド
	 * @throws \Exception pdo 未設定やトランザクションでない場合に例外
	 */
	public function rollback(): void
	{
		if (self::$pdo === null) {
			throw new \Exception("[rollback]pdo is null", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		if (!self::$pdo->inTransaction()) {//非トランザクション
			throw new \Exception("not in transaction rollback", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$hasRolledBack = self::$pdo->rollBack();
		if (!$hasRolledBack) {
			throw new \Exception("rollback failed", 1);
		}
	}
	/**
	 * insert クエリ実行メソッド
	 * @param string $sql クエリ
	 * @param array $placeHolder プレースホルダーの配列
	 * @return int
	 * @throws \Exception エラーで例外
	 */
	public function executeInsertQuery(string $sql, array $placeHolder = []): int
	{
		$this->executeQuery($sql, $placeHolder);
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$insertId = self::$pdo->lastInsertId();
		return (int)$insertId;
	}
	/**
	 * 単純なクエリ実行メソッド
	 * @param string $sql クエリ
	 * @param array $placeHolder プレースホルダーの配列
	 * @throws \Exception エラーで例外
	 */
	public function executeQuery(string $sql, array $placeHolder = []): void
	{
		if (self::$pdo === null) {
			throw new \Exception("pdo is null", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$sth = self::$pdo->prepare($sql);
		if ($sth === false) {
			throw new \Exception("prepare sql failed", 1);
		}
		$hasExecuted = $sth->execute($placeHolder);
		if ($hasExecuted === false) {
			throw new \Exception("statement execution failed", 1);
		}
	}
	/**
	 * pdo 取得メソッド 主にテスト用
	 * @return ?\PDO
	 */
	public static function getPDO(): ?\PDO
	{
		return self::$pdo;
	}
	/**
	 * pdo 入れ替えメソッド 主にテスト用
	 * @param \PDO $pdo PDO
	 */
	public static function replacePDO(\PDO $pdo): void
	{
		self::$pdo = $pdo;
	}
	/**
	 * PDO 解放メソッド 主にテスト用
	 */
	public static function resetPDO(): void
	{
		self::$pdo = null;
	}
}
