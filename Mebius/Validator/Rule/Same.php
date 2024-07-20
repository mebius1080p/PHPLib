<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;
use Mebius\Validator\{ValidateInterface, Util};

/**
 * Same description...
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Same implements ValidateInterface
{
	private string $propName = "";
	private string $targetValue = "";
	public function __construct(string $targetPropName)
	{
		if ($targetPropName === "") {
			throw new \Exception("ターゲットプロパティ名を指定してください", 1);
		}
		$this->propName = $targetPropName;
	}
	public function validate(string $name, mixed $value): void
	{
		Util::checkUTF8($name, $value);

		if ($value !== $this->targetValue) {
			throw new \Exception("値が一致しません", 1);
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

	public function getTargetPropName(): string
	{
		return $this->propName;
	}
	public function setTargetValue(string $val): void
	{
		$this->targetValue = $val;
	}
}
