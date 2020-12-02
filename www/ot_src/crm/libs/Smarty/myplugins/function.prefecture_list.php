<?php
/**
 * Smarty plugin
 *
 * This plugin is only for Smarty2 BC
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {prefecture_list} function plugin
 *
 * Type:     function<br>
 * Name:     prefecture_list<br>
 * Purpose:  handle math computations in template<br>
 * @link http://smarty.php.net/manual/en/language.function.math.php {math}
 *          (Smarty online manual)
 * @author   OpenTone
 * @param array $params parameters
 * @param object $template template object
 * @return string|null
 */
function smarty_function_prefecture_list($params, $template)
{
	$list = array("" => "");

	// “s“¹•{Œ§ƒŠƒXƒgŽæ“¾
	$prefectureList = parse_ini_file(PROPATY_DIR . 'prefecture.ini');
	$params["options"] = $list + $prefectureList;

	if(!empty($params["val"])){
		// ˆø”‚ªƒŠƒXƒg‚É‚ ‚é‚©”»’è
		$key = array_search($params["val"], $prefectureList);
		$params["selected"] = $key;
	}

	// HTML¶¬
	require_once(SMARTY_PLUGINS_DIR . 'function.html_options.php');
	return smarty_function_html_options($params, $template);
}

?>