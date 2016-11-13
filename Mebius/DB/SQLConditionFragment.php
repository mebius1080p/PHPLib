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
	/**
	*値が文字列かどうかを判定するメソッド。
	*@param {mixed} $mustBeStr チェックする変数
	*@throws {Exception} 引数が文字列でなかったときに例外
	*/
	protected function checkString($mustBeStr)
	{
		if (gettype($mustBeStr) !== "string") {
			throw new \Exception("引数は文字列でなくてはなりません", 1);
		}
	}
}
