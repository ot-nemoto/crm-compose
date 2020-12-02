<?php
/**
 * Smarty plugin
 *
 * This plugin is only for Smarty2 BC
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {paging} function plugin
 *
 * Type:     function<br>
 * Name:     paging<br>
 * Purpose:  handle math computations in template<br>
 * @link http://smarty.php.net/manual/en/language.function.math.php {math}
 *          (Smarty online manual)
 * @author   OpenTone
 * @param array $params parameters
 * @param object $template template object
 * @return string|null
 */
function smarty_function_paging($params, $template)
{
	// 各種定義
	$strPrev   = "&lt;前へ";
	$strNext   = "次へ&gt;";
	$spacer = "&nbsp;&nbsp;&nbsp;";

	$shref = '<a href=?';
	$ehref = '>';
	$endTag = '</a>';

	$totalCount = empty($params["total_count"]) ? 0 : $params["total_count"];
	$page_count = empty($params["page_count"]) ? 0 : $params["page_count"];
	$target    = $params["target"];

	$currentPage = empty($_GET[$target]) ? 1 : $_GET[$target];

	// 初期設定
	$prev   = $strPrev;
	$next   = $strNext;

	// 前ページがあるか判定
	if($currentPage > 1){
		// リンク生成
		$num = ($currentPage - 1) < 1 ? 1 : ($currentPage - 1);
		$page = $target . "=" . $num;

		// リンク再現
		$qs = "";
		foreach($_GET as $k=>$v){
			if($k != $target){
				$qs .= "&" . $k . "=" . $v;
			}
		}

		// 前ページリンク
		$prev = $shref . $page . $qs . $ehref . $strPrev . $endTag;
	}

	// 後ページがあるか判定
	if(($currentPage * $page_count) < $totalCount){
		// リンク生成
		$page = $target . "=" . ($currentPage + 1);

		// リンク再現
		$qs = "";
		foreach($_GET as $k=>$v){
			if($k != $target){
				$qs .= "&" . $k . "=" . $v;
			}
		}

		// 後ページリンク
		$next = $shref . $page . $qs . $ehref . $strNext . $endTag;
	}


	// ページング表示
	echo $prev . $spacer . $next;

	return;
}

?>