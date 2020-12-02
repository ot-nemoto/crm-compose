<?php

abstract class CommonBase
{
	protected $tpl = array();
	protected $tplh = array();
	protected $obj = array();
	protected $tplFile = "";

	public function execute()
	{
		try{
			// サイト依存の事前処理
			$this->beforeFilter();

			// メイン処理実行
			$this->transact();

			// テンプレート設定処理
			$smarty = new MySmarty();

			// 普通にsmartyに変数登録します。
			foreach($this->tpl as $k => $v){
				$smarty->assign($k, $v);
			}

			// エスケープ処理してsmartyに変数登録します。（XSS対応）
			foreach($this->tplh as $k => $v){
				$smarty->assign($k, CommonUtil::h($v));
			}

			// smartyにオブジェクトとして登録。（まだ使ったことない…）
			foreach($this->obj as $k => $v){
				$smarty->register_object($k, $v);
			}

			// 読み込みテンプレートの指定。初期値はacで指定された文字列＋「.html」
			if(empty($this->tplFile)){
				$this->tplFile = $GLOBALS['fileName'] . ".html";
			}

			// smartyによるhtml生成処理
			$chtml = $smarty->fetch($this->tplFile);

			// サイト依存の事後処理処理
			$this->afterFilter();

			// html表示
			echo $chtml;
		}
		catch(Exception $e){
			var_dump($e->getMessage());
		}
	}

	// 自動で読み込みたいファイルがあるフォルダを設定できます。
	// （ほとんどテストしてない。。。）
	// 引数は配列でください。
	// ex)
	//   array(BL_DIR . 'sample/inc/' , BL_DIR . 'sample/ext/')
	protected function setIncludePath($path)
	{
		if(is_array($path)){
			$GLOBALS['include_path'] = array_merge($GLOBALS['include_path'], $path);
		}
	}

	protected function transact() { }

	abstract protected function beforeFilter();

	abstract protected function afterFilter();
}

// 読み込みファイル定義
$include_path = array(
	BL_DIR, 
	DAO_DIR);

// このメソッドでクラスの自動読み込みを行っています。
// ロードされていないクラスが宣言されると、ここのfunctionが呼ばれます。
// これをうまく利用するといちいちincludeする手間が省け、
// bugの発生を抑えられるメリットがあります。
// その反面、ロードされていないクラスを宣言されるたびに、
// 指定のフォルダを探しに行くので、検索対象フォルダは適度に。。。
function __classautoload($class){
	// 検索する拡張子一覧
	$exts = array("php", "class.php", "inc", "class.inc");

	// 検索するディレクトリ一覧
	$defPath = array(COMMON_DIR, LIB_DIR);

	$dirs = array_merge($defPath, $GLOBALS['include_path']);

	// include_path を検索し、存在すればそのファイルをインクルードする。
	// 存在しなければ何もしない。
	foreach($dirs as $dir){
		foreach($exts as $ext){
			$file = $dir . $class . "." . $ext;
			if($fp = @fopen($file, "r", 1)){
				@fclose($fp);
				require_once $file;
				return;
			}
		}
	}
}

// クラス自動読み込み登録
// 新しいversionのsmartyを使っている場合明示的に指定する必要がある模様。
spl_autoload_register("__classautoload");
