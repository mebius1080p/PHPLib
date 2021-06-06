<?php

declare(strict_types=1);

namespace Mebius\Net;

/**
 * CurlCommunicatorCore サーバー間通信コミュニケーターのコアモジュール
 */
class CurlCommunicatorCore
{
	public function __construct()
	{
		//dd;
	}
	/**
	 * 送信メソッド 今のところインスタンス化する必要は無いが、この構成にしておく
	 * @param string $url 送信先 URL
	 * @param array $curlOption curl のオプション配列
	 * @return string レスポンス body の文字列
	 * @throws \Exception エラーで例外
	 */
	public function send(string $url, array $curlOption): string
	{
		$ch = curl_init($url);
		if ($ch === false) {
			throw new \Exception("curl init failed", 1);
		}

		$result = "";
		try {
			$isAllSet = curl_setopt_array($ch, $curlOption);
			if ($isAllSet === false) {
				throw new \Exception("some curl option was not set", 1);
			}

			$result = (string)curl_exec($ch);

			if (curl_errno($ch) !== 0) {
				$message = curl_error($ch);
				throw new \Exception($message, 1);
			}
		} catch (\Exception $e) {
			throw $e;
		} finally {
			curl_close($ch);
		}

		return $result;
	}
}
