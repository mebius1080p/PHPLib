<?php
namespace Mebius\IO;

/**
* CountFileHandler シンプルなファイルカウントアップクラス
*/
class CountFileHandler
{
	private $filePath;
	/**
	*@param {stirng} $aFile 開こうとするファイルパス
	*/
	public function __construct(string $aFile)
	{
		if (!file_exists($aFile))
		{
			throw new \Exception("開こうとするファイルが存在しません。");
		}
		$this->filePath = $aFile;
	}
	/**
	*カウントアップメソッド
	*@return {int} カウントアップ後の数値
	*/
	public function countUp()
	{
		$currentStr = file_get_contents($this->filePath);
		$counter = (int)$currentStr;
		$counter++;
		file_put_contents($this->filePath, (string)$counter, LOCK_EX);
		return $counter;
	}
}
