<?php

declare(strict_types=1);

namespace Mebius\Mail;

use Swift_Mailer;

/**
 * MailSenderSwift3 swift mailer を使用したメール送信クラス
 */
class MailSenderSwift3
{
	/**
	 * @var ?Swift_Mailer $mailer
	 */
	private static ?Swift_Mailer $mailer = null;
	/**
	 * コンストラクタ
	 * @param Swift_Mailer $mailer swift mailer
	 */
	public function __construct(Swift_Mailer $mailer)
	{
		if (self::$mailer === null) {
			self::$mailer = $mailer;
		}
	}

	/**
	 * swiftmailer 取得するメソッド 主にテスト用
	 * @return ?Swift_Mailer
	 */
	public static function getSwiftMailer(): ?Swift_Mailer
	{
		return self::$mailer;
	}
	/**
	 * swiftmailer 置換メソッド 主にテスト用
	 * @param Swift_Mailer $mailer swift mailer インスタンス
	 */
	public static function replaceSwiftMailer(Swift_Mailer $mailer): void
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
