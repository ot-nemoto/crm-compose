<?php
class DaoSection extends DaoBase
{
	public $table = "Section";

	public function search($param)
	{
		$cid = $param["cid"];
		$sid = $param["sid"];
		$where = "";

		// クエリー文を指定 
		$sql	="SELECT "
				."    * "
				."FROM "
				."    {$this->table} ";

		$where = "WHERE DeleteFlg <> 1 ";

		if(!empty($cid)){
			$where .= "AND CompanyID = '{$cid}' ";
		}

		if(!empty($sid)){
			$where .= "AND SectionID = '{$sid}' ";
		}

		$sql = $sql . $where;

		return $this->fetch($sql);
	}

	public function searchList($param, $page=1)
	{
		$name =CommonUtil::convertSearchValue($param["name"]);
		$order = empty($param["order"]) ? "SectionID" : $param["order"];
		$where = "";

		$start = (($page-1) * SECTION_LIST_MAX_COUNT) + 1;
		$end = $page * SECTION_LIST_MAX_COUNT;

		$where = "WHERE s.DeleteFlg <> 1 ";

		if(!empty($name)){
			$where .= "AND SearchSectionName like '%{$name}%' ";
		}

		// クエリー文を指定 
		$sql	="SELECT "
				."    s.* "
				."    , c.CompanyName "
				."    , c.ZipCode AS CompanyZipCode "
				."    , c.Address1 AS CompanyAddress1 "
				."    , c.Address2 AS CompanyAddress2 "
				."    , c.Address3 AS CompanyAddress3 "
				."    , c.TelephoneNumber1 AS CompanyTelephoneNumber1 "
				."    , c.TelephoneNumber2 AS CompanyTelephoneNumber2 "
				."    , c.FaxNumber1 AS CompanyFaxNumber1 "
				."    , c.MailAddress1 AS CompanyMailAddress1 "
				."FROM "
				."    (SELECT ROW_NUMBER() OVER(ORDER BY {$order}) AS RowNum, * FROM {$this->table} s {$where} ) AS s "
				."LEFT OUTER JOIN  "
				."    Company c  "
				."ON  "
				."    s.CompanyID = c.CompanyID "
				."AND c.DeleteFlg <> 1 ";

		$where .= "AND s.RowNum BETWEEN {$start} AND {$end} ";

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

		// クエリー文を指定 
		$sql	="SELECT COUNT(SectionID) AS count FROM {$this->table} ";

		$where = "WHERE DeleteFlg <> 1 ";

		if(!empty($name)){
			$where .= "AND SearchSectionName like '%{$name}%' ";
		}

		$sql = $sql . $where;

		$ret = $this->getRecord($sql);

		return isset($ret["count"]) ? $ret["count"] : 0;
	}

	public function get($param)
	{
		$key = $param["sid"];
		$where = "";

		// クエリー文を指定 
		$sql	="SELECT "
				."    * "
				."FROM "
				."    {$this->table} ";

		$where = "WHERE DeleteFlg <> 1 ";

		if(!empty($key)){
			$where .= "AND SectionID = '{$key}' ";
		}

		$sql = $sql . $where;

		return $this->getRecord($sql);
	}
}
