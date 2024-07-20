<?php

declare(strict_types=1);

namespace Mebius\Validator;

/**
 * Util バリデーション用ユーティリティークラス
 */
class Util
{
	/**
	 * 文字列チェックメソッド
	 * @param string $name パラメーター名
	 * @param mixed $value 調べる値
	 * @throws \Exception エラーで例外
	 */
	public static function checkUTF8(string $name, mixed $value): void
	{
		if (!is_string($value)) {
			throw new \Exception("値が文字列ではありません:{$name}", 1);
		}
		if (!mb_check_encoding($value, 'UTF-8')) {//攻撃の可能性
			throw new \Exception("パラメーターが UTF-8 ではありません");
		}
	}
}
