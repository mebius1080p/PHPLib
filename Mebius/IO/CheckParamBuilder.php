<?php
namespace Mebius\IO;

//パラメーターチェック用の配列を作るクラス
class CheckParamBuilder
{
	const GET_POST = 0;
	const QUERY_STRING = 1;
	const INCLUDE_MATCH = true;
	const EXCLUDE_MATCH = false;
	private $param = [];
	private $mode;
	public function __construct($aMode = 0)
	{
		if($aMode !== self::GET_POST &&
			$aMode !== self::QUERY_STRING)
		{
			throw new \Exception("引数は 0 or 1 です");
		}
		$this->mode = $aMode;
	}
	public function add($aTag, $aReg, $aIEFlag = self::INCLUDE_MATCH)
	{
		if (is_string($aTag) === false ||
			is_string($aReg) === false ||
			is_bool($aIEFlag) === false)
		{
			throw new \Exception("CheckParamBuilder.add : 第一、第二引数は文字列、第三引数は bool です");
		}
		if ($this->mode === 0 && $aTag === "")
		{//mode が 1 のときは空文字を許可する
			throw new \Exception("CheckParamBuilder.add : 第一引数が空文字です");
		}
		if ($aReg !== "")
		{//から文字を許可。そうでないときは regexp 表現
			if(preg_match("/^\/.+\/$/", $aReg) !== 1)
			{
				throw new \Exception("CheckParamBuilder.add : 第二引数はスラッシュで囲んでください");
			}
		}
		$temp = [
			"name" => $aTag,
			"regex" => $aReg,
			"ieFlag" => $aIEFlag//include, exclude flag
		];
		$this->param[] = $temp;//追加
	}
	public function getParam()
	{
		return $this->param;
	}
	public function getMode()
	{
		return $this->mode;
	}
}
