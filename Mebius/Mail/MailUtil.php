<?php

declare(strict_types=1);

namespace Mebius\Mail;

use Swift_SmtpTransport;
use Swift_SendmailTransport;
use Swift_Transport_Esmtp_EightBitMimeHandler;
use Swift_Mailer;

/**
 * MailUtil swiftmailer ユーティリティークラス
 */
class MailUtil
{
	/**
	 * sendmail コマンド使用時のための swift 作成メソッド
	 * @return Swift_Mailer
	 */
	public static function createSwiftSendmail(): Swift_Mailer
	{
		$transport = new Swift_SendmailTransport();
		return new Swift_Mailer($transport);
	}
	/**
	 * swift mailer を作成するメソッド。(smtp)
	 * @param string $ipOrHost 接続する IP アドレス or ホスト名
	 * @param int $port ポート番号
	 * @return Swift_Mailer
	 * @throws \Exception 不正なアドレス or ポート番号で例外
	 */
	public static function createSwiftSmtp(string $ipOrHost, int $port = 25): Swift_Mailer
	{
		if ($ipOrHost === "") {
			throw new \Exception("empty ip or host", 1);
		}
		if ($port <= 0 || $port >= 65536) {
			throw new \Exception("invalid port number", 1);
		}

		$transport = new Swift_SmtpTransport($ipOrHost, $port);
		$eightBitMime = new Swift_Transport_Esmtp_EightBitMimeHandler();
		$transport->setExtensionHandlers([$eightBitMime]);

		return new Swift_Mailer($transport);
	}
}
