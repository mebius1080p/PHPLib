<?php
declare(strict_types=1);
namespace Mebius\Json;

use JsonSchema\Validator;

/**
 * JsonValidatorBase json schema check base class
 * composer require justinrainbow/json-schema
 */
abstract class JsonValidatorBase
{
	/**
	 * @var JsonSchema\Validator
	 */
	private $validator;
	/**
	 * コンストラクタ
	 * @param mixed $json デコード済み json
	 * @param mixed $schemaJson デコード済み json schema
	 * @throws \Exception json デコードエラーで例外
	 */
	public function __construct($json, $schemaJson)
	{
		$jsonObj = \json_decode($json);
		if ($jsonObj === null) {
			throw new \Exception("invalid json string", 1);
		}
		$schemaObj = \json_decode($schemaJson);
		if ($schemaObj === null) {
			throw new \Exception("invalid JSON SCHEMA", 1);
		}
		$this->validator = new Validator();
		$this->validator->validate($jsonObj, $schemaObj);
	}
	/**
	 * 結果取り出しメソッド
	 * @return bool
	 */
	public function getResult(): bool
	{
		return $this->validator->isValid();
	}
	/**
	 * エラー取り出しメソッド
	 * @return array
	 */
	public function getErrors(): array
	{
		return $this->validator->getErrors();
	}
}
