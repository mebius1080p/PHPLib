<?php
declare(strict_types=1);
namespace Mebius\DB;

/**
 * InsertHelper2 sql の insert 文を作成するクラス
 */
class InsertHelper2
{
	private const INSERT_SQL = "INSERT INTO %s (%s) VALUES(%s)";
	private const INSERT_ON_UPDATE_SQL = "INSERT INTO %s (%s) VALUES(%s) ON DUPLICATE KEY UPDATE %s";
	private const DUPLICATE_TEMPLATE = "%s=VALUES(%s)";

	/**
	 * @var string テーブル名
	 */
	private $table = "";
	/**
	 * @var array カラム名の文字列配列
	 */
	private $columns = [];
	/**
	 * @var array プレースホルダー部分の文字列配列
	 */
	private $placeHolders = [];
	/**
	 * @var array on duplicate 以降の文をいれる文字列配列
	 */
	private $updates = [];
	/**
	 * コンストラクタ
	 * @param string $table テーブル名
	 * @param array $columns カラムの文字列をつめた配列
	 * @throws \Exception テーブル名が空文字の時や、カラムがない場合に例外
	 */
	public function __construct(string $table, array $columns)
	{
		if ($table === "") {
			throw new \Exception("empty table name", 1);
		}
		if (count($columns) === 0) {
			throw new \Exception("empty columns", 1);
		}
		$this->table = $table;
		$this->columns = $columns;
		$this->make();
	}
	/**
	 * 通常の insert 文を返すメソッド
	 * @return string 通常の insert 文
	 */
	public function getInsertSQL(): string
	{
		$sql = sprintf(
			self::INSERT_SQL,
			$this->table,
			$this->getColumnStr(),
			$this->getPlaceHolderStr()
		);
		return $sql;
	}
	/**
	 * insert, update 両方に使える insert 文を返すメソッド
	 * @return string 通常の insert 文
	 */
	public function getOnDuplicateSQL(): string
	{
		$sql = sprintf(
			self::INSERT_ON_UPDATE_SQL,
			$this->table,
			$this->getColumnStr(),
			$this->getPlaceHolderStr(),
			$this->getUpdateStr()
		);
		return $sql;
	}
	/**
	 * insert 文最初の () の中身用の文字列を返す
	 * @return string insert 文最初の () の中身用の文字列
	 */
	public function getColumnStr(): string
	{
		return implode(",", $this->columns);
	}
	/**
	 * insert 文 values() の中身用の文字列を返す
	 * @return string insert 文 values() の中身用の文字列を返す
	 */
	public function getPlaceHolderStr(): string
	{
		return implode(",", $this->placeHolders);
	}
	/**
	 * update 以降で使用する文字列を返す
	 * @return string update 以降で使用する文字列
	 */
	public function getUpdateStr(): string
	{
		return implode(",", $this->updates);
	}
	//----------------------------
	/**
	 * 出力用の各配列を用意するメソッド
	 */
	private function make(): void
	{
		foreach ($this->columns as $col) {
			$this->placeHolders[] = "?";
			$this->updates[] = sprintf(self::DUPLICATE_TEMPLATE, $col, $col);
		}
	}
}
