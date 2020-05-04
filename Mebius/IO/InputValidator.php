<?php

declare(strict_types=1);

namespace Mebius\IO;

/**
 * InputValidator クラス for php7 新実装
 */
class InputValidator
{
	public const MODE_REGEX = 0;
	public const MODE_MAIL_UTF8 = 1;
	public const MODE_MAIL = 2;
	public const MODE_BETWEEN = 3;
	//http://techracho.bpsinc.jp/hachi8833/2013_09_27/13713
	//https://stackoverflow.com/questions/5219848/how-to-validate-non-english-utf-8-encoded-email-address-in-javascript-and-php
	private const RE_MAIL = "/\A([\p{L}\.\-\d_]+)@([\p{L}\-\.\d_]+)((\.(\p{L}){2,63})+)\z/u";
	// /\A[[:^cntrl:]]{0,50}\z/u
	/**
	 * @var ValidatorObj[] バリデートオブジェクト配列
	 */
	private array $checkArray = [];
	/**
	 * コンストラクタ
	 * @param ValidateParamBuilder $vpb ValidateParamBuilder のインスタンス
	 * @throws \Exception ValidateParamBuilder で値がセットされていない場合に例外
	 */
	public function __construct(ValidateParamBuilder $vpb)
	{
		$paramArray = $vpb->getParam();
		if (count($paramArray) === 0) {
			throw new \Exception("ValidateParamBuilder オブジェクトの中身が空です");
		}
		$this->checkArray = $paramArray;
		$this->validate();
	}
	//----------------------------------------------
	/**
	 * バリデートメソッド。複数の値を一気にチェックするので、返り値などは返さない
	 * @throws \Exception バリデートエラーで例外
	 */
	private function validate(): void
	{
		foreach ($this->checkArray as $vo) {
			switch ($vo->mode) {
				case self::MODE_REGEX:
					$this->regex($vo);
					break;
				case self::MODE_MAIL:
					$this->mail($vo);
					break;
				case self::MODE_MAIL_UTF8:
					$this->mailutf8($vo);
					break;
				case self::MODE_BETWEEN:
					$this->between($vo);
					break;
			}
		}
	}
	/**
	 * 正規表現でパラメータをチェックするメソッド
	 * @param ValidatorObj $vo ValidatorObj のインスタンス
	 * @throws \Exception マッチしなかった場合、除外なのに含まれている例外
	 */
	private function regex(ValidatorObj $vo): void
	{
		$regResult = preg_match($vo->regex, $vo->stringValue);
		if ($vo->isInclude) {
			if ($regResult !== 1) {//キーワードが含まれていない
				throw new \Exception($vo->stringValue . " : 値が不正です");
			}
		} else {//除外キーワードがないか調べる
			if ($regResult === 1 || $regResult === false) {//キーワードが含まれている or エラー
				throw new \Exception($vo->stringValue . " : 不正な値が含まれています");
			}
		}
	}
	/**
	 * メールアドレスをチェックするメソッド
	 * @param ValidatorObj $vo ValidatorObj のインスタンス
	 * @throws \Exception メールアドレスでなかった場合、通常例外を投げる
	 */
	private function mail(ValidatorObj $vo): void
	{
		if (!filter_var($vo->stringValue, FILTER_VALIDATE_EMAIL)) {
			throw new \Exception($vo->stringValue . " : 不正なメールアドレスです。");
		}
	}
	/**
	 * メールアドレスをチェックするメソッド utf8 対応版
	 * @param ValidatorObj $vo ValidatorObj のインスタンス
	 * @throws \Exception メールアドレスでなかった場合、通常例外を投げる
	 */
	private function mailutf8(ValidatorObj $vo): void
	{
		if (preg_match(self::RE_MAIL, $vo->stringValue) !== 1) {
			throw new \Exception($vo->stringValue . " : 不正なメールアドレスです。");
		}
	}
	/**
	 * 数値比較用のメソッド
	 * @param ValidatorObj $vo ValidatorObj のインスタンス
	 * @throws \Exception 範囲外/内だった場合、通常例外を投げる
	 */
	private function between(ValidatorObj $vo): void
	{
		if ($vo->isInclude) {
			$this->checkInclude($vo);
		} else {
			$this->checkExclude($vo);
		}
	}
	/**
	 * 範囲内かチェックするメソッド
	 * @param ValidatorObj $vo ValidatorObj のインスタンス
	 * @throws \Exception 範囲外だった場合、通常例外を投げる
	 */
	private function checkInclude(ValidatorObj $vo): void
	{
		$message1 = "%d は %d と %d の間にありません";
		if ($vo->intValue < $vo->min || $vo->max < $vo->intValue) {
			throw new \Exception(sprintf($message1, $vo->intValue, $vo->min, $vo->max), 1);
		}
	}
	/**
	 * 範囲外かチェックするメソッド
	 * @param ValidatorObj $vo ValidatorObj のインスタンス
	 * @throws \Exception 範囲内だった場合、通常例外を投げる
	 */
	private function checkExclude(ValidatorObj $vo): void
	{
		$message1 = "%d は %d と %d の間にあります";
		if ($vo->min < $vo->intValue && $vo->intValue < $vo->max) {
			throw new \Exception(sprintf($message1, $vo->intValue, $vo->min, $vo->max), 1);
		}
	}
}
