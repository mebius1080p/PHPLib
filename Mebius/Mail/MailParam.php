<?php
namespace Mebius\Mail;

use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * MailParam メール送信のためのパラメーターを保持するクラス
 * テキストは utf8 メールを想定。添付ファイルを付けるときだけ ISO2022 を指定し、本文は base64 エンコードする
 */
class MailParam
{
	//改行コード
	public const LF = "\n";
	public const CRLF = "\r\n";

	//メール自体のエンコーディング 基本は uni で、添付のあるときだけ ja にする
	public const UTF8 = "uni";
	public const ISO2022 = "ja";

	private $to = "";
	private $subject = "";
	private $message = "";

	private $headers = [];

	/**
	 * @var string 改行コード
	 */
	private $lineBreak = "\r\n";

	/**
	 * @var bool 添付ファイルを設定するかのフラグ
	 */
	private $hasAttach = false;

	/**
	 * @var string[] 添付ファイルパス文字列配列
	 */
	private $attaches = [];

	/**
	 * @var string メール本文中の、添付ファイルの境界文字列
	 */
	private $boundary = "__BOUNDARY__";

	/**
	 * @var bool 添付ファイル追加時に必要なヘッダーを追加したかどうかのチェックフラグ
	 */
	private $attachHeaderAppended = false;

	/**
	 * コンストラクタ
	 * @param string $from from メールアドレス
	 * @param string $to to メールアドレス
	 * @param string $subject メールタイトル
	 * @param string $templateFilePath メール本文のテンプレートとなるファイルパス
	 * @param array $templateParam メール本文作成用のテンプレートパラメーターを納めた連想配列
	 * @throws \Exception 引数エラーで例外
	 */
	public function __construct(string $from, string $to, string $subject, string $templateFilePath, array $templateParam)
	{
		if (filter_var($from, FILTER_VALIDATE_EMAIL) === false
			|| filter_var($to, FILTER_VALIDATE_EMAIL) === false
			) {
			throw new \Exception("invalid mail address", 1);
		}
		if ($subject === "") {
			throw new \Exception("empty subject", 1);
		}
		if (!file_exists($templateFilePath)) {
			throw new \Exception("template file does not exist", 1);
		}
		if (!is_file($templateFilePath)) {
			throw new \Exception("template path is not file", 1);
		}

		$this->to = $to;
		$this->subject = $subject;

		$loader = new Twig_Loader_Filesystem(dirname($templateFilePath));
		$twig = new Twig_Environment($loader);

		//こちらは空でもよしとする
		$this->message = $twig->render(basename($templateFilePath), $templateParam);

		$this->headers[] = "From: " . $from;
	}
	/**
	 * 改行コードを LF にするメソッド
	 */
	public function setLF(): void
	{
		$this->lineBreak = self::LF;
	}
	/**
	 * 改行コードを CRLF にするメソッド
	 */
	public function setCRLF(): void
	{
		$this->lineBreak = self::CRLF;
	}
	/**
	 * メール送信のエンコーディング文字列を返すメソッド
	 * 添付ファイルを付けるときだけ ISO2022 を使用し、他は utf8 メールを前提とする
	 * @return string
	 */
	public function getEncoding(): string
	{
		if ($this->hasAttach) {
			//添付ファイルを付けるときは、2022 にしておかないとまずいようだ
			return self::ISO2022;
		} else {
			return self::UTF8;
		}
	}
	/**
	 * メールヘッダーを返すメソッド
	 * @return string
	 */
	public function getHeader(): string
	{
		return implode($this->lineBreak, $this->headers);
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
	public function getBody(): string
	{
		if ($this->hasAttach) {
			$b64Message = chunk_split(base64_encode($this->message));
			$bodies = [
				"--{$this->boundary}",
				"Content-Type: text/plain; charset=\"UTF-8\"",
				"Content-Transfer-Encoding: base64" . $this->lineBreak,//改行 2 回
				$b64Message,
				"--{$this->boundary}",
			];
			foreach ($this->attaches as $attach) {
				$fileName = basename($attach);
				$mime = mime_content_type($attach);
				$content = chunk_split(base64_encode(file_get_contents($attach)));
				$bodies[] = "Content-Type: {$mime}; name=\"{$fileName}\"";
				$bodies[] = "Content-Disposition: attachment; filename=\"{$fileName}\"";
				$bodies[] = "Content-Transfer-Encoding: base64" . $this->lineBreak;//改行 2 回
				$bodies[] = $content;
				$bodies[] = "--{$this->boundary}";
			}
			return implode($this->lineBreak, $bodies);
		} else {
			//一度改行で分割してから、所定の改行コードで組み立て直す
			$messages = preg_split("/\r\n|\n/", $this->message);
			return implode($this->lineBreak, $messages);
		}
	}
	/**
	 * 添付ファイル追加メソッド
	 * @param string $filePath 添付ファイルのパス ファイル名は事前にチェックしておくこと
	 */
	public function addAttach(string $filePath): void
	{
		if (file_exists($filePath) && is_file($filePath)) {
			$this->hasAttach = true;
			$this->attaches[] = $filePath;
			if (!$this->attachHeaderAppended) {//多重追加回避
				$this->headers[] = "Content-Type: multipart/mixed;boundary=\"{$this->boundary}\"";
				$this->attachHeaderAppended = true;
			}
		}
	}
}
