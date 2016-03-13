<?php
function pdo_safe_select($table, array $conditionArray){
	$sqb = new SelectQueryBuilder($table, array $conditionArray);//連想配列
	return $sqb;
}

/**
* InsertQueryBuilder;
* シンプル版
* IQueryBuilder を実装したい…
*/
class SelectQueryBuilder
{
	private $data = array();
	private $sql = "";
	const BASE_SQL = "SELECT * FROM ";
	public function __construct($table, array $conditionArray)
	{
		$this->sql = self::BASE_SQL . $table;
		if (count($conditionArray) > 0) {
			$this->sql .= " WHERE ";
			$fragment = [];
			foreach ($conditionArray as $key => $value) {
				$fragment[] = $key . "=?";
				$this->data[] = $values;
			}
			$this->sql .= implode(" AND ", $fragment);
		}
	}
	public function getSQL()
	{
		return $this->sql;
	}
	public function getData()
	{
		return $this->data;
	}
}

?>