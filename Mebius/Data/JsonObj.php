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
	public $status = "bad";
	/**
	 * @var string 追加のメッセージを保持するフィールド
	 */
	public $message = "";
	/**
	 * @var mixed 配列にかかわらずその他データを格納するフィールド
	 */
	public $data = [];
	public function __construct()
	{
		// dd;
	}
}
