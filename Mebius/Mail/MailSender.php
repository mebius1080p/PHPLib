<?php

declare(strict_types=1);

namespace Mebius\Mail;

/**
 * MailSender メール送信クラス
 * @deprecated
 */
class MailSender
{
	public function __construct()
	{
		// dd;
	}
	/**
	 * メール送信メソッド
	 * @param MailParam $mp メール送信パラメーター保持オブジェクト
	 * @return bool メール送信できたか否かのフラグ 失敗したからといって必ずしも例外が必要ではないので bool を返す
	 */
	public function send(MailParam $mp): bool
	{
		mb_language($mp->getEncoding());
		return mb_send_mail($mp->getTo(), $mp->getSubject(), $mp->getBody(), $mp->getHeader());
	}
}
