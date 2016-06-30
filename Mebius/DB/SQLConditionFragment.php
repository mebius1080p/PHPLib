<?php
namespace Mebius\DB;

/**
* SQLConditionFragment
*/
abstract class SQLConditionFragment
{
	protected $value = "";
	protected $fragment = "";
	public function getFragment(){
		return $this->fragment;
	}
	public function getValue(){
		return $this->value;
	}
	protected function checkString($mustBeStr){
		if (gettype($mustBeStr) !== "string") {
			throw new \Exception("引数は文字列でなくてはなりません", 1);
		}
	}
}
