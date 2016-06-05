<?php
namespace Mebius\DB;

/**
* SelectQueryBuilder
*/
class SelectQueryBuilder extends QueryBuilder
{
	const BASE_SQL = "SELECT %s FROM %s";
	public function __construct($columnStr, $tableName, array $cond = [], $isLogical = false)
	{
		$this->sql = sprintf(self::BASE_SQL, $columnStr, $tableName);
		if (count($cond) > 0) {
			$this->sql .= " WHERE ";
			$tempCondArray = [];
			foreach ($cond as $key) {//$key は PlaceHolderFragment
				$this->data[] = $key->getValue();
				$tempCondArray[] = $key->getFragment();
			}
			if ($isLogical) {
				$this->sql .= implode(" OR ", $tempCondArray);
			} else {
				$this->sql .= implode(" AND ", $tempCondArray);
			}
		}
	}
	public function stashCurrentCondition(){
		$conditionMatch = [];
		$r = preg_match("/WHERE (.+)/", $this->sql, $conditionMatch);
		$currentCondition = $conditionMatch[1];
		$this->sql = str_replace($currentCondition, "($currentCondition)", $this->sql);
	}
	public function addFragment(StandardConditionFragment $frag){//and だけ対応
		if ($frag !== null) {
			$this->sql .= " AND " . $frag->getFragment();
			$this->data[] = $frag->getValue();
		}
	}
}
