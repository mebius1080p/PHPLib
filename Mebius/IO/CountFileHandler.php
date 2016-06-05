<?php
namespace Mebius\IO;

/**
* CountFileHandler
*/
class CountFileHandler
{
	private $fh;
	private $fileName;
	public function __construct($aFile)
	{
		if ($aFile === "" || is_string($aFile) === false)
		{
			throw new \Exception("ファイル名は文字列で指定します");
		}
		if (file_exists($aFile) === false)
		{
			throw new \Exception("開こうとするファイルが存在しません。");
		}
		$this->fileName = $aFile;
		$this->fh = fopen($aFile, "r+");
		flock($this->fh, LOCK_EX);//書き込みロック
	}
	public function countUp()
	{
		$counter = fread($this->fh, filesize($this->fileName));//読み込み
		$counter = (int)$counter;
		$counter++;
		//ftruncate($this->fh, 0);//ファイルサイズ切り詰め：オプション
		rewind($this->fh);//ポインタを先頭に
		fwrite($this->fh, (string)$counter);//ファイルポインタに書き込み
		return $counter;
	}
	public function __destruct()
	{
		flock($this->fh, LOCK_UN);//ロック解放
		fclose($this->fh);//確実に閉じる
	}
}
