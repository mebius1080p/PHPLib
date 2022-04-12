<?php

declare(strict_types=1);

namespace Mebius\Data;

/**
 * InputValidator 投稿内容バリデートのためのクラス
 * InputObj などで継承して利用することを想定
 * errors というプロパティは渡さないこと
 * 継承クラスでは、たとえば validate メソッドを書き、bool を返すなど自由に実装する
 * 引数が必要なこともあるかもしれないので abstract メソッドは記述しない
 */
abstract class InputValidator
{
	//http://techracho.bpsinc.jp/hachi8833/2013_09_27/13713
	//https://stackoverflow.com/questions/5219848/how-to-validate-non-english-utf-8-encoded-email-address-in-javascript-and-php
	private const RE_MAIL = "/\A([\p{L}\.\-\d_]+)@([\p{L}\-\.\d_]+)((\.(\p{L}){2,63})+)\z/u";

	/**
	 * @var string[] $errors エラーのあった項目を配列で保持する
	 */
	private array $errors = [];
	public function __construct()
	{
		//dd;
	}
	/**
	 * 日付バリデートメソッド
	 * @param string $prop アクセスするプロパティ
	 * @throws \Exception 例外の可能性あり
	 */
	public function date(string $prop): void
	{
		$this->checkProperty($prop);
		$value = $this->$prop;
		self::checkUTF8($value);
		try {
			$dt = new \DateTime($value);//@phan-suppress-current-line PhanUnusedVariable
		} catch (\Exception $e) {//@phan-suppress-current-line PhanUnusedVariableCaughtException
			$this->errors[] = $prop;
		}
	}
	/**
	 * 必須バリデートメソッド
	 * @param string $prop アクセスするプロパティ
	 * @throws \Exception 例外の可能性あり
	 */
	public function mandatory(string $prop): void
	{
		$this->checkProperty($prop);
		$value = $this->$prop;
		if (\is_string($value)) {
			self::checkUTF8($value);
		}
		if ($value === "" || $value === 0) {
			$this->errors[] = $prop;
		}
	}
	/**
	 * 文字列長バリデートメソッド(最大)
	 * @param string $prop アクセスするプロパティ
	 * @param int $len この長さ以下の文字列であるかをチェックする
	 * @throws \Exception 例外の可能性あり
	 */
	public function length(string $prop, int $len): void
	{
		if ($len <= 0) {//空文字を許可しない 許可したければこのメソッドは呼ばない
			throw new \Exception("invalid len argument", 1);
		}
		$this->checkProperty($prop);
		$str = $this->$prop;
		self::checkUTF8($str);
		if (mb_strlen($str) > $len) {
			$this->errors[] = $prop;
		}
	}
	/**
	 * 文字列長バリデートメソッド(最小)
	 * @param string $prop アクセスするプロパティ
	 * @param int $len この長さ以上の文字列であるかをチェックする
	 * @throws \Exception 例外の可能性あり
	 */
	public function overLength(string $prop, int $len): void
	{
		if ($len <= 0) {//から文字を許可しない 許可したければこのメソッドは呼ばない
			throw new \Exception("invalid len argument", 1);
		}
		$this->checkProperty($prop);
		$str = $this->$prop;
		self::checkUTF8($str);
		if (mb_strlen($str) < $len) {
			$this->errors[] = $prop;
		}
	}
	/**
	 * 数値範囲バリデートメソッド(整数)
	 * @param string $prop アクセスするプロパティ
	 * @param int $from この数値以上の数値であるかをチェックする
	 * @param int $to この数値以下の数値であるかをチェックする
	 * @throws \Exception 例外の可能性あり
	 */
	public function numRange(string $prop, int $from, int $to): void
	{
		if ($from > $to) {
			throw new \Exception("invalid from_to argument", 1);
		}
		$this->checkProperty($prop);
		$num = \intval($this->$prop);
		if ($num < $from || $to < $num) {
			$this->errors[] = $prop;
		}
	}
	/**
	 * 電話番号バリデートメソッド
	 * @param string $prop アクセスするプロパティ
	 * @throws \Exception 例外の可能性あり
	 */
	public function tel(string $prop): void
	{
		$this->regex($prop, "/\A[0-9\-]+\z/");
	}
	/**
	 * 郵便番号バリデートメソッド
	 * @param string $prop アクセスするプロパティ
	 * @throws \Exception 例外の可能性あり
	 */
	public function postal(string $prop): void
	{
		$this->regex($prop, "/\A[0-9]{3}-?[0-9]{4}\z/");
	}
	/**
	 * メールバリデートメソッド
	 * @param string $prop アクセスするプロパティ
	 * @param bool $isutf8 メアドを utf8 としてチェックするかのフラグ
	 * @throws \Exception 例外の可能性あり
	 */
	public function mail(string $prop, bool $isutf8 = true): void
	{
		$this->checkProperty($prop);
		$mail = $this->$prop;
		self::checkUTF8($mail);
		if ($isutf8) {
			if (preg_match(self::RE_MAIL, $mail) !== 1) {
				$this->errors[] = $prop;
			}
		} else {
			if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				$this->errors[] = $prop;
			}
		}
	}
	/**
	 * 同一性バリデートメソッド
	 * @param string $prop1 アクセスするプロパティ1
	 * @param string $prop2 アクセスするプロパティ2
	 * @param bool $dualError 値が違ったとき、errors に両方の名前を入れるかのフラグ
	 * @throws \Exception 例外の可能性あり
	 */
	public function same(string $prop1, string $prop2, bool $dualError = true): void
	{
		$this->checkProperty($prop1);
		$this->checkProperty($prop2);
		$str1 = $this->$prop1;
		$str2 = $this->$prop2;
		self::checkUTF8($str1);
		self::checkUTF8($str2);
		if ($str1 !== $str2) {
			$this->errors[] = $prop1;
			if ($dualError) {
				$this->errors[] = $prop2;
			}
		}
	}
	/**
	 * 正規表現でのバリデートメソッド
	 * @param string $prop アクセスするプロパティ
	 * @param string $regex 正規表現
	 * @throws \Exception 例外の可能性あり
	 */
	public function regex(string $prop, string $regex): void
	{
		self::checkProperty($prop);
		$str = $this->$prop;
		self::checkUTF8($str);
		if (preg_match($regex, $str) !== 1) {
			$this->errors[] = $prop;
		}
	}
	/**
	 * エラーのあった項目を返すメソッド
	 * @return string[]
	 */
	public function getErrors(): array
	{
		$this->errors = \array_values(\array_unique($this->errors));
		return $this->errors;
	}
	/**
	 * エラーオブジェクトを返すメソッド
	 * @return \stdClass
	 */
	public function getErrorObject(): \stdClass
	{
		$obj = new \stdClass();
		foreach ($this->errors as $er) {
			if ($er !== "") {
				$obj->$er = true;
			}
		}
		return $obj;
	}
	/**
	 * 文字列が utf8 かどうか調べるメソッド。
	 * @param string $str チェックする文字列
	 * @throws \Exception 文字コードが utf8 でなければ例外
	 */
	public static function checkUTF8(string $str): void
	{
		if (!mb_check_encoding($str, 'UTF-8')) {//攻撃の可能性
			throw new \Exception("パラメーターが UTF-8 ではありません");
		}
	}
	/**
	 * 各種バリデートメソッドで呼んで、継承クラスが指定のプロパティを持っているかチェックするメソッド
	 * @param string $prop プロパティ名
	 * @throws \Exception プロパティがなければ例外
	 */
	public function checkProperty(string $prop): void
	{
		if ($prop === "errors") {
			throw new \Exception("cant use errors property", 1);
		}
		if (!\property_exists($this, $prop)) {
			throw new \Exception("property does not exist:" . $prop, 1);
		}
	}
}
