<?php
namespace Mebius\DB;

/**
* PDOHandler
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
	* @param $dbType {number} このクラスの MYSQL か SQLITE
	* @param $dbName {string} DB name か sqlite のファイルパス
	* @param $user {string} ユーザー
	* @param $user {string} パスワード
	*/
	public function __construct($dbType, $dbName, $user = "", $pass = "")
	{
		$dsnArray = [self::DSN_MYSQL, self::DSN_SQLITE];
		$dsn = sprintf($dsnArray[$dbType], $dbName);
		$this->pdo = new \PDO($dsn, $user, $pass);
		$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); // 静的プレースホルダを指定
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); //: 例外を投げる
	}
	public function execute(QueryBuilder $qb){//一件のクエリ用
		$this->resultData = [];//初期化
		$sth = $this->pdo->prepare($qb->getSQL());
		$this->executeResult = $sth->execute($qb->getData());
		if ($this->executeResult) {
			$this->resultData = $sth->fetchAll(\PDO::FETCH_OBJ);
		}
	}
	/**
	* @param {QueryBuilder} $qb data には二次元配列を持っていること
	*/
	public function executeTransactionWithMultipleData(QueryBuilder $qb){
		$this->resultData = [];//初期化
		$sth = $this->pdo->prepare($qb->getSQL());
		try {
			$this->pdo->beginTransaction();
			$data = $qb->getData();//二次元配列「
			foreach ($data as $key) {
				$sth->execute($key);
			}
			$this->pdo->commit();
		} catch (\Exception $e) {
			$this->pdo->rollBack();
			throw new \Exception($e, 1);
		}
	}
	public function getResult(){
		return $this->executeResult;
	}
	public function getData(){
		return $this->resultData;
	}
	public function getLastInsertId(){
		return $this->pdo->lastInsertId();
	}
}
