<?php
namespace Mebius\IO;

//input チェッククラス
class InputChecker
{
	private $checkArray;//配列
	private $env;
	public function __construct(CheckParamBuilder $aCPBObj)
	{
		$temp = $aCPBObj->getParam();
		if(count($temp) === 0)
		{
			throw new \Exception("CheckParamBuilder オブジェクトの中身が空です");
		}
		$this->checkArray = $temp;
		if ($aCPBObj->getMode() === CheckParamBuilder::GET_POST)
		{
			if($_SERVER["REQUEST_METHOD"] === "GET")
			{
				$this->env = $_GET;
			} else {
				$this->env = $_POST;
			}
			$this->check0();
		} else {//CheckParamBuilder::QUERY_STRING
			$this->checkQS();
		}
	}
	private function check0()
	{
		$len = count($this->checkArray);
		for ($i = 0; $i < $len; $i++)
		{
			if(array_key_exists($this->checkArray[$i]["name"], $this->env) === false)
			{
				throw new \Exception("必須キーが見つかりません");
			}
		}
		$this->check1();
	}
	private function check1()
	{
		$len = count($this->checkArray);
		for ($i = 0; $i < $len; $i++)
		{
			$name = $this->checkArray[$i]["name"];
			$reg = $this->checkArray[$i]["regex"];
			$ieFlag = $this->checkArray[$i]["ieFlag"];
			if (mb_check_encoding($this->env[$name], 'UTF-8') === false)
			{//攻撃の可能性
				throw new \Exception($name." : パラメーターが UTF-8 ではありません");
			}
			if ($reg !== "")
			{
				if ($ieFlag === CheckParamBuilder::INCLUDE_MATCH)
				{
					if(preg_match($reg, $this->env[$name]) !== 1)
					{
						throw new \Exception($name." : 値が不正です");
					}
				} else {//除外キーワードがないか調べる
					$result = preg_match($reg, $this->env[$name]);
					if ($result === 1 || $result === false)
					{
						throw new \Exception($name." : 不正な値が含まれています");
					}
				}
			}//空文字の時はチェックしない
		}
	}
	private function checkQS()
	{
		$reg = $this->checkArray[0]["regex"];
		if (preg_match($reg, $_SERVER['QUERY_STRING']) !== 1)
		{
			throw new \Exception("Query_String 値が不正です");
		}
	}
	public static function checkMailAddress($aMail)
	{
		if (is_string($aMail) === false)
		{
			throw new \Exception("引数は文字列です");
		}
		if (preg_match('/\A[[:^cntrl:]]{0,50}\z/u', $aMail) !== 1)
		{
			throw new \Exception("including controll string");
		}
		if (filter_var($aMail, FILTER_VALIDATE_EMAIL) === false)
		{
			throw new \Exception("不正なメールアドレスです。");
		}
	}
}
