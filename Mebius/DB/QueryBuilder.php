<?php
declare(strict_types=1);
namespace Mebius\DB;

/**
 * コンストラクタなし
 */
abstract class QueryBuilder
{
	protected $sql = "";
	protected $data = [];
	/**
	*sql 文字列を取得
	*@return {string} sql 文字列
	*/
	public function getSQL()
	{
		return $this->sql;
	}
	/**
	*placeholder に当てはめられる値の配列を取得
	*@return {string[]} placeholder に当てはめられる値の配列
	*/
	public function getData()
	{
		return $this->data;
	}
}
