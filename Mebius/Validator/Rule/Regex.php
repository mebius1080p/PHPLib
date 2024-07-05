<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;
use Mebius\Validator\{ValidateInterface, Util};

/**
 * Regex 正規表現バリデーター
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Regex implements ValidateInterface
{
	private string $pattern = "";
	public function __construct(string $pattern)
	{
		if ($pattern === "") {
			throw new \Exception("パターンを指定してください", 1);
		}
		$this->pattern = $pattern;
	}
	public function validate(string $name, mixed $value): void
	{
		Util::checkUTF8($name, $value);

		$matches = [];
		$matchResult = preg_match($this->pattern, $value, $matches);
		if ($matchResult !== 1) {
			throw new \Exception("パターンマッチしません", 1);
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
