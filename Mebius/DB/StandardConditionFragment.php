<?php
namespace Mebius\DB;

/**
* StandardConditionFragment
*/
class StandardConditionFragment extends SQLConditionFragment
{
	public function __construct($column, $operator, $value)
	{
		$this->checkString($column);
		$this->checkOperator($operator);
		$this->value = $this->checkValue($value);
		$this->fragment = sprintf("%s %s ?", $column, $operator);
	}
	private function checkOperator($op){
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
	private function checkValue($mustBeStrOrNumber){
		if (gettype($mustBeStrOrNumber) !== "string") {
			if (gettype($mustBeStrOrNumber) === "integer" || gettype($mustBeStrOrNumber) === "double") {
				return strval($mustBeStrOrNumber);
			} else {
				throw new \Exception("引数は文字列か数値でなくてはなりません", 1);
			}
		} else{
			return $mustBeStrOrNumber;
		}
	}
}
