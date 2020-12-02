<?php
class DaoUtil
{
	public function buildSelectQuery($params)
	{
	}

	public function buildInsertQuery($table, $params)
	{
		$query = "INSERT INTO {$table} ( ";
		$cols = "";
		$vals = "";

		self::escape($params);

		foreach($params as $k => $v){
			if(!empty($v)){
				$cols .= $k . ", ";
				$vals .= $v . "','";
			}
		}

		$cols = preg_replace("/, $/", "", $cols);
		$vals = preg_replace("/','$/", "", $vals);

		return $query . $cols . " ) VALUES ('" . $vals . "')";
	}

	public function buildUpdateQuery($table, $params, $conditions)
	{
		$query = "UPDATE {$table} SET ";
		$vals = "";
		$where = "";

		self::escape($params);

		foreach($params as $k => $v){

			$val = ($v === "") ? "null" : "'".$v."'";
			$vals .= $k . " = " . $val . ",";
		}

		$vals = preg_replace("/,$/", "", $vals);

		foreach($conditions as $k => $v){
			$connection = empty($where) ? " WHERE " : " AND ";
			$where .= $connection . $k . " = '" . $v . "'";
		}

		return $query . $vals . $where;
	}

	public function buildDeleteQuery($params)
	{
	}

	public function setInsertInfo($params)
	{
		$params["CreatedUserCode"] = "system";
		$params["CreatedDatetime"] = date("Y/m/d H:i:s");
		$params["UpdatedUserCode"] = "system";
		$params["UpdatedDatetime"] = date("Y/m/d H:i:s");

		return $params;
	}

	public function setUpdateInfo($params)
	{
		$params["UpdatedUserCode"] = "system";
		$params["UpdatedDatetime"] = date("Y/m/d H:i:s");

		return $params;
	}

	public function escape(& $params)
	{
		if(is_array($params)){
			foreach($params as &$v){
				$v = self::escape($v);
			}
		}

		if(is_string($params)){
			return self::ms_escape_string($params);
		}

		return $params;
	}

	public function ms_escape_string($data)
	{
		if ( !isset($data) or empty($data) ){
			 return '';
		}

		if ( is_numeric($data) ){
			return $data;
		}

		$non_displayables = array(
			'/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
			'/%1[0-9a-f]/',             // url encoded 16-31
			'/[\x00-\x08]/',            // 00-08
			'/\x0b/',                   // 11
			'/\x0c/',                   // 12
			'/[\x0e-\x1f]/'             // 14-31
		);

		foreach ( $non_displayables as $regex ){
			$data = preg_replace( $regex, '', $data );
		}

		$data = str_replace("'", "''", $data );

		return $data;
	}
}
