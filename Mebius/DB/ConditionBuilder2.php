<?php

declare(strict_types=1);

namespace Mebius\DB;

/**
 * ConditionBuilder2 検索条件などの where と placeholder を作成するベースメソッド
 */
abstract class ConditionBuilder2
{
	/**
	 * @var string $where where 文以降のクエリ文字列
	 */
	protected $where = "";
	/**
	 * @var string[] プレースホルダ用データの配列
	 */
	protected $placeholder = [];
	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		//
	}
	/**
	 * where 文を返すメソッド
	 * @return string where 文
	 */
	public function getWhere(): string
	{
		return $this->where;
	}
	/**
	 * プレースホルダ用データの配列を返す
	 * @return array プレースホルダ用データの配列
	 */
	public function getPlaceholder(): array
	{
		return $this->placeholder;
	}
	/**
	 * 検索条件作成メソッド
	 * 実際の作成メソッドは継承して実装する
	 * $inputObj は保持する必要が無いので引数で渡す
	 * @param object $inputObj 条件作成元のオブジェクト
	 */
	abstract public function build(object $inputObj): void;
}
