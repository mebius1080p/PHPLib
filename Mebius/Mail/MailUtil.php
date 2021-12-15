<?php

declare(strict_types=1);

namespace Mebius\Mail;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;

/**
 * MailUtil メールユーティリティークラス
 */
class MailUtil
{
	/**
	 * symfony mailer 用の DSN を用いて transport を作り出すメソッド
	 * どんな送信方法を使うかは、DSN の記述により表現する
	 * @param string $dsn トランスポート作成用 DSN
	 * @param LoggerInterface|null $logger ロガー
	 * @return TransportInterface
	 * @throws \Exception エラーで例外
	 */
	public static function createSymfonyMailerSmtp(string $dsn, LoggerInterface $logger = null): TransportInterface
	{
		if ($dsn === "") {
			throw new \Exception("mail DSN is empty", 1);
		}

		$transport = Transport::fromDsn($dsn, null, null, $logger);
		return $transport;
	}
}
