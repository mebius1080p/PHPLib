<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;
use Mebius\Validator\{ValidateInterface, Util};

/**
 * Mandatory 必須チェックするルール
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Mandatory implements ValidateInterface
{
	public function __construct()
	{
		//dd;
	}
	public function validate(string $name, mixed $value): void
	{
		if (is_string($value)) {
			Util::checkUTF8($name, $value);
		}
		if ($value === "" || $value === 0) {
			throw new \Exception("値を設定してください", 1);
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
