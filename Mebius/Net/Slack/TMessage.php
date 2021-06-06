<?php

declare(strict_types=1);

namespace Mebius\Net\Slack;

/**
 * TMessage メッセージ送信系のトレイト
 */
trait TMessage
{
	abstract public function sendWrapper(string $methodFamily, string $json): object;

	/**
	 * メッセージ書き込みメソッド
	 * @param string $channel 送信先チャンネル
	 * @param string $message 送信メッセージ
	 * @return object
	 */
	public function sendMessage(string $channel, string $message): object
	{
		$METHOD = "chat.postMessage";
		$param = [
			"channel" => $channel,
			"text" => $message,
		];
		$json = json_encode($param, JSON_UNESCAPED_UNICODE);
		if ($json === false) {
			throw new \Exception("json parameter failed", 1);
		}

		$obj = $this->sendWrapper($METHOD, $json);

		return $obj;
	}
}
