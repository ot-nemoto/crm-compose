<?php
class DaoBase
{
	private static $conn = null;

	// コネクション取得
	// Singleton実装なので、好きなだけ呼び出してください。
	// １セッション１コネクションまでとしてます。
	protected function getConnection()
	{
		if(empty(self::$conn)){

			// TODO:そのうちリテラルはやめよう！
			// データベースのインスタンス名を指定
			$serverName = "db";

			//接続情報を指定 
			$connectionInfo = array("UID"=>"SA", 
							"PWD"=>"Passw0rd?", 
							"Database"=>"CustomerManagementFrom110815", 
							"CharacterSet"=>"UTF-8"); 

			// コネクションを確立 
			self::$conn = sqlsrv_connect($serverName, $connectionInfo);

			if(empty(self::$conn)){
				throw new Exception("connection create error");
			}
		}

		return self::$conn;
	}

	// 複数レコード返却を想定
	// ex)
	// array(2) {
	//   [0]=>
	//   array(2) {
	//     ["CompanyID"]=>string(3) "375"
	//     ["CompanyName"]=>string(33) "株式会社オープントーン"
	//   }
	//   [1]=>
	//   array(21) {
	//     ["CompanyID"]=>string(3) "265"
	//     ["CompanyName"]=>string(16) "株式会社オープン"
	//   }
	// }
	public function fetch($sql)
	{
		$ret = array();
		$conn = $this->getConnection();

		// クエリーを実行 
		$stmt = sqlsrv_query($conn, $sql); 

		if(empty($stmt)){
			return array();
		}

		while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
			$ret[] = $row;
		}

		// クエリー結果の開放 
		sqlsrv_free_stmt($stmt);

		return $ret;
	}

	// 単件レコード返却を想定
	// ex)
	//   array(2) {
	//     ["CompanyID"]=>string(3) "375"
	//     ["CompanyName"]=>string(33) "株式会社オープントーン"
	//   }
	public function getRecord($sql)
	{
		$ret = array();
		$conn = $this->getConnection();

		// クエリーを実行 
		$stmt = sqlsrv_query($conn, $sql); 

		if(empty($stmt)){
			return array();
		}

		$ret = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

		// クエリー結果の開放 
		sqlsrv_free_stmt($stmt);

		return $ret;
	}

	// 更新クエリー実行
	public function execute($sql)
	{
		$ret = array();
		$conn = $this->getConnection();

		// クエリーを実行 
		$stmt = sqlsrv_query($conn, $sql); 

		$ret = sqlsrv_rows_affected($stmt);

		// クエリー結果の開放 
		sqlsrv_free_stmt($stmt);

		return $ret;
	}

	// デストラクタ
	public function __destruct()
	{
		// コネクションのクローズ 
		if(is_resource(self::$conn)){
			sqlsrv_close(self::$conn);
		}
	}

	// 新規登録（PKがインクリメントで自動発行される場合用）
	public function insert($params)
	{
		$params = DaoUtil::setInsertInfo($params);
		$query = DaoUtil::buildInsertQuery($this->table, $params);

		$ret = $this->execute($query);

		if($ret){
			// インクリメントされた値を取得
			$sql = "SELECT SCOPE_IDENTITY() AS pKey";
			$id = $this->getRecord($sql);

			return $id["pKey"];
		}

		return false;
	}

	// 更新（UtilのbuildQuery使いたい場合用）
	public function update($params, $conditions)
	{
		$params = DaoUtil::setUpdateInfo($params);
		$query = DaoUtil::buildUpdateQuery($this->table, $params, $conditions);

		return $this->execute($query);
	}
}
