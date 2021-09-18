<?php

declare(strict_types=1);

namespace DummyClass;

use Mebius\DB\DBHandlerBase3;

/**
 * HogeModel hoge model
 */
class HogeModel extends DBHandlerBase3
{
	public function __construct(\PDO $pdo)
	{
		parent::__construct($pdo, "hoge");
	}
}
