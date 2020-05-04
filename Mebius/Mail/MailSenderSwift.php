<?php

declare(strict_types=1);

namespace Mebius\Mail;

use Swift_SmtpTransport;
use Swift_Transport_Esmtp_EightBitMimeHandler;
use Swift_Mailer;
use Swift_Mime_ContentEncoder_PlainContentEncoder;
use Swift_Message;

/**
 * MailSenderSwift swift mailer を使用した新バージョンのメール送信クラス
 * smtp 送信する想定
 */
class MailSenderSwift
{
	/**
	 * @var string $ip IP アドレス or ホスト名
	 */
	private string $ipOrHost = "";
	/**
	 * @var int $port ポート番号
	 */
	private int $port = 0;
	/**
	 * コンストラクタ
	 * @param string $ipOrHost IP アドレス or ホスト名
	 * @param int $port ポート番号
	 * @throws \Exception 不正なアドレス or ポート番号で例外
	 */
	public function __construct(string $ipOrHost, int $port = 25)
	{
		if ($ipOrHost === "") {
			throw new \Exception("invalid ip or host", 1);
		}
		if ($port <= 0 || $port >= 65536) {
			throw new \Exception("invalid port number", 1);
		}
		$this->ipOrHost = $ipOrHost;//簡易バリデートしかしないので扱い注意
		$this->port = $port;
	}
	/**
	 * メール送信メソッド
	 * @param MailParamCore $mpc MailParamCore のインスタンス
	 * @return bool メール送信の成否
	 */
	public function send(MailParamCore $mpc): bool
	{
		$transport = new Swift_SmtpTransport($this->ipOrHost, $this->port);
		$eightBitMime = new Swift_Transport_Esmtp_EightBitMimeHandler();
		$transport->setExtensionHandlers([$eightBitMime]);
		$mailer = new Swift_Mailer($transport);

		$message = new Swift_Message($mpc->getSubject());
		$plainEncoder = new Swift_Mime_ContentEncoder_PlainContentEncoder('8bit');
		$message->setEncoder($plainEncoder);
		$message->setFrom($mpc->getFrom());
		$message->setTo($mpc->getTo());
		$message->setBody($mpc->getMessage());

		$successCount = $mailer->send($message);
		return $successCount > 0;
	}
}
