<?php

declare(strict_types=1);

namespace Mebius\DB;

/**
 * ConditionBuilder 投稿データから sql の where 文とプレースホルダー用データ配列を作成する抽象クラス
 * @deprecated
 */
abstract class ConditionBuilder
{
	/**
	 * @var object 投稿データをまとめたオブジェクト
	 */
	protected object $inputObj;
	/**
	 * @var string where 文の文字列
	 */
	protected string $condition = "";
	/**
	 * @var array プレースホルダーとして使用する値を入れる配列
	 */
	protected array $placeholder = [];
	/**
	 * コンストラクタ
	 * @param object $inputObj stdClass や入力データをまとめたクラスのインスタンスなど
	 */
	public function __construct(object $inputObj)
	{
		$this->inputObj = $inputObj;
		$this->build();
	}
	/**
	 * where 文を作成し、placeholder に値を格納するこのクラスの核となるメソッド。
	 * 抽象クラスで中身を実装すること
	 */
	abstract public function build();
	/**
	 * 検索条件など、where 文を取得するメソッド
	 * @return string where 文
	 */
	public function getCondition(): string
	{
		return $this->condition;
	}
	/**
	 * プレースホルダーに使う配列を取得するメソッド
	 * @return array プレースホルダーに使う配列
	 */
	public function getPlaceHolder(): array
	{
		return $this->placeholder;
	}
}
