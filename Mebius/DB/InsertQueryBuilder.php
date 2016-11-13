<?php
namespace Mebius\DB;

/**
* SQL INSERT Builder
*/
class InsertQueryBuilder extends QueryBuilder
{
	const BASE_SQL = "INSERT INTO %s (%s) VALUES (%s)";
	/**
	*@param {string} $tableName テーブル名
	*@param {array} $column 連想配列。
	*/
	public function __construct($tableName, array $column)
	{
		if (count($column) === 0) {
			throw new \Exception("カラムがありません");
		}
		$temp = [];
		$ques = [];
		foreach ($column as $key => $value) {
			$temp[] = $key;
			$ques[] = "?";
			$this->data[] = $value;
		}
		//key は信じる。value は信じない
		$this->sql = sprintf(self::BASE_SQL, $tableName, implode(",", $temp), implode(",", $ques));
	}
	/**
	*複数 insert などで executeTransactionWithMultipleData の前に使用するメソッド
	*@param {array} $data 二次元配列。値チェックはしないので注意して使用する
	*/
	public function setMultipleData(array $data)
	{
		$this->data = $data;
	}
}
