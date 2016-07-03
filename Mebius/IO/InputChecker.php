<?php
namespace Mebius\IO;

/**
*InputChecker クラス for php7
*/
class InputChecker
{
	private $checkArray;//配列
	public function __construct(CheckParamBuilder $aCPBObj)
	{
		$paramArray = $aCPBObj->getParam();
		if (count($paramArray) === 0) {
			throw new \Exception("CheckParamBuilder オブジェクトの中身が空です");
		}
		$this->checkArray = $paramArray;
	}
	/**
	*バリデートメソッド。複数の値を一気にチェックするので、返り値などは返さない
	*/
	public function validate()
	{
		$len = count($this->checkArray);
		for ($i = 0; $i < $len; $i++) {
			$value = $this->checkArray[$i]["value"];
			$reg = $this->checkArray[$i]["regex"];
			$isInclude = $this->checkArray[$i]["isInclude"];
			if (!mb_check_encoding($value, 'UTF-8')) {//攻撃の可能性
				throw new \Exception($value . " : パラメーターが UTF-8 ではありません");
			}
			if ($reg !== "") {
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
			}//空文字の時はチェックしない
		}
	}
	/**
	*メールアドレスだけをチェックするスタティックメソッド
	*@param {string} $aMail メールアドレスと思われる文字列
	*@return {stdClass} バリデーション通過したかどうかのフラグとメッセージを持つオブジェクト
	*/
	public static function checkMailAddress(string $aMail)
	{
		$retObj = new \stdClass();
		$retObj->result = true;
		$retObj->message = "";
		//no need to check control string???
		// if (preg_match('/\A[[:^cntrl:]]{0,50}\z/u', $aMail) !== 1) {
		// 	$retObj->result = false;
		// 	$retObj->message = "including controll string";
		// }
		if (!filter_var($aMail, FILTER_VALIDATE_EMAIL)) {
			$retObj->result = false;
			$retObj->message = "不正なメールアドレスです。";
		}
		return $retObj;
	}
}
