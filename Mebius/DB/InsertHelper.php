<?php
declare(strict_types=1);
namespace Mebius\DB;

/**
 * InsertHelper insert ... on duplicate key update 文用のヘルパークラス
 */
class InsertHelper
{
	private $columns = [];
	private $placeHolders = [];
	private $updates = [];//on duplicate 以降の文をいれる
	/**
	 * コンストラクタ
	 * @param array $columns カラムの文字列をつめた配列
	 */
	public function __construct(array $columns)
	{
		$this->columns = $columns;
		$this->make();
	}
	/**
	 * insert 文最初の () の中身用の文字列を返す
	 * @return string insert 文最初の () の中身用の文字列
	 */
	public function getColumnStr()
	{
		return implode(",", $this->columns);
	}
	/**
	 * insert 文 values() の中身用の文字列を返す
	 * @return string insert 文 values() の中身用の文字列を返す
	 */
	public function getPlaceHolderStr()
	{
		return implode(",", $this->placeHolders);
	}
	/**
	 * update 以降で使用する文字列を返す
	 * @return string update 以降で使用する文字列
	 */
	public function getUpdateStr()
	{
		return implode(",", $this->updates);
	}
	//----------------------------
	/**
	 * 出力用の各配列を用意するメソッド
	 */
	private function make()
	{
		foreach ($this->columns as $col) {
			$this->placeHolders[] = "?";
			$this->updates[] = $col . "=?";
		}
	}
}
