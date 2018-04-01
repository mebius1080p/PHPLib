<?php
declare(strict_types=1);
namespace Mebius\DB;

/**
* DeleteQueryBuilder
*/
class DeleteQueryBuilder extends QueryBuilder
{
	const BASE_SQL = "DELETE FROM %s";
	/**
	*@param {string} $tableName テーブル名
	*@param {SQLConditionFragment[]} $cond where 移行で使用する条件を含む SQLConditionFragment の配列
	*@param {boolean} $isLogical where 句を OR でつなぐか AND でつなぐかのフラグ
	*/
	public function __construct($tableName, array $cond, $isLogical = false)
	{
		$this->sql = sprintf(self::BASE_SQL, $tableName);
		if (count($cond) > 0) {
			$this->sql .= " WHERE ";
			$tempCondStr = [];
			foreach ($cond as $key) {//$key は SQLConditionFragment
				$this->data[] = $key->getValue();
				$tempCondStr[] = $key->getFragment();
			}
			if ($isLogical) {
				$this->sql .= implode(" OR ", $tempCondStr);
			} else {
				$this->sql .= implode(" AND ", $tempCondStr);
			}
		}
	}
}
