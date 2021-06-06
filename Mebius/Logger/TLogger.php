<?php

declare(strict_types=1);

namespace Mebius\Logger;

use Monolog\Logger;

/**
 * TLogger ロガートレイト
 */
trait TLogger
{
	private ?Logger $logger = null;

	/**
	 * ロガー設定メソッド
	 * @param Logger $logger ロガーインスタンス
	 */
	public function setLogger(Logger $logger): void
	{
		$this->logger = $logger;
	}
	/**
	 * info ログ書き込みメソッド
	 * @param mixed $obj ログに書き込む変数
	 */
	public function info(mixed $obj): void
	{
		if ($this->logger === null) {
			return;
		}
		$message = $this->stringifyVariable($obj);
		$this->logger->info($message);
	}
	/**
	 * error ログ書き込みメソッド
	 * @param mixed $obj ログに書き込む変数
	 */
	public function error(mixed $obj): void
	{
		if ($this->logger === null) {
			return;
		}
		$message = $this->stringifyVariable($obj);
		$this->logger->error($message);
	}

	/**
	 * mixed オブジェクトの文字列化メソッド
	 * @param mixed $obj 変換対象のオブジェクト
	 * @return string
	 */
	private function stringifyVariable(mixed $obj): string
	{
		$canNotConvertTypes = [
			"resource",
			"resource (closed)",
			"unknown type",
		];
		$objType = gettype($obj);
		if (in_array($objType, $canNotConvertTypes, true)) {
			return "- cant convert to string -";
		}

		$retString = match ($objType) {
			"array" => (string)json_encode($obj, JSON_UNESCAPED_UNICODE),
			"object" => $this->stringifyObjectType($obj),
			default => (string)$obj,
		};

		return $retString;
	}
	/**
	 * 引数の型によって文字列化したものを返すメソッド
	 * @param object $obj 変換対象のオブジェクト
	 * @return string 引数を文字列化したもの
	 */
	private function stringifyObjectType(object $obj): string
	{
		//関数だったときだけわかるように文字列で返す
		//それ以外は強制的に json encode する [stdClass, クラスインスタンス(無名含む)]
		$retString = match (get_class($obj)) {
			"Closure" => "-[function]-",
			default => (string)json_encode($obj, JSON_UNESCAPED_UNICODE),
		};
		return $retString;
	}
}
