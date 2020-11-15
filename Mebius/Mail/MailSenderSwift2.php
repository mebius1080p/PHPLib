<?php

declare(strict_types=1);

namespace Mebius\Mail;

use Swift_SmtpTransport;
use Swift_SendmailTransport;
use Swift_Transport_Esmtp_EightBitMimeHandler;
use Swift_Mailer;

/**
 * MailSenderSwift2 swift mailer を使用したメール送信クラス
 * @deprecated
 */
class MailSenderSwift2
{
	/**
	 * @var ?Swift_Mailer $mailer
	 */
	private static ?Swift_Mailer $mailer = null;
	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		//
	}
	/**
	 * sendmail コマンド使用時のための swift 設定メソッド
	 * sendmail, smtp で引数が違いすぎるので別メソッドとして実装
	 */
	public static function setupSwiftSendmail(): void
	{
		$transport = new Swift_SendmailTransport();
		self::$mailer = new Swift_Mailer($transport);
	}
	/**
	 * swift mailer をセットアップするメソッド。(smtp)
	 * @param string $ipOrHost 接続する IP アドレス or ホスト名
	 * @param int $port ポート番号
	 * @throws \Exception 不正なアドレス or ポート番号で例外
	 */
	public static function setupSwiftSmtp(string $ipOrHost, int $port = 25): void
	{
		if ($ipOrHost === "") {
			throw new \Exception("invalid ip or host", 1);
		}
		if ($port <= 0 || $port >= 65536) {
			throw new \Exception("invalid port number", 1);
		}

		$transport = new Swift_SmtpTransport($ipOrHost, $port);
		$eightBitMime = new Swift_Transport_Esmtp_EightBitMimeHandler();
		$transport->setExtensionHandlers([$eightBitMime]);

		self::$mailer = new Swift_Mailer($transport);
	}
	/**
	 * swiftmailer 設定メソッド 主にテスト用
	 * @param Swift_Mailer $mailer swift mailer インスタンス
	 */
	public static function setSwiftMailer(Swift_Mailer $mailer): void
	{
		self::$mailer = $mailer;
	}
	/**
	 * swiftmailer リセットメソッド 主にテスト用
	 */
	public static function resetSwiftMailer(): void
	{
		self::$mailer = null;
	}
	/**
	 * メール送信メソッド
	 * @param MailParamCore $mpc MailParamCore のインスタンス
	 * @return bool メール送信の成否
	 * @throws \Exception トランスポートがセットアップされてないとき例外
	 */
	public function send(MailParamCore $mpc): bool
	{
		if (self::$mailer === null) {
			throw new \Exception("swift mailer is null", 1);
		}

		$swiftMessage = $mpc->getSwiftMessage();

		//@phan-suppress-next-line PhanPossiblyNonClassMethodCall
		$successCount = self::$mailer->send($swiftMessage);
		return $successCount > 0;
	}
}
