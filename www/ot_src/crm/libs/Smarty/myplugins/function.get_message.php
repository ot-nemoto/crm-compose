<?php
/**
 * Smarty plugin
 *
 * This plugin is only for Smarty2 BC
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {get_message} function plugin
 *
 * Type:     function<br>
 * Name:     get_message<br>
 * Purpose:  handle math computations in template<br>
 * @link http://smarty.php.net/manual/en/language.function.math.php {math}
 *          (Smarty online manual)
 * @author   OpenTone
 * @param array $params parameters
 * @param object $template template object
 * @return string|null
 */
function smarty_function_get_message($params, $template)
{
	static $messageList = null;

	if(empty($messageList)){
		$messageList = parse_ini_file(PROPATY_DIR . 'message_text.ini');
	}

	$key = empty($params["key"]) ? "" : $params["key"];

	if(array_key_exists($key, $messageList)){
		return $messageList[$key];
	}

	return;
}

?>