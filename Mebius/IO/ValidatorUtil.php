<?php
namespace Mebius\IO;
/**
 *parambuilder 等で使うユーティリティークラス
 */
class ValidatorUtil
{
	/**
	*文字列が utf8 かどうか調べるメソッド。
	*@param {string} $str チェックする文字列
	*@throws {Exception} 文字コードが utf8 でなければ通常例外を投げる
	*/
	public static function checkUTF8($str)
	{
		if (!mb_check_encoding($str, 'UTF-8')) {//攻撃の可能性
			throw new \Exception("パラメーターが UTF-8 ではありません");
		}
	}
	/**
	*文字列が boolean かどうか調べるメソッド。
	*@param {any} $param チェックするパラメーター
	*@param {string} $exMessage 例外で設定するメッセージ
	*@throws {Exception} bolean でなければ通常例外を投げる
	*/
	public static function checkBoolean($param, $exMessage)
	{
		if (!is_bool($param)) {
			throw new \Exception($exMessage);
		}
	}
	/**
	*引数が数値かどうかチェックする。null を許可。
	*@param {any} $value 数値と思われる変数
	*@throws {Exception} 数値でも null でなければ通常例外を投げる
	*/
	public static function checkNullOrNumber($value)
	{
		if ($value === null) {
			return;
		}
		if (!is_int($value)) {
			throw new \Exception($value . " : は数値ではありません");
		}
	}
	/**
	*範囲設定として数値が正しいかどうかをチェック
	*@throws {Exception} 両方 null でなければ通常例外を投げる
	*@throws {Exception} $val1 < $val2 でなければ通常例外を投げる
	*/
	public static function checkBetween($val1, $val2)
	{
		if ($val1 === null && $val2 === null) {
			throw new \Exception("比較数値は最低でも片方は設定してください", 1);
		}
		if ($val1 !== null && $val2 !== null) {
			if ($val1 >= $val2) {//等しい場合もだめ
				throw new \Exception($val1 . " は " . $val2 . "よりも小さくしてください", 1);
			}
		}
	}
}
