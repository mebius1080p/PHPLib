<?php
namespace Mebius\DB;

/**
 * コンストラクタなし
 */
abstract class QueryBuilder
{
	protected $sql = "";
	protected $data = [];
	public function getSQL()
	{
		return $this->sql;
	}
	public function getData()
	{
		return $this->data;
	}
}
