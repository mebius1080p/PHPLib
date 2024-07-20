<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;
use Mebius\Validator\{ValidateInterface, Util};

/**
 * StrGreaterThanEqual 文字数制限バリデーター
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class StrGreaterThanEqual implements ValidateInterface
{
	private int $min = 0;
	public function __construct(int $min)
	{
		if ($min <= 0) {
			throw new \Exception("min number must be greater than 0", 1);
		}
		$this->min = $min;
	}
	public function validate(string $name, mixed $value): void
	{
		Util::checkUTF8($name, $value);

		if (mb_strlen($value) < $this->min) {
			throw new \Exception("{$this->min}文字以上を設定してください", 1);
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
