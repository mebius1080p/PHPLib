<?php
namespace Mebius\IO;

/**
*単純なファイル読み書きクラス
*/
class FileHandler
{
	private $fileName = "";
	private $fh;
	private $isLocked = false;
	private $isOpened = false;
	/**
	*@param {string} $fileName このクラスで扱うファイル名
	*/
	public function __construct(string $fileName)
	{
		if (!file_exists($fileName)) {
			throw new \Exception($fileName . " が存在しません。");
		}
		$this->fileName = $fileName;
	}
	public function getString()
	{
		return file_get_contents($this->fileName);
	}
	/**
	*引数のテキストでファイル内容を丸ごと書き換えるメソッド
	*@param {string} $aStr 新たに書き込むテキスト
	*/
	public function update(string $aStr)
	{
		$this->fh = fopen($this->fileName, "w");
		$this->isOpened = true;
		flock($this->fh, LOCK_EX);//書き込みロック
		$this->isLocked = true;
		//ftruncate($this->fh, 0);//ファイルサイズ切り詰め：オプション
		//rewind()???
		fwrite($this->fh, $aStr);
		flock($this->fh, LOCK_UN);//ロック解放
		$this->isLocked = false;
		fclose($this->fh);//古いバージョンではロックも解除
		$this->isOpened = false;
	}
	public function __destruct()
	{
		if ($this->isLocked) {
			flock($this->fh, LOCK_UN);//ロック解放
			$this->isLocked = false;
		}
		if ($this->isOpened) {
			fclose($this->fh);//確実に閉じる
			$this->isOpened = false;
		}
	}
}
