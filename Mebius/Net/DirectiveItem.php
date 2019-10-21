<?php

declare(strict_types=1);

namespace Mebius\Net;

/**
 * DirectiveItem csp manager2 の内部で使用する、ディレクティブクラス
 */
class DirectiveItem
{
	public const CSP_SELF = "'self'";
	public const CSP_BLOB = "blob:";
	public const CSP_DATA = "data:";
	public const CSP_UNSAFE_INLINE = "'unsafe-inline'";

	private const CSP_TEMPLATE = "%s %s;";
	/**
	 * @var string $src csp ソースの文字列
	 */
	private $src = "";
	/**
	 * @var string[] $directive ディレクティブの値配列
	 */
	private $directives = [];
	/**
	 * @param string[] $allowedDirectivePatterns ディレクティブで使用可能な正規表現
	 */
	private $allowedDirectivePatterns = [
		"/\A'self'\z/",
		"/\Ablob:\z/",
		"/\Adata:\z/",
		"/\A'none'\z/",
		"/\A'unsafe-inline'\z/",
		"/\Ahttps?:\/\/.+\z/",
		//お好みでハッシュ系を許可……
	];

	/**
	 * コンストラクタ
	 * @param string $src csp ソースの文字列 内部で使用するクラスのため値チェックなし
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
	 * @param string $source csp ソース文字列 CSPManager2 の定数を使うことを推奨
	 * @param string $directive ディレクティブの値 一部はこのクラスの定数を使うと便利
	 */
	public function appendDirective(string $source, string $directive): void
	{
		if ($this->src !== $source || $directive === "") {
			return;
		}
		$matchedDirectivePattern = array_values(
			\array_filter($this->allowedDirectivePatterns, function ($pattern) use ($directive) {
				return \preg_match($pattern, $directive) === 1;
			})
		);
		if (\count($matchedDirectivePattern) === 0) {
			return;
		}
		if (\in_array($directive, $this->directives)) {
			// 重複登録はさせない
			return;
		}
		$this->directives[] = $directive;
	}
	/**
	 * このディレクティブで指定する csp ヘッダーの断片を出力する
	 * @return string
	 */
	public function getDirectiveString(): string
	{
		if (count($this->directives) === 0) {
			return "";
		}
		return \sprintf(self::CSP_TEMPLATE, $this->src, \implode(" ", $this->directives));
	}
}
