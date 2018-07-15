<?php
namespace Mebius\Mail;

use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * MailParam メール送信のためのパラメーターを保持するクラス
 */
class MailParam
{
	private $from = "";
	private $to = "";
	private $subject = "";
	private $message = "";
	/**
	 * コンストラクタ
	 * @param string $form from メールアドレス
	 * @param string $to to メールアドレス
	 * @param string $subject メールタイトル
	 * @param string $templateTilePath メール本文のテンプレートとなるファイルパス
	 * @param array $templateParam メール本文作成用のテンプレートパラメーターを納めた連想配列
	 * @throws \Exception 引数エラーで例外
	 */
	public function __construct(string $from, string $to, string $subject, string $templateTilePath, array $templateParam)
	{
		if (filter_var($from, FILTER_VALIDATE_EMAIL) === false
			|| filter_var($to, FILTER_VALIDATE_EMAIL) === false
			) {
			throw new \Exception("invalid mail address", 1);
		}
		if ($subject === "") {
			throw new \Exception("empty subject", 1);
		}
		if (!is_file($templateTilePath)) {
			throw new \Exception("template file does not exist", 1);
		}

		$this->from = $from;
		$this->to = $to;
		$this->subject = $subject;

		$loader = new Twig_Loader_Filesystem(dirname($templateTilePath));
		$twig = new Twig_Environment($loader);

		//こちらは空でもよしとする
		$this->message = $twig->render(basename($templateTilePath), $templateParam);
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
	 * to アドレスを返すメソッド
	 * @return string
	 */
	public function getTo(): string
	{
		return $this->to;
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
}
