<?php

	// フォルダパス情報
	define('ROOT_DIR',      "/var/www/ot_src/crm/");
	define('COMMON_DIR',    ROOT_DIR . "source/common/");
	define('LIB_DIR',       ROOT_DIR . "libs/");
	define('PROPATY_DIR',   ROOT_DIR . "propaty/");
	define('BL_DIR',        ROOT_DIR . "source/business/");
	define('MODEL_DIR',     ROOT_DIR . "source/modules/");
	define('DAO_DIR',       ROOT_DIR . "source/dao/");
	define('TEMPLATE_DIR',  ROOT_DIR . "templates/");
	define('COMPILE_DIR',   ROOT_DIR . "work/");

	// Smartyを読み込み
	require_once(LIB_DIR . "Smarty/MySmarty.php");

	// 会社一覧件数表示上限
	define('COMPANY_LIST_MAX_COUNT', 50);

	// 部署一覧件数表示上限
	define('SECTION_LIST_MAX_COUNT', 10);

	// 担当者一覧件数表示上限
	define('PERSON_LIST_MAX_COUNT', 50);

