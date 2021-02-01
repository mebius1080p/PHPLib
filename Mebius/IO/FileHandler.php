<?php

declare(strict_types=1);

namespace Mebius\IO;

/**
 * 単純なファイル読み書きクラス 小さめのファイル取り扱いを想定
 */
class FileHandler
{
	/**
	 * @var string ファイルパス
	 */
	private string $filePath = "";
	/**
	 * コンストラクタ
	 * @param string $filePath このクラスで扱うファイル名
	 * @throws \Exception 引数がファイルパスでないとき例外
	 */
	public function __construct(string $filePath)
	{
		if (!file_exists($filePath)) {
			throw new \Exception($filePath . " が存在しません。");
		}
		if (!\is_file($filePath)) {
			throw new \Exception($filePath . " が存在しません。");
		}
		$this->filePath = $filePath;
	}
	/**
	 * ファイルの内容を返すメソッド
	 * @return string ファイルの内容。読み取れない場合は空文字
	 */
	public function getString(): string
	{
		$contents = file_get_contents($this->filePath);
		if ($contents === false) {
			$contents = "";
		}
		return $contents;
	}
	/**
	 * 引数のテキストでファイル内容を丸ごと書き換えるメソッド
	 * @param string $str 新たに書き込むテキスト
	 */
	public function update(string $str): void
	{
		file_put_contents($this->filePath, $str, LOCK_EX);
	}
	/**
	 * カウントアップメソッド
	 * @return int カウントアップ後の数値
	 * @throws \Exception ファイル書き込み失敗で例外
	 */
	public function countUp(): int
	{
		$currentStr = $this->getString();
		$counter = (int)$currentStr;
		$counter++;
		$writeByte = file_put_contents($this->filePath, (string)$counter, LOCK_EX);
		if ($writeByte === false) {
			throw new \Exception("cant update file", 1);
		}
		return $counter;
	}
}
