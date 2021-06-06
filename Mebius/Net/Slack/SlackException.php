<?php

declare(strict_types=1);

namespace Mebius\Net\Slack;

/**
 * SlackException slack 通信時例外用クラス
 */
class SlackException extends \Exception
{
	private object $obj;
	public function __construct(string $message, object $obj)
	{
		parent::__construct($message);
		$this->obj = $obj;
	}
	public function getJson(): object
	{
		return $this->obj;
	}
}
