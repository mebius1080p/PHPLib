<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;
use Mebius\Validator\{ValidateInterface, Util};

/**
 * MailSimple メールアドレスバリデーター (filter_var バージョン)
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class MailSimple implements ValidateInterface
{
	public function __construct()
	{
		//dd;
	}
	public function validate(string $name, mixed $value): void
	{
		Util::checkUTF8($name, $value);
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			throw new \Exception("不正なメールアドレス", 1);
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
