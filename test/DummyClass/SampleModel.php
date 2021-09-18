<?php

declare(strict_types=1);

namespace DummyClass;

use Mebius\DB\DBHandlerBase3;

/**
 * SampleModel テスト用サンプルモデル
 */
class SampleModel extends DBHandlerBase3
{
	public function __construct(\PDO $pdo)
	{
		parent::__construct($pdo, "hoge");
	}
}
