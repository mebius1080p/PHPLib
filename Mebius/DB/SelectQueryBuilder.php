<?php
namespace Mebius\DB;

/**
* SelectQueryBuilder
*/
class SelectQueryBuilder extends QueryBuilder
{
	const BASE_SQL = "SELECT %s FROM %s";
	/**
	*@param {string} $columnStr select 文に使用するカラムの文字列
	*@param {string} $tableName テーブル名
	*@param {array} $cond select where 移行で使用する条件を含む SQLConditionFragment の配列
	*@param {boolean} $isLogical where 句を連結するときに OR にするか AND にするかのフラグ
	*/
	public function __construct($columnStr, $tableName, array $cond = [], $isLogical = false)
	{
		$this->sql = sprintf(self::BASE_SQL, $columnStr, $tableName);
		if (count($cond) > 0) {
			$this->sql .= " WHERE ";
			$tempCondArray = [];
			foreach ($cond as $key) {//$key は SQLConditionFragment
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
	/**
	*呼び出し時の sql 文で、where より後の文字列を括弧でくくるメソッド 複雑な where 句作成のために使用
	*/
	public function stashCurrentCondition()
	{
		$conditionMatch = [];
		preg_match("/WHERE (.+)/", $this->sql, $conditionMatch);
		$currentCondition = $conditionMatch[1];
		$this->sql = str_replace($currentCondition, "($currentCondition)", $this->sql);
	}
	/**
	*複雑な where 句作成のために使用。and だけ対応
	*@param {StandardConditionFragment} $frag sql 条件の追加断片
	*/
	public function addFragment(StandardConditionFragment $frag)
	{
		if ($frag !== null) {
			$this->sql .= " AND " . $frag->getFragment();
			$this->data[] = $frag->getValue();
		}
	}
}
