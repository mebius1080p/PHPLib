<?php

declare(strict_types=1);

namespace Mebius\DB;

/**
 * PDOUtil PDO 関連ユーティリティークラス
 */
class PDOUtil
{
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
	 * PDO 作成メソッド
	 * @param string $dsn DB 接続の DSN 文字列
	 * @param string $user DB 接続のユーザー名
	 * @param string $pass DB 接続のパスワード
	 * @return \PDO;
	 * @throws \PDOException db 接続失敗時に例外が出るらしい
	 */
	public static function createPDO(string $dsn, string $user, string $pass): \PDO
	{
		$pdoOption = [
			\PDO::MYSQL_ATTR_MULTI_STATEMENTS => false, // 複文禁止
		];
		$pdo = new \PDO($dsn, $user, $pass, $pdoOption);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); // 静的プレースホルダを指定
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // 例外を投げる
	}
}
