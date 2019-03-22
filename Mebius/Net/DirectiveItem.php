<?php
declare(strict_types=1);
namespace Mebius\Net;

/**
 * DirectiveItem csp manager2 の内部で使用する、ディレクティブクラス
 */
class DirectiveItem
{
	private const CSP_TEMPLATE = "%s %s;";
	/**
	 * @var string $src csp ソースの文字列
	 */
	private $src = "";
	/**
	 * @var string[] $directive ディレクティブの値配列
	 */
	private $directive = [];

	/**
	 * コンストラクタ
	 * @param string $src csp ソースの文字列
	 */
	public function __construct(string $src)
	{
		$this->src = $src;
	}
	/**
	 * csp ソース取得メソッド
	 * @return string
	 */
	public function getSource(): string
	{
		return $this->src;
	}
	/**
	 * ディレクティブ追加メソッド
	 * @param string $directive ディレクティブの値
	 */
	public function appendDirective(string $directive): void
	{
		if (\in_array($directive, $this->directive)) {
			// 重複登録はさせない
			return;
		}
		$this->directive[] = $directive;
	}
	/**
	 * このディレクティブで指定する csp ヘッダーの断片を出力する
	 * @return string
	 */
	public function getDirectiveString(): string
	{
		if (count($this->directive) === 0) {
			return "";
		}
		return \sprintf(self::CSP_TEMPLATE, $this->src, \implode(" ", $this->directive));
	}
}
