<?php

declare(strict_types=1);

namespace Mebius\Data;

/**
 * JsonObj ajax レスポンス用 json オブジェクト
 */
class JsonObj
{
	/**
	 * @var string サーバーでの処理結果を表す bad|ok
	 */
	public string $status = "bad";
	/**
	 * @var string 追加のメッセージを保持するフィールド
	 */
	public string $message = "";
	/**
	 * @var mixed 配列にかかわらずその他データを格納するフィールド
	 */
	public $data = [];
	public function __construct()
	{
		// dd;
	}
}
