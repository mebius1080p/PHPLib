<?php
namespace Mebius\DB;

/**
* SQLConditionFragment
*/
abstract class SQLConditionFragment
{
	protected $value = "";
	protected $fragment = "";
	/**
	*sql 断片を取得
	*@return {string} sql 断片
	*/
	public function getFragment()
	{
		return $this->fragment;
	}
	/**
	*placeholder に当てはめられる値を取得
	*@return {string|int|double} placeholder に当てはめられる値
	*/
	public function getValue()
	{
		return $this->value;
	}
}
