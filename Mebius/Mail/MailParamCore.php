<?php

declare(strict_types=1);

namespace Mebius\Mail;

use Symfony\Component\Mime\Email;

/**
 * MailParamCore シンプルなメールパラメーター所持クラス
 * message に関しては継承クラスでどうにかして設定することを想定
 */
abstract class MailParamCore
{
	/**
	 * @var string $from from メールアドレス
	 */
	private string $from = "";
	/**
	 * @var string $to to メールアドレス
	 */
	private string $to = "";
	/**
	 * @var string $subject メールアドレス
	 */
	private string $subject = "";
	/**
	 * @var string $message メールメッセージ
	 */
	protected string $message = "";
	/**
	 * コンストラクタ
	 * @param string $from from メールアドレス
	 * @param string $to to メールアドレス
	 * @param string $subject メールタイトル
	 * @throws \Exception エラーで例外
	 */
	public function __construct(string $from, string $to, string $subject)
	{
		if (
			filter_var($from, FILTER_VALIDATE_EMAIL) === false
			|| filter_var($to, FILTER_VALIDATE_EMAIL) === false
		) {
			throw new \Exception("invalid mail address", 1);
		}
		if ($subject === "") {
			throw new \Exception("empty subject", 1);
		}
		$this->from = $from;
		$this->to = $to;
		$this->subject = $subject;
	}
	/**
	 * to アドレスを返すメソッド
	 * @return string
	 */
	public function getTo(): string
	{
		return $this->to;
	}
	/**
	 * from アドレスを返すメソッド
	 * @return string
	 */
	public function getFrom(): string
	{
		return $this->from;
	}
	/**
	 * メールタイトルを返すメソッド
	 * @return string
	 */
	public function getSubject(): string
	{
		return $this->subject;
	}
	/**
	 * メール本文を返すメソッド
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}
	/**
	 * symfonymailer 用のメールオブジェクトを返すメソッド
	 * message については呼び出し前に継承クラスで作っておく
	 * @return Email
	 */
	public function getSymfonyMail(): Email
	{
		$mail = new Email();
		$mail->from($this->getFrom())
			->to($this->getTo())
			->subject($this->getSubject())
			->text($this->getMessage());

		return $mail;
	}
}
