<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;
use Mebius\Validator\{ValidateInterface, Util};

/**
 * Date 日付バリデーター
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Date implements ValidateInterface
{
	private const RE_DATE = "/\A2[0-9]{3}-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][0-9]|3[0-1])\z/u";

	private string $fixedValue = "";
	public function __construct()
	{
		//dd;
	}
	public function validate(string $name, mixed $value): void
	{
		Util::checkUTF8($name, $value);

		$matches = [];
		$matchResult = preg_match(self::RE_DATE, $value, $matches);
		if ($matchResult !== 1) {
			throw new \Exception("不正な日付", 1);
		}

		//念のため datetime でもチェック 06-31 は 07-01 と解釈される
		$dt = new \DateTimeImmutable($value);//@phan-suppress-current-line PhanUnusedVariable
		$this->fixedValue = $dt->format("Y-m-d");
	}
	public function hasFixedValue(): bool
	{
		return true;
	}
	public function getFixedValue(): mixed
	{
		return $this->fixedValue;
	}
}
