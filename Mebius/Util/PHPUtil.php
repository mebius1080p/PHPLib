<?php

declare(strict_types=1);

namespace Mebius\Util;

/**
 * PHPUtil php ユーティリティークラス
 */
class PHPUtil
{
	/**
	 * オブジェクトにプロパティがあるかチェックするメソッド
	 * @param object $obj チェック対象のオブジェクト
	 * @param string $prop 存在を調べるプロパティ
	 * @throws \Exception エラーで例外
	 */
	public static function propCheck(object $obj, string $prop): void
	{
		if (!property_exists($obj, $prop)) {
			throw new \Exception("property ${prop} not exist", 1);
		}
	}
}
