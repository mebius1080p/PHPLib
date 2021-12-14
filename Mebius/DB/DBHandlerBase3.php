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
	 * @var string $currentConnection 現在の接続名(連想配列のキー)
	 */
	private string $currentConnection = "default";
	/**
	 * @var array $pdoAssoc 複数の PDO を保持する連想配列
	 */
	private static array $pdoAssoc = [];

	/**
	 * コンストラクタ
	 * @param \PDO $pdo pdo オブジェクト
	 * @param string $connectionName 接続名
	 * @throws \Exception 接続名が空の時例外
	 */
	public function __construct(\PDO $pdo, string $connectionName = "default")
	{
		if ($connectionName === "") {
			throw new \Exception("empty connection name", 1);
		}

		$this->currentConnection = $connectionName;
		//上書きはしない　設定されていないときだけ格納する
		if (!array_key_exists($connectionName, self::$pdoAssoc)) {
			self::$pdoAssoc[$connectionName] = $pdo;
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
		$currentPDO = $this->getPDO();
		if ($currentPDO === null) {
			throw new \Exception("pdo is null", 1);
		}

		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$sth = $currentPDO->prepare($sql);
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
		//v8 より常に配列を返すようになったらしい
		return $fetchedData;
	}
	/**
	 * トランザクション開始メソッド
	 * @throws \Exception pdo 未設定や多重トランザクションスタートで例外
	 */
	public function begin(): void
	{
		$currentPDO = $this->getPDO();
		if ($currentPDO === null) {
			throw new \Exception("[begin]pdo is null", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		if ($currentPDO->inTransaction()) {
			//入れ子トランザクションはサポートせず
			throw new \Exception("nesting transaction not supported", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$transactionBegun = $currentPDO->beginTransaction();
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
		$currentPDO = $this->getPDO();
		if ($currentPDO === null) {
			throw new \Exception("[commit]pdo is null", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		if (!$currentPDO->inTransaction()) {//非トランザクション
			throw new \Exception("not in transaction commit", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$hasCommit = $currentPDO->commit();
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
		$currentPDO = $this->getPDO();
		if ($currentPDO === null) {
			throw new \Exception("[rollback]pdo is null", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		if (!$currentPDO->inTransaction()) {//非トランザクション
			throw new \Exception("not in transaction rollback", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$hasRolledBack = $currentPDO->rollBack();
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
		$insertId = $this->getPDO()->lastInsertId();
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
		$currentPDO = $this->getPDO();
		if ($currentPDO === null) {
			throw new \Exception("pdo is null", 1);
		}
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$sth = $currentPDO->prepare($sql);
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
	public function getPDO(): ?\PDO
	{
		if (array_key_exists($this->currentConnection, self::$pdoAssoc)) {
			return self::$pdoAssoc[$this->currentConnection];
		} else {
			return null;
		}
	}
	/**
	 * 現在の接続名を返すメソッド
	 * @return string 現在の接続名
	 */
	public function getCurrentConnectionName(): string
	{
		return $this->currentConnection;
	}
	/**
	 * PDO 解放メソッド 主にテスト用
	 */
	public static function resetPDO(): void
	{
		self::$pdoAssoc = [];
	}
}
