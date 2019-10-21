<?php

declare(strict_types=1);

namespace Mebius\Mail;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * MailParamWithTwig twig を使ってメールメッセージを作成するクラス
 */
class MailParamWithTwig extends MailParamCore
{
	/**
	 * コンストラクタ
	 * @param string $mailTemplatePath メールテンプレートパス
	 * @param string $templateParam テンプレートパラメーター
	 */
	public function buildMessage(string $mailTemplatePath, array $templateParam)
	{
		if (!file_exists($mailTemplatePath)) {
			throw new \Exception("template file does not exist", 1);
		}
		if (!is_file($mailTemplatePath)) {
			throw new \Exception("template path is not file", 1);
		}

		$loader = new FilesystemLoader(dirname($mailTemplatePath));
		$twig = new Environment($loader);

		$this->message = $twig->render(basename($mailTemplatePath), $templateParam);
	}
}
