<?php

declare(strict_types=1);

namespace Mebius\Validator\Rule;

use Attribute;

/**
 * Mail メールバリデーター (マルチバイトメールアドレス対応)
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Mail extends Regex
{
	//http://techracho.bpsinc.jp/hachi8833/2013_09_27/13713
	//https://stackoverflow.com/questions/5219848/how-to-validate-non-english-utf-8-encoded-email-address-in-javascript-and-php
	private const RE_MAIL = "/\A([\p{L}\.\-\d_]+)@([\p{L}\-\.\d_]+)((\.(\p{L}){2,63})+)\z/u";
	public function __construct()
	{
		parent::__construct(self::RE_MAIL);
	}
	public function validate(string $name, mixed $value): void
	{
		try {
			parent::validate($name, $value);
		} catch (\Exception $e) {
			throw new \Exception("不正なメールアドレス", 1);
		}
	}
}
