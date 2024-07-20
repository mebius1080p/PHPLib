<?php

declare(strict_types=1);

namespace Mebius\Validator;

/**
 * ValidateInterface バリデーションインターフェース
 */
interface ValidateInterface
{
	/**
	 * バリデートメソッド
	 * @param string $name プロパティの名前
	 * @param mixed $value 値
	 * @throws \Exception エラーで例外
	 */
	public function validate(string $name, mixed $value): void;
	/**
	 * バリデーション後、調整された値を持つかどうかを返すメソッド
	 * @return bool
	 */
	public function hasFixedValue(): bool;
	/**
	 * 調整値を返すメソッド
	 */
	public function getFixedValue(): mixed;
}
