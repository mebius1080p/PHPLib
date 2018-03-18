<?php
namespace Mebius\Data;

/**
* JsonObj ajax レスポンス用 json オブジェクト
*/
class JsonObj
{
	public $status = "bad";
	public $message = "";
	public $data = [];
	public function __construct()
	{
		// dd;
	}
}
