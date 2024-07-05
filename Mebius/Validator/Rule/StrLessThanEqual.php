<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;
use Mebius\Validator\{ValidateInterface, Util};

/**
 * StrLessThanEqual 文字数制限バリデーター
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class StrLessThanEqual implements ValidateInterface
{
	private int $max = 0;
	public function __construct(int $max)
	{
		if ($max <= 1) {
			throw new \Exception("max number must be greater than 1", 1);
		}
		$this->max = $max;
	}
	public function validate(string $name, mixed $value): void
	{
		Util::checkUTF8($name, $value);

		if (mb_strlen($value) > $this->max) {
			throw new \Exception("{$this->max}文字まで設定可能です", 1);
		}
	}
	public function hasFixedValue(): bool
	{
		return false;
	}
	public function getFixedValue(): mixed
	{
		return null;
	}
}
