<?php
declare(strict_types=1);
namespace Mebius\IO;

/**
 * InputValidator に渡すデータをまとめるだけのクラス for php7
 */
class ValidateParamBuilder
{
	/**
	 * パラメーター格納配列
	 */
	private $param = [];
	/**
	 * param を返すメソッド
	 * @return array インスタンスが保持する param
	 */
	public function getParam(): array
	{
		return $this->param;
	}
	public function __construct()
	{
		//do nothing
	}
	/**
	 * チェックするパラメーターを追加するメソッド 正規表現用
	 * @param string $aInputStr 入力文字列
	 * @param string $aRegExpStr チェック用の正規表現文字列。
	 * @param bool $aIsInclude 正規表現通りなら OK なのか、そうでないかのフラグ。false の時は除外文字列をチェックできる
	 */
	public function addWithRegEx(string $aInputStr, string $aRegExpStr, $aIsInclude = true): void
	{
		ValidatorUtil::checkUTF8($aInputStr);
		if(preg_match("/^\/.+\/$/", $aRegExpStr) !== 1) {//スラッシュで始まり何か入ってスラッシュで終わる文字列
			throw new \Exception("addWithRegEx : 第二引数はスラッシュで囲まれた正規表現リテラルにしてください");
		}
		ValidatorUtil::checkBoolean($aIsInclude, "addWithRegEx : 第三引数は boolean にしてください");
		$temp = [
			"value1" => $aInputStr,
			"value2" => "",//between 用の第二パラメータ
			"value3" => "",//between 用の第三パラメータ
			"mode" => "regex",
			"regex" => $aRegExpStr,
			"isInclude" => $aIsInclude
		];
		$this->param[] = $temp;//追加
	}
	/**
	 * メールとしてパラメーターを追加するメソッド exclude はまず使わないだろう……
	 * @param string $mayBeMail メールと思われる文字列
	 * @param bool $checkAsUtf8 メールアドレスを utf8 としてチェックするかどうかのフラグ
	 */
	public function addMail(string $mayBeMail, $checkAsUtf8 = true): void
	{
		ValidatorUtil::checkUTF8($mayBeMail);
		ValidatorUtil::checkBoolean($checkAsUtf8, "addMail : 第二引数は boolean にしてください");
		$temp = [
			"value1" => $mayBeMail,
			"value2" => "",
			"value3" => "",
			"mode" => $checkAsUtf8 ? "mailutf8" : "mail",
			"regex" => "",
			"isInclude" => true
		];
		$this->param[] = $temp;//追加
	}
	/**
	 * 値が指定数値の範囲内かどうかをチェックするパラメーターを追加するメソッド exclude はまず使わないだろう……
	 * 整数のみ対応
	 * @param string $aValue1 間を検証する値
	 * @param int $aValue2 間を検証する小さい方の数値
	 * @param int $aValue2 間を検証する大きい方の数値
	 * @param bool $aIsInclude 数値が引数の間か、そうでないかのフラグ。false の時は範囲外チェック
	 */
	public function addBetweenInt(string $aValue1, $aValue2 = null, $aValue3 = null, $aIsInclude = true): void
	{
		ValidatorUtil::checkUTF8($aValue1);
		//value1 に関しては validator でチェックする
		ValidatorUtil::checkNullOrNumber($aValue2);
		ValidatorUtil::checkNullOrNumber($aValue3);
		ValidatorUtil::checkBetween($aValue2, $aValue3);

		$temp = [
			"value1" => intval($aValue1),
			"value2" => $aValue2,
			"value3" => $aValue3,
			"mode" => "compare",
			"regex" => "",
			"isInclude" => $aIsInclude
		];
		$this->param[] = $temp;//追加
	}
}
