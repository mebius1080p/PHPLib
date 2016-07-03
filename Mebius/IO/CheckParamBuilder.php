<?php
namespace Mebius\IO;

/**
*InputChecker に渡すデータをまとめるだけのクラス for php7 a
*/
class CheckParamBuilder
{
	/**
	*パラメーター格納配列
	*/
	private $param = [];
	public function __construct()
	{
		//do nothing
	}
	/**
	*チェックするパラメーターを追加するメソッド
	*@param {string} $aInputStr 入力文字列
	*@param {string} $aRegExpStr チェック用の正規表現文字列。
	*@param {boolean} $aIsInclude 正規表現通りなら OK なのか、そうでないかのフラグ。false の時は除外文字列をチェックできる
	*/
	public function add(string $aInputStr, string $aRegExpStr, $aIsInclude = true)
	{
		if(preg_match("/^\/.+\/$/", $aRegExpStr) !== 1)//スラッシュで始まり何か入ってスラッシュで終わる文字列
		{
			throw new \Exception("CheckParamBuilder2->add : 第二引数はスラッシュで囲まれた正規表現リテラルにしてください");
		}
		if (!is_bool($aIsInclude)) {
			throw new \Exception("CheckParamBuilder2->add : 第三引数は boolean にしてください");
		}
		$temp = [
			"value" => $aInputStr,
			"regex" => $aRegExpStr,
			"isInclude" => $aIsInclude
		];
		$this->param[] = $temp;//追加
	}
	/**
	*param を返すメソッド
	*@return {array} インスタンスが保持する param
	*/
	public function getParam()
	{
		return $this->param;
	}
}
