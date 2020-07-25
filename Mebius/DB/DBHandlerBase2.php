<?php

declare(strict_types=1);

namespace Mebius\DB;

/**
 * DBHandlerBase2 データマッパー用モデルクラスの基底クラス
 * 単独でも機能するが、継承して使用することを想定している
 * laravel のコントローラなどで使用できるよう、コンストラクタの引数を撤廃
 * PDO のスタブを使ってテストできるようにした
 */
class DBHandlerBase2
{
	/**
	 * @var ?\PDO pdo のインスタンス
	 */
	protected static ?\PDO $pdo = null;

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		//
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
	 */
	public function executeInsertQuery(string $sql, array $placeHolder = []): int
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
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$insertId = self::$pdo->lastInsertId();
		return (int)$insertId;
	}
	/**
	 * DSN 文字列を作るメソッド (host 指定バージョン)
	 * @param string $host ホスト名。localhost, IP address
	 * @param string $dbName db name
	 * @param string $charset 文字コード
	 * @return string
	 */
	public static function buildMysqlDSN(string $host, string $dbName, string $charset = "utf8mb4"): string
	{
		$template = "mysql:host=%s;dbname=%s;charset=%s";
		$dsn = \sprintf($template, $host, $dbName, $charset);
		return $dsn;
	}
	/**
	 * DSN 文字列を作るメソッド (ソケット指定バージョン)
	 * @param string $dbName db name
	 * @param string $socket unix ソケットパス
	 * @param string $charset 文字コード
	 * @return string
	 */
	public static function buildMysqlDSNSocket(
		string $dbName,
		string $socket = "/var/lib/mysql/mysql.sock",
		string $charset = "utf8mb4"
	): string {
		$template = "mysql:unix_socket=%s;dbname=%s;charset=%s";
		$dsn = \sprintf($template, $socket, $dbName, $charset);
		return $dsn;
	}
	/**
	 * PDO 作成メソッド 通常はこれを使って PDO を設定する
	 * @param string $dsn DB 接続の DSN 文字列
	 * @param string $user DB 接続のユーザー名
	 * @param string $pass DB 接続のパスワード
	 * @throws \PDOException db 接続失敗時に例外が出るらしい
	 */
	public static function setupPDO(string $dsn, string $user, string $pass): void
	{
		$pdoOption = [];
		if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
			$pdoOption[\PDO::MYSQL_ATTR_MULTI_STATEMENTS] = false; // 複文禁止
		}
		self::$pdo = new \PDO($dsn, $user, $pass, $pdoOption);
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		self::$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); // 静的プレースホルダを指定
		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // 例外を投げる
	}
	/**
	 * pdo セットメソッド 主にテスト用
	 * @param \PDO $pdo PDO
	 */
	public static function setPDO(\PDO $pdo): void
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
