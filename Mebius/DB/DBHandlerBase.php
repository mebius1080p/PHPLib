<?php
namespace Mebius\DB;

/**
*DBHandlerBase データマッパー用モデルクラスの基底クラス
*/
abstract class DBHandlerBase
{
	/**
	*@var PDO pdo のインスタンス
	*/
	protected $pdo = null;
	/**
	*コンストラクタ
	*@param string $dsn DB 接続の DSN 文字列
	*@param string $user DB 接続のユーザー名
	*@param string $pass DB 接続のパスワード
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
	*デストラクタ
	*/
	public function __destruct()
	{
		$this->pdo = null;
	}
}
