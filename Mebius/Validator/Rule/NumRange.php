<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;
use Mebius\Validator\{ValidateInterface, Util};

/**
 * NumRange 数値範囲バリデーター
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class NumRange implements ValidateInterface
{
	private int $from = 0;
	private int $to = 0;
	public function __construct(int $from, int $to)
	{
		if ($from > $to) {
			throw new \Exception("invalid from_to argument", 1);
		}
		$this->from = $from;
		$this->to = $to;
	}
	public function validate(string $name, mixed $value): void
	{
		if (!is_int($value) && !is_float($value)) {
			throw new \Exception("数値のみチェックできます", 1);
		}
		if ($value < $this->from || $this->to < $value) {
			throw new \Exception("{$this->from}以上{$this->to}以下を入力できます", 1);
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
