<?php

declare(strict_types=1);

namespace Mebius\Net;

/**
 * CSPManager CSP ヘッダーの文字列を作成するクラス
 * @deprecated
 */
class CSPManager
{
	private string $base = "";
	private array $image = [];
	private array $style = [];
	private array $script = [];
	private array $frame = [];
	private array $child = [];
	private array $connect = [];
	public function __construct()
	{
		$this->base = "Content-Security-Policy: default-src 'self';";
		$this->image[] = "'self'";
		$this->image[] = "data:";
		$this->image[] = "blob:";
		$this->style[] = "'self'";
		$this->script[] = "'self'";
		$this->frame[] = "'self'";
		$this->child[] = "'self'";
		$this->connect[] = "'self'";
	}
	/**
	 * image ソースを追加
	 * @param string $src image ソース
	 */
	public function addImage(string $src): void
	{
		$this->image[] = $src;
	}
	/**
	 * style ソースを追加
	 * @param string $src style ソース
	 */
	public function addStyle(string $src): void
	{
		$this->style[] = $src;
	}
	/**
	 * script ソースを追加
	 * @param string $src script ソース
	 */
	public function addScript(string $src): void
	{
		$this->script[] = $src;
	}
	/**
	 * child ソースを追加 frame にも自動で追加
	 * @param string $src child ソース
	 */
	public function addChild(string $src): void
	{
		$this->child[] = $src;
		$this->frame[] = $src;
	}
	/**
	 * connect ソースを追加
	 * @param string $src connect ソース
	 */
	public function addConnect(string $src): void
	{
		$this->connect[] = $src;
	}
	/**
	 * csp 文字列を組み立てて返すメソッド
	 * @return string csp 文字列
	 */
	public function getCSP(): string
	{
		$csp = $this->base;
		$cspFragment = [];
		$cspFragment[] = "img-src " . implode(" ", $this->image) . ";";
		$cspFragment[] = "style-src " . implode(" ", $this->style) . ";";
		$cspFragment[] = "script-src " . implode(" ", $this->script) . ";";
		$cspFragment[] = "frame-src " . implode(" ", $this->frame) . ";";
		$cspFragment[] = "child-src " . implode(" ", $this->child) . ";";
		$cspFragment[] = "connect-src " . implode(" ", $this->connect) . ";";
		$csp .= implode("", $cspFragment);
		return $csp;
	}
}
