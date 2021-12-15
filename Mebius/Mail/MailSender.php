<?php

declare(strict_types=1);

namespace Mebius\Mail;

use Mebius\Logger\TLogger;
use Symfony\Component\Mailer\Transport\TransportInterface;

/**
 * MailSender サービスプロバイダーなどではこのクラスのインスタンスまで作って返すとよい
 */
class MailSender
{
	use TLogger;

	private TransportInterface $transport;

	/**
	 * コンストラクタ
	 * @param TransportInterface $transport メールトランスポート
	 */
	public function __construct(TransportInterface $transport)
	{
		$this->transport = $transport;
	}
	/**
	 * メール送信メソッド
	 * @param MailParamCore $mailParam メール送信パラメーター
	 * @return bool
	 */
	public function send(MailParamCore $mailParam): bool
	{
		//例外が出なかったら送信されたと見なす
		$mailSent = false;
		try {
			$this->transport->send($mailParam->getSymfonyMail());
			$mailSent = true;
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}

		return $mailSent;
	}
}
