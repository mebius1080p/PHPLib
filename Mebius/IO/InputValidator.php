<?php
declare(strict_types=1);
namespace Mebius\IO;

/**
 * InputValidator クラス for php7 新実装
 */
class InputValidator
{
	//http://techracho.bpsinc.jp/hachi8833/2013_09_27/13713
	//https://stackoverflow.com/questions/5219848/how-to-validate-non-english-utf-8-encoded-email-address-in-javascript-and-php
	const RE_MAIL = "/\A([\p{L}\.\-\d_]+)@([\p{L}\-\.\d_]+)((\.(\p{L}){2,63})+)\z/u";
	// /\A[[:^cntrl:]]{0,50}\z/u
	private $checkArray;//配列
	/**
	 * コンストラクタ
	 * @param ValidateParamBuilder $aCPBObj ValidateParamBuilder のインスタンス
	 */
	public function __construct(ValidateParamBuilder $aCPBObj)
	{
		$paramArray = $aCPBObj->getParam();
		if (count($paramArray) === 0) {
			throw new \Exception("ValidateParamBuilder オブジェクトの中身が空です");
		}
		$this->checkArray = $paramArray;
		$this->validate();
	}
	/**
	 * バリデートメソッド。複数の値を一気にチェックするので、返り値などは返さない
	 */
	private function validate(): void
	{
		$len = count($this->checkArray);
		for ($i = 0; $i < $len; $i++) {
			$mode = $this->checkArray[$i]["mode"];
			switch ($mode) {
				case 'regex':
					$this->regex($this->checkArray[$i]);
					break;
				case 'mail':
					$this->mail($this->checkArray[$i]);
					break;
				case 'mailutf8':
					$this->mailutf8($this->checkArray[$i]);
					break;
				case 'compare':
					$this->compare($this->checkArray[$i]);
					break;
			}
		}
	}
	/**
	 * 正規表現でパラメータをチェックするメソッド
	 * @param array $array ValidateParamBuilder の持っていた配列の要素
	 * @throws Exception マッチしなかった場合、通常例外を投げる
	 */
	private function regex(array $array): void
	{
		$value = $array["value1"];//value2 は使わない
		$reg = $array["regex"];
		$isInclude = $array["isInclude"];
		if ($isInclude) {
			if(preg_match($reg, $value) !== 1) {
				throw new \Exception($value . " : 値が不正です");
			}
		} else {//除外キーワードがないか調べる
			$result = preg_match($reg, $value);
			if ($result === 1 || $result === false) {
				throw new \Exception($value . " : 不正な値が含まれています");
			}
		}
	}
	/**
	 * メールアドレスをチェックするメソッド
	 * @param array $array ValidateParamBuilder の持っていた配列の要素
	 * @throws Exception メールアドレスでなかった場合、通常例外を投げる
	 */
	public function mail(array $array): void
	{
		if (!filter_var($array["value1"], FILTER_VALIDATE_EMAIL)) {
			throw new \Exception($array["value1"] . " : 不正なメールアドレスです。");
		}
	}
	/**
	 * メールアドレスをチェックするメソッド utf8 対応版
	 * @param array $array ValidateParamBuilder の持っていた配列の要素
	 * @throws Exception メールアドレスでなかった場合、通常例外を投げる
	 */
	public function mailutf8(array $array): void
	{
		if (preg_match(self::RE_MAIL, $array["value1"]) !== 1) {
			throw new \Exception($array["value1"] . " : 不正なメールアドレスです。");
		}
	}
	/**
	 * 数値比較用のメソッド
	 * @param array $array ValidateParamBuilder の持っていた配列の要素
	 * @throws Exception 範囲外/内だった場合、通常例外を投げる
	 */
	public function compare(array $array): void
	{
		if ($array["isInclude"]) {
			$this->checkInclude($array);
		} else {
			$this->checkExclude($array);
		}

	}
	/**
	 * 範囲内かチェックするメソッド
	 * @param array $array ValidateParamBuilder の持っていた配列の要素
	 * @throws Exception 範囲外だった場合、通常例外を投げる
	 */
	private function checkInclude(array $array): void
	{
		$val = $array["value1"];
		$min = $array["value2"];
		$max = $array["value3"];
		$message1 = "%s は %s と %s の間にありません";
		$message2 = "%s は %s より大きくありません";
		$message3 = "%s は %s より小さくありません";
		if ($min !== null && $max !== null) {
			if ($val < $min || $max < $val) {
				throw new \Exception(sprintf($message1, $val, $min, $max), 1);
			}
		} else if ($min !== null) {
			if ($val < $min) {
				throw new \Exception(sprintf($message2, $val, $min), 1);
			}
		} else if ($max !== null) {
			if ($max < $val) {
				throw new \Exception(sprintf($message3, $val, $max), 1);
			}
		}
	}
	/**
	 * 範囲外かチェックするメソッド
	 * @param array $array ValidateParamBuilder の持っていた配列の要素
	 * @throws Exception 範囲内だった場合、通常例外を投げる
	 */
	private function checkExclude(array $array): void
	{
		$val = $array["value1"];
		$min = $array["value2"];
		$max = $array["value3"];
		$message1 = "%s は %s と %s の間にあります";
		$message2 = "%s は %s より小さくありません";//使わないだろうが……
		$message3 = "%s は %s より大きくありません";//使わないだろうが……
		if ($min !== null && $max !== null) {
			if ($min < $val && $val < $max) {
				throw new \Exception(sprintf($message1, $val, $min, $max), 1);
			}
		} else if ($min !== null) {
			if ($min < $val) {
				throw new \Exception(sprintf($message2, $val, $min), 1);
			}
		} else if ($max !== null) {
			if ($val < $max) {
				throw new \Exception(sprintf($message3, $val, $max), 1);
			}
		}
	}
}
