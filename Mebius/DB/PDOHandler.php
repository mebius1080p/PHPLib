<?php
namespace Mebius\DB;

/**
* PDOHandler mysql, sqlite 両対応
* @class
*/
class PDOHandler
{
	const MYSQL = 0;
	const SQLITE = 1;
	const DSN_MYSQL = "mysql:host=localhost;dbname=%s;charset=utf8";
	const DSN_SQLITE = "sqlite:%s";
	private $pdo;
	private $executeResult;
	private $resultData = [];
	/**
	* @param $dbType {number} 0|1 このクラスの MYSQL か SQLITE
	* @param $dbName {string} DB name か sqlite のファイルパス
	* @param $user {string} ユーザー
	* @param $user {string} パスワード
	*/
	public function __construct($dbType, $dbName, $user = "", $pass = "")
	{
		$dsnArray = [self::DSN_MYSQL, self::DSN_SQLITE];
		$dsn = sprintf($dsnArray[$dbType], $dbName);
		$pdoOption = [];
		if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
			$pdoOption[\PDO::MYSQL_ATTR_MULTI_STATEMENTS] = false;
		}
		$this->pdo = new \PDO($dsn, $user, $pass, $pdoOption);
		$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); // 静的プレースホルダを指定
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); //: 例外を投げる
	}
	/**
	*DB アクセス実行 一件のクエリ用
	*@param {QueryBuilder} $qb クエリビルダーのインスタンス
	*@throws {Exception} DB アクセスでエラーがあったら例外
	*/
	public function execute(QueryBuilder $qb)
	{//
		$this->resultData = [];//初期化
		$sth = $this->pdo->prepare($qb->getSQL());
		$this->executeResult = $sth->execute($qb->getData());
		if ($this->executeResult) {
			$this->resultData = $sth->fetchAll(\PDO::FETCH_OBJ);
		}
	}
	/**
	*トランザクションで sql 実行。例外時はロールバックするためこのメソッド内部で try-catch
	*主に多数の insert などで使用
	* @param {QueryBuilder} $qb クエリビルダーのインスタンス data には二次元配列を持っていること
	*@throws {Exception} DB アクセスでエラーがあったらロールバックした後例外
	*/
	public function executeTransactionWithMultipleData(QueryBuilder $qb)
	{
		$this->resultData = [];//初期化
		$sth = $this->pdo->prepare($qb->getSQL());
		try {
			$this->pdo->beginTransaction();
			$data = $qb->getData();//二次元配列
			foreach ($data as $key) {
				$sth->execute($key);
			}
			$this->pdo->commit();
		} catch (\Exception $e) {
			$this->pdo->rollBack();
			throw new \Exception($e, 1);
		}
	}
	/**
	*sth の実行結果を返す
	*@return {array} sth の実行結果
	*/
	public function getResult()
	{
		return $this->executeResult;
	}
	/**
	*select 文などで取得したデータを返す
	*@return {array} sql文で取得したデータ
	*/
	public function getData()
	{
		return $this->resultData;
	}
	/**
	*execute の後で呼び出すことで lastInsertId を返すメソッド
	*@return {string} lastInsertId
	*/
	public function getLastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
}
