<?php

declare(strict_types=1);

use Mebius\Validator\ValidateTrait;
use Mebius\Validator\Rule\{Tel, Date, Mandatory, NumRange, Regex, Postal, Mail, MailSimple, Same};

/**
 * SampleInput テスト用サンプルクラス
 */
class SampleInput
{
	use ValidateTrait;

	#[Tel]
	public string $tel = "";
	#[Date]
	public string $date = "";
	#[Mandatory]
	public string $name = "";
	#[NumRange(2, 5)]
	public int $number = 0;
	#[Regex("/\A[a-z]{5}\z/")]
	public string $regsample = "";
	#[Postal]
	public string $postal = "";
	#[Mail]
	public string $mail1 = "";
	#[MailSimple]
	public string $mail2 = "";
	#[Same("str2")]
	public string $str1 = "";
	public string $str2 = "";
	public function __construct()
	{
		//dd;
	}
}
