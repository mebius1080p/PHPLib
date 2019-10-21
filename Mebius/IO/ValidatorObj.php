<?php

declare(strict_types=1);

namespace Mebius\IO;

/**
* ValidatorObj inputvalidator 内部で使う、バリデートのための各種値を保持するクラス
*/
class ValidatorObj
{
	/**
	 * @var string メアドや正規表現チェックの時にチェック元文字列を入れる
	 */
	public $stringValue = "";
	/**
	 * @var int between で比較する時用の数値を入れる
	 */
	public $intValue = 0;
	/**
	 * @var int バリデートモード InputValidator の const 値を使用
	 */
	public $mode = -1;
	/**
	 * @var string 正規表現チェック用の正規表現文字列をセット
	 */
	public $regex = "";
	/**
	 * @var bool 正規表現と範囲チェック時に、含むものを探すのか、含まないものを探すのかを表すフラグ。true: だと含むもの(範囲内)を探す
	 */
	public $isInclude = true;
	/**
	 * @var int 範囲チェック時の最小値をセットするためのプロパティ
	 */
	public $min = 0;
	/**
	 * @var int 範囲チェック時の最大値をセットするためのプロパティ
	 */
	public $max = 0;
	public function __construct()
	{
		//dd;
	}
}
