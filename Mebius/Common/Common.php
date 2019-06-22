<?php
declare(strict_types=1);
namespace Mebius\Common;

/**
 * Common よく使うメソッドを集めたクラス ci の commonlib を切り出し
 */
class Common
{
	/**
	 * @var string $debugFilePath デバッグ用ファイルパス
	 */
	private $debugFilePath = "";
	/**
	 * コンストラクタ
	 * @param string $debugFilePath デバッグ用ファイルパス
	 */
	public function __construct(string $debugFilePath = "")
	{
		$this->debugFilePath = $debugFilePath;
	}
	/**
	 * json を出力するメソッド
	 * @param mixed $obj 配列や stdClass など
	 */
	public function json($obj): void
	{
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($obj, JSON_UNESCAPED_UNICODE);
	}
	/**
	 * デバッグ用メソッド
	 * @param mixed $obj 何でも
	 */
	public function debug($obj): void
	{
		if ($this->debugFilePath === "") {
			return;
		}
		$dt = new \DateTime();
		$now = $dt->format("Y-m-d H:i:s");
		$debugStr = $now . ":" . print_r($obj, true) . "\n";
		file_put_contents($this->debugFilePath, $debugStr, LOCK_EX | FILE_APPEND);
	}
}
