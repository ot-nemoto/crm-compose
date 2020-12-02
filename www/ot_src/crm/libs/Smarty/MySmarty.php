<?php

require_once('Smarty.class.php');

class MySmarty extends Smarty
{
	public function MySmarty()
	{
		parent::__construct();

		$this->template_dir    =  TEMPLATE_DIR;
		$this->compile_dir     =  COMPILE_DIR . 'smarty_compile/';
		$this->plugins_dir     =  array(SMARTY_PLUGINS_DIR,SMARTY_DIR . 'myplugins' . DS);
		$this->left_delimiter  =  '{{';
		$this->right_delimiter =  '}}';
	}
}