<?php
/**
 * Smarty plugin
 *
 * This plugin is only for Smarty2 BC
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {get_ini_value} function plugin
 *
 * Type:     function<br>
 * Name:     get_ini_value<br>
 * Purpose:  handle math computations in template<br>
 * @link http://smarty.php.net/manual/en/language.function.math.php {math}
 *          (Smarty online manual)
 * @author   OpenTone
 * @param array $params parameters
 * @param object $template template object
 * @return string|null
 */
function smarty_function_get_ini_value($params, $template)
{
	static $list = array();

	$fileName = $params["file"];

	if(empty($list[$fileName])){
		$list[$fileName] = parse_ini_file(PROPATY_DIR . $params["file"] . ".ini");
	}

	$key = $params["key"];

	if(array_key_exists($key, $list[$fileName])){
		return $list[$fileName][$key];
	}

	return;
}

?>