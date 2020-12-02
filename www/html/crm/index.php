<?php

	// 必要ファイルの読み込み
	require('/var/www/ot_src/crm/propaty/Const.php');

	$fileName = isset($_GET['ac']) ? $_GET['ac'] : "Menu";

	// 実行ファイルの読み込み

	require(COMMON_DIR . "base/CommonBase.php");
	require(COMMON_DIR . "base/SiteBase.php");

	$filePath = MODEL_DIR  . $fileName . ".php";

	if(file_exists($filePath)){

		require($filePath);

		$src = new $fileName();
	}else{
		// 静的ファイル表示用 - modulesに対象のファイルが存在しない場合に呼ばれます。
		require(COMMON_DIR . "base/StaticScreenFilter.php");

		$src = new StaticScreenFilter();
	}

	// 処理実行 - common/baseのCommonBaseのexecute()が呼び出される。
	$src->execute();
