<?php
declare(strict_types=1);
namespace Mebius\Net;

/**
 * CSPManager2 csp manager の改良版
 */
class CSPManager2
{
	public const CHILD = "child-src";
	public const CONNECT = "connect-src";
	public const FONT = "font-src";
	public const FRAME = "frame-src";
	public const IMAGE = "img-src";
	public const MEDIA = "media-src";
	public const SCRIPT = "script-src";
	public const STYLE = "style-src";
	public const WORKER = "worker-src";

	/**
	 * @var DirectiveItem[] $directiveDec DirectiveItem の配列
	 */
	private $directives = [];

	public function __construct()
	{
		$rc = new \ReflectionClass(CSPManager2::class);
		$constants = $rc->getConstants();
		foreach ($constants as $key => $value) {
			$this->directives[] = new DirectiveItem($value);
		}
	}
	/**
	 * ディレクティブ追加メソッド
	 * @param string $src ディレクティブソース。このクラスの const を使うことを推奨
	 * @param string $directive 追加するディレクティブの値
	 */
	public function addDirective(string $src, string $directive): void
	{
		$filteredDirective = \array_filter($this->directives, function ($directive) use ($src) {
			return $directive->getSource() === $src;
		});
		$trimedDirectives = \array_values($filteredDirective);
		if (count($trimedDirectives) === 0) {
			return;
		}
		$trimedDirectives[0]->appendDirective($directive);
	}
	/**
	 * http ヘッダーに出力する csp 文字列を出力するメソッド
	 * @return string
	 */
	public function getCSPString(): string
	{
		// const で書きたいところだが、ReflectionClass を使っているためここに記述
		$BASE_CSP_HEADER = "Content-Security-Policy: default-src 'self';";

		$cspStringArray = [];
		foreach ($this->directives as $directive) {
			$directiveString = $directive->getDirectiveString();
			if ($directiveString !== "") {
				$cspStringArray[] = $directiveString;
			}
		}
		return $BASE_CSP_HEADER . \implode("", $cspStringArray);
	}
}
