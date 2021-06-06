<?php

declare(strict_types=1);

namespace Mebius\Net\Slack;

use Mebius\Net\CurlCommunicatorCore;
use Mebius\Logger\TLogger;
use Mebius\Util\PHPUtil;

/**
 * SlackCommunicator slack communicator
 */
class SlackCommunicator
{
	use TMessage;
	use TLogger;

	private const BASE_URL = "https://slack.com/api/";
	private string $token = "";
	private int $timeout = 30;

	private CurlCommunicatorCore $core;
	/**
	 * コンストラクタ
	 * @param string $token トークン
	 * @param CurlCommunicatorCore $core コアコミュニケーター
	 * @throws \Exception エラーで例外
	 */
	public function __construct(string $token, CurlCommunicatorCore $core)
	{
		if ($token === "") {
			throw new \Exception("token is empty", 1);
		}
		$this->token = $token;
		$this->core = $core;
	}
	/**
	 * タイムアウト設定メソッド
	 * @param int $timeout タイムアウト秒
	 */
	public function setTimeout(int $timeout): void
	{
		if ($timeout > 0) {
			$this->timeout = $timeout;
		}
	}
	public function sendWrapper(string $methodFamily, string $json): object
	{
		if ($methodFamily === "") {
			throw new \Exception("method family is empty", 1);
		}
		if ($json === "") {
			throw new \Exception("json is empty", 1);
		}

		$finalURL = self::BASE_URL . $methodFamily;

		$curlOption = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => $this->timeout,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $json,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json; charset=UTF-8",
				sprintf("Authorization: Bearer %s", $this->token),
			]
		];

		$response = $this->core->send($finalURL, $curlOption);

		$json = json_decode($response);
		if ($json === null) {
			throw new \Exception("json decode failed:${response}", 1);
		}
		if (!is_object($json)) {
			throw new \Exception("json is not object:${response}", 1);
		}
		PHPUtil::propCheck($json, "ok");

		if ($json->ok !== true) {
			throw new SlackException("ステータスエラー", $json);
		}

		return $json;
	}
}
