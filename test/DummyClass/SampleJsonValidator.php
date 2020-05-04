<?php

declare(strict_types=1);

namespace DummyClass;

use Mebius\Json\JsonValidatorBase;

/**
 * SampleJsonValidator テスト用サンプル json バリデータークラス
 */
class SampleJsonValidator extends JsonValidatorBase
{
	private const SCHEMA = <<<HOGESCHEMA
{
	"type":"array",
	"items":{
		"type":"integer"
	}
}
HOGESCHEMA;
	public function __construct(string $json)
	{
		parent::__construct($json, self::SCHEMA);
	}
}
