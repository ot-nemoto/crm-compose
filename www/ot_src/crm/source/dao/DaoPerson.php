<?php
class DaoPerson extends DaoBase
{
	public $table = "Person";

	public function search($param)
	{
		$cid = $param["cid"];
		$sid = $param["sid"];
		$order = $param["order"];

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

		if(!empty($order)){
			$sql = $sql . "order by " . $order;
		}

		return $this->fetch($sql);
	}

	public function searchList($param, $page=1)
	{
		$name = CommonUtil::convertSearchValue($param["name"]);
		$order = empty($param["order"]) ? "PersonID" : $param["order"];

		$start = (($page-1) * PERSON_LIST_MAX_COUNT) + 1;
		$end = $page * PERSON_LIST_MAX_COUNT;

		$where = "WHERE p.DeleteFlg <> 1 ";

		if(!empty($name)){
			$where .= "AND SearchName like '%{$name}%' ";
		}

		// クエリー文を指定 
		$sql	="SELECT  "
				."    p.* "
				."    , c.CompanyName "
				."    , c.ZipCode AS CompanyZipCode "
				."    , c.Address1 AS CompanyAddress1 "
				."    , c.Address2 AS CompanyAddress2 "
				."    , c.Address3 AS CompanyAddress3 "
				."    , c.TelephoneNumber1 AS CompanyTelephoneNumber1 "
				."    , c.TelephoneNumber2 AS CompanyTelephoneNumber2 "
				."    , c.FaxNumber1 AS CompanyFaxNumber1 "
				."    , c.MailAddress1 AS CompanyMailAddress1 "
				."    , s.SectionName "
				."    , s.ZipCode AS SectionZipCode "
				."    , s.Address1 AS SectionAddress1 "
				."    , s.Address2 AS SectionAddress2 "
				."    , s.Address3 AS SectionAddress3 "
				."    , s.TelephoneNumber1 AS SectionTelephoneNumber1 "
				."    , s.TelephoneNumber2 AS SectionTelephoneNumber2 "
				."    , s.FaxNumber1 AS SectionFaxNumber1 "
				."    , s.MailAddress1 AS SectionMailAddress1 "
				."FROM  "
				."    (SELECT ROW_NUMBER() OVER(ORDER BY {$order}) AS RowNum, * FROM {$this->table} p {$where} ) AS p "
				."LEFT OUTER JOIN  "
				."    Company c  "
				."ON  "
				."    p.CompanyID = c.CompanyID "
				."AND c.DeleteFlg <> 1 "
				."LEFT OUTER JOIN  "
				."    Section s  "
				."ON  "
				."    p.SectionID = s.SectionID "
				."AND s.DeleteFlg <> 1 ";

		$where .= "AND p.RowNum BETWEEN {$start} AND {$end} ";

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
		$sql	="SELECT COUNT(PersonID) AS count FROM {$this->table} ";

		$where = "WHERE DeleteFlg <> 1 ";

		if(!empty($name)){
			$where .= "AND SearchName like '%{$name}%' ";
		}

		$sql = $sql . $where;

		$ret = $this->getRecord($sql);

		return isset($ret["count"]) ? $ret["count"] : 0;
	}

	public function get($param)
	{
		$key = $param["pid"];
		$where = "";

		// クエリー文を指定 
		$sql	="SELECT "
				."    * "
				."FROM "
				."    {$this->table} ";

		$where = "WHERE DeleteFlg <> 1 ";

		if(!empty($key)){
			$where .= "AND PersonID = '{$key}' ";
		}

		$sql = $sql . $where;

		return $this->getRecord($sql);
	}
}
