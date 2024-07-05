<?php

declare(strict_types=1);

namespace Mebius\Validator;

use Mebius\Validator\Rule\Same;

/**
 * ValidateTrait バリデータートレイト
 */
trait ValidateTrait
{
	private array $validateError = [];
	public function validate()
	{
		$ref = new \ReflectionClass($this);
		$props = $ref->getProperties();
		foreach ($props as $prop) {
			$name = $prop->getName();
			$attrs = $prop->getAttributes(ValidateInterface::class, \ReflectionAttribute::IS_INSTANCEOF);
			foreach ($attrs as $attr) {
				try {
					$attrObj = $attr->newInstance();
					if ($attrObj::class === Same::class) {//同値チェックの時だけの特別処理
						/** @var Same $attrObj */
						$targetProp = $attrObj->getTargetPropName();
						if (!property_exists($this, $targetProp)) {
							throw new \Exception("同地チェック:{$targetProp} が見つかりません", 1);
						}
						$targetVal = $this->$targetProp;
						if (!is_string($targetVal)) {
							throw new \Exception("同値チェックでは文字列のみチェックできます", 1);
						}
						$attrObj->setTargetValue($targetVal);
					}
					$attrObj->validate($name, $prop->getValue($this));

					if ($attrObj->hasFixedValue()) {//調整値上書き
						$this->$name = $attrObj->getFixedValue();
					}
				} catch (\Exception $e) {
					if (!array_key_exists($name, $this->validateError)) {
						$this->validateError[$name] = [];
					}
					$this->validateError[$name][] = $e->getMessage();
				}
			}
		}
	}
	public function getValidateError(): array
	{
		return $this->validateError;
	}
}
