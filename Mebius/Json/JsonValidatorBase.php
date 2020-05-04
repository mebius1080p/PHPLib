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
	 * @var Validator
	 */
	private Validator $validator;
	/**
	 * @var mixed
	 */
	private $json;
	/**
	 * コンストラクタ
	 * @param string $json json 文字列
	 * @param string $schemaJson json schema 文字列
	 * @throws \Exception json デコードエラーで例外
	 */
	public function __construct(string $json, string $schemaJson)
	{
		$this->json = \json_decode($json);
		if ($this->json === null) {
			throw new \Exception("invalid json string", 1);
		}
		$schemaObj = \json_decode($schemaJson);
		if ($schemaObj === null) {
			throw new \Exception("invalid JSON SCHEMA", 1);
		}
		$this->validator = new Validator();
		$this->validator->validate($this->json, $schemaObj);
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
	/**
	 * デコード済み json を取り出すメソッド
	 * @return mixed
	 */
	public function getJson()
	{
		return $this->json;
	}
}
