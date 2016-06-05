<?php
namespace Mebius\DB;

/**
* DeleteQueryBuilder
*/
class DeleteQueryBuilder extends QueryBuilder
{
	const BASE_SQL = "DELETE FROM %s";
	public function __construct($tableName, array $cond, $isLogical = false)
	{
		$this->sql = sprintf(self::BASE_SQL, $tableName);
		if (count($cond) > 0) {
			$this->sql .= " WHERE ";
			$tempCondStr = [];
			foreach ($cond as $key) {//$key は CompareFragment
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
