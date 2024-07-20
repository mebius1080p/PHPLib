<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;

/**
 * Postal 郵便番号バリデーター
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Postal extends Regex
{
	private const RE_POSTAL = "/\A[0-9]{3}-?[0-9]{4}\z/";
	public function __construct()
	{
		parent::__construct(self::RE_POSTAL);
	}
	public function validate(string $name, mixed $value): void
	{
		try {
			parent::validate($name, $value);
		} catch (\Exception $e) {
			throw new \Exception("不正な郵便番号", 1);
		}
	}
}
