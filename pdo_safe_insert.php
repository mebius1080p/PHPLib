<?php
function pdo_safe_insert($table, array $array){
	$iqb = new InsertQueryBuilder($table, array $array);//連想配列
	return $iqb;
}

/**
* InsertQueryBuilder;
* IQueryBuilder を実装したい…
*/
class InsertQueryBuilder
{
	private $columns = array();
	private $data = array();
	private $q = array();
	private $sql = "";
	const BASE_SQL = "INSERT INTO %s (%s) VALUES(%s)";
	public function __construct($table, array $array)
	{
		foreach ($array as $key => $value) {
			$this->columns[] = $key;
			$this->data[] = $values;
			$this->q[] = "?";
		}
		$this->sql = sprintf(self::BASE_SQL, $table, implode(",", $this->columns), implode(",", $this->q));
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