<?php
declare(strict_types=1);
namespace Mebius\IO;

/**
 * InputValidator に渡すデータをまとめるだけのクラス for php7
 */
class ValidateParamBuilder
{
	/**
	 * @var array ValidatorObj[] バリデートオブジェクト配列
	 */
	private $param = [];
	/**
	 * param を返すメソッド
	 * @return array ValidatorObj[] バリデートオブジェクト配列
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
	 * @throws \Exception 正規表現に一致しなければ例外
	 */
	public function addWithRegEx(string $aInputStr, string $aRegExpStr, bool $aIsInclude = true): void
	{
		self::checkUTF8($aInputStr);
		if (preg_match("/^\/.+\/$/", $aRegExpStr) !== 1) {//スラッシュで始まり何か入ってスラッシュで終わる文字列
			throw new \Exception("addWithRegEx : 第二引数はスラッシュで囲まれた正規表現リテラルにしてください");
		}

		$vo = new ValidatorObj();
		$vo->stringValue = $aInputStr;
		$vo->mode = InputValidator::MODE_REGEX;
		$vo->regex = $aRegExpStr;
		$vo->isInclude = $aIsInclude;
		$this->param[] = $vo;//追加
	}
	/**
	 * メールとしてパラメーターを追加するメソッド exclude はまず使わないだろう……
	 * @param string $mayBeMail メールと思われる文字列
	 * @param bool $checkAsUtf8 メールアドレスを utf8 としてチェックするかどうかのフラグ
	 * @throws \Exception メールアドレスの文字コード不正で例外
	 */
	public function addMail(string $mayBeMail, bool $checkAsUtf8 = true): void
	{
		self::checkUTF8($mayBeMail);
		$mode = $checkAsUtf8 ? InputValidator::MODE_MAIL_UTF8 : InputValidator::MODE_MAIL;
		$vo = new ValidatorObj();
		$vo->stringValue = $mayBeMail;
		$vo->mode = $mode;
		$this->param[] = $vo;//追加
	}
	/**
	 * 値が指定数値の範囲内かどうかをチェックするパラメーターを追加するメソッド exclude はまず使わないだろう……
	 * 整数のみ対応
	 * @param int $aValue 間を検証する値
	 * @param int $min 間を検証する小さい方の数値
	 * @param int $max 間を検証する大きい方の数値
	 * @param bool $aIsInclude 数値が引数の間か、そうでないかのフラグ。false の時は範囲外チェック
	 * @throws \Exception 範囲エラーで例外
	 */
	public function addBetweenInt(int $aValue, int $min, int $max, bool $aIsInclude = true): void
	{
		if ($min >= $max) {//等しい場合もだめ
			throw new \Exception(
				sprintf("%d は %d よりも小さくしてください", $min, $max),
				1
			);
		}

		$vo = new ValidatorObj();
		$vo->intValue = $aValue;
		$vo->mode = InputValidator::MODE_BETWEEN;
		$vo->isInclude = $aIsInclude;
		$vo->min = $min;
		$vo->max = $max;
		$this->param[] = $vo;//追加
	}
	//---------------------------
	/**
	 * 文字列が utf8 かどうか調べるメソッド。
	 * @param string $str チェックする文字列
	 * @throws \Exception 文字コードが utf8 でなければ例外
	 */
	public static function checkUTF8($str): void
	{
		if (!mb_check_encoding($str, 'UTF-8')) {//攻撃の可能性
			throw new \Exception("パラメーターが UTF-8 ではありません");
		}
	}
}
