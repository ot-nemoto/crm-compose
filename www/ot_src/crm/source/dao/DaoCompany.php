<?php
class DaoCompany extends DaoBase
{
	public $table = "Company";

	public function search($param, $page=1)
	{
		$name = CommonUtil::convertSearchValue($param["name"]);
		$order = empty($param["order"]) ? "CompanyID" : $param["order"];
		$nameIdx = $param["nameIdx"];

		$start = (($page-1) * COMPANY_LIST_MAX_COUNT) + 1;
		$end = $page * COMPANY_LIST_MAX_COUNT;

		$where  = "WHERE DeleteFlg <> 1 ";

		if(!empty($name)){
			$where .= " AND ( SearchCompanyName like '%{$name}%' OR ShortCompanyName like '{$name}%' ) ";
		}

		if(is_numeric($nameIdx) ){
			$where .= " AND IndexCompanyName = {$nameIdx} ";
		}

		// クエリー文を指定 
		$sql	="SELECT "
				."    * "
				."FROM "
				."    (SELECT ROW_NUMBER() OVER(ORDER BY {$order}) AS RowNum, * FROM {$this->table} {$where} ) AS c ";

		$where .= "AND c.RowNum BETWEEN {$start} AND {$end} ";
		$sql = $sql . $where;


		if(!empty($order)){
			$sql = $sql . "order by " . $order;
		}

		return $this->fetch($sql);
	}

	public function getCount($param)
	{
		$name = CommonUtil::convertSearchValue($param["name"]);
		$order = $param["order"];
		$nameIdx = $param["nameIdx"];

		// クエリー文を指定 
		$sql	="SELECT COUNT(CompanyID) AS count FROM {$this->table} ";

		$where = "WHERE DeleteFlg <> 1 ";

		if(!empty($name)){
			$where .= " AND ( SearchCompanyName like '%{$name}%' OR ShortCompanyName like '{$name}%' ) ";
		}

		if(is_numeric($nameIdx) ){
			$where .= " AND IndexCompanyName = {$nameIdx} ";
		}

		$sql = $sql . $where;

		$ret = $this->getRecord($sql);

		return isset($ret["count"]) ? $ret["count"] : 0;
	}

	public function get($param)
	{
		$key = $param["cid"];
		$where = "";

		// クエリー文を指定 
		$sql	="SELECT "
				."    * "
				."FROM "
				."    {$this->table} ";

		$where = "WHERE DeleteFlg <> 1 ";

		if(!empty($key)){
			$where .= "AND CompanyID = '{$key}' ";
		}

		$sql = $sql . $where;

		return $this->getRecord($sql);
	}
}
