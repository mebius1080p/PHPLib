<?php
namespace Mebius\DB;

/**
* StandardConditionFragment
*/
class StandardConditionFragment extends SQLConditionFragment
{
	/**
	*@param {string} $column where 句で用いられるカラム名の文字列
	*@param {string} $operator where 句で用いられる演算子の文字列
	*@param {string|int|double} $value placeholder で当てはめられる値
	*/
	public function __construct($column, $operator, $value)
	{
		$this->checkString($column);
		$this->checkOperator($operator);
		$this->value = $this->checkValue($value);
		$this->fragment = sprintf("%s %s ?", $column, $operator);
	}
	/**
	*演算子が規定の文字であるかをチェックするメソッド
	*@param {string} $op 演算子の文字列
	*/
	private function checkOperator($op)
	{
		$this->checkString($op);
		$isValidOperator = false;
		$validOperator = ["=", "<", ">", "<=", ">=", "LIKE", "NOT LIKE"];
		foreach ($validOperator as $key) {
			if ($op === $key) {
				$isValidOperator = true;
				break;
			}
		}
		if (!$isValidOperator) {
			throw new \Exception("オペレーターが間違っています", 1);
		}
	}
	/**
	*コンストラクタで渡された value が文字列か数値であるかをチェックするメソッド
	*@param {mixed} $mustBeStrOrNumber チェックする値
	*@return {string|int|double} チェックした値
	*@throws {Exception} 引数が不正な値だった場合に例外
	*/
	private function checkValue($mustBeStrOrNumber)
	{
		if (gettype($mustBeStrOrNumber) !== "string") {
			if (gettype($mustBeStrOrNumber) === "integer" || gettype($mustBeStrOrNumber) === "double") {
				return strval($mustBeStrOrNumber);
			} else {
				throw new \Exception("引数は文字列か数値でなくてはなりません", 1);
			}
		} else {
			return $mustBeStrOrNumber;
		}
	}
}
