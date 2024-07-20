<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;

/**
 * Tel 電話番号バリデーター
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Tel extends Regex
{
	private const RE_TEL = "/\A[0-9]{1}[0-9\-]+[0-9]{1}\z/";
	public function __construct()
	{
		parent::__construct(self::RE_TEL);
	}
	public function validate(string $name, mixed $value): void
	{
		try {
			parent::validate($name, $value);
		} catch (\Exception $e) {
			throw new \Exception("不正な電話番号", 1);
		}
	}
}
