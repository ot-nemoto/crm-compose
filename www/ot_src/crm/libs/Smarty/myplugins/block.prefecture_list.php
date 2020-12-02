<?php
/**
 * Smarty plugin to format text blocks
 *
 * @package Smarty
 * @subpackage PluginsBlock
 */

/**
 * Smarty {prefecture_list}{/prefecture_list} block plugin
 * 
 * Type:     block function<br>
 * Name:     prefecture_list<br>
 * Purpose:  format text a certain way with preset styles
 *            or custom wrap/indent settings<br>
 * 
 * @link http://smarty.php.net/manual/en/language.function.prefecture_list.php {prefecture_list}
 *       (Smarty online manual)
 * @param array $params parameters
 * <pre>
 * Params:   style: string (email)
 *            indent: integer (0)
 *            wrap: integer (80)
 *            wrap_char string ("\n")
 *            indent_char: string (" ")
 *            wrap_boundary: boolean (true)
 * </pre>
 * @author Monte Ohrt <monte at ohrt dot com> 
 * @param string $content contents of the block
 * @param object $template template object
 * @param boolean &$repeat repeat flag
 * @return string content re-formatted
 */
function smarty_block_prefecture_list($params, $content, $template, &$repeat)
{
	if (is_null($content)) {
		return;
	}

	if ($repeat) {
		return;
	}
	$list = array("" => "");

	// 都道府県リスト取得
	$prefectureList = parse_ini_file(PROPATY_DIR . 'prefecture.ini');
	$params["options"] = $list + $prefectureList;

	if(!empty($params["val"])){
		// 引数がリストにあるか判定
		$key = array_search($params["val"], $prefectureList);
		$params["selected"] = $key;
	}

	if(empty($params["selected"])){
		// ブロック内を表示
		return $content;
	}else{
		// プルダウン生成
		require_once(SMARTY_PLUGINS_DIR . 'function.html_options.php');
		return smarty_function_html_options($params, $template);
	}
} 

?>