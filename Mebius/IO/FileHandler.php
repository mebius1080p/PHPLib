<?php
declare(strict_types=1);
namespace Mebius\IO;

/**
 * 単純なファイル読み書きクラス
 */
class FileHandler
{
	/**
	 * @var string ファイルパス
	 */
	private $filePath = "";
	/**
	 * コンストラクタ
	 * @param string $filePath このクラスで扱うファイル名
	 * @throws \Exception 引数のファイルがないとき例外
	 */
	public function __construct(string $filePath)
	{
		if (!file_exists($filePath)) {
			throw new \Exception($filePath . " が存在しません。");
		}
		$this->filePath = $filePath;
	}
	/**
	 * ファイルの内容を返すメソッド
	 * @return string ファイルの内容。コンストラクタでファイルの存在をチェックしているので、多少意味のあるメソッド
	 */
	public function getString(): string
	{
		return file_get_contents($this->filePath);
	}
	/**
	 * 引数のテキストでファイル内容を丸ごと書き換えるメソッド
	 * @param string $aStr 新たに書き込むテキスト
	 */
	public function update(string $aStr): void
	{
		file_put_contents($this->filePath, $aStr, LOCK_EX);
	}
	/**
	 * カウントアップメソッド
	 * @return int カウントアップ後の数値
	 */
	public function countUp(): int
	{
		$currentStr = file_get_contents($this->filePath);
		$counter = (int)$currentStr;
		$counter++;
		file_put_contents($this->filePath, (string)$counter, LOCK_EX);
		return $counter;
	}
}
