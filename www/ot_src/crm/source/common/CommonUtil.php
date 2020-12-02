<?php
class CommonUtil
{
	public function h(& $str)
	{
		if(is_array($str)){
			foreach($str as &$s){
				$s = self::h($s);
			}
		}

		if(is_string($str)){
			return htmlspecialchars($str);
		}

		return $str;
	}

	public function getIniValue($iniFileName, $key)
	{
		$list = parse_ini_file(PROPATY_DIR . $iniFileName . '.ini');
		return isset($list[$key]) ? $list[$key] : "";
	}

	public function convertSearchValue($str)
	{
		// 標準関数で置き換え
		$str = str_replace(" ", "", mb_convert_kana($str, "khas", "utf-8"));

		// 小文字半角カタカナを全角に置き換え
		$pattern = array('ｧ','ｨ','ｩ','ｪ','ｫ','ｬ','ｭ','ｮ','ｯ');
		$replacement = array('ｱ','ｲ','ｳ','ｴ','ｵ','ﾔ','ﾕ','ﾖ','ﾂ');
		return str_replace($pattern, $replacement, $str);
	}

	public function convertNumberValue($str)
	{
		return mb_convert_kana($str, "as", "utf-8");
	}
}
