<?php
class SearchCompany extends SiteBase
{
	public function transact()
	{
		// 並び順設定
		$sort = "UpdatedDatetime desc";

		// ページ設定
		$cPage = isset($_GET["cPage"]) ? $_GET["cPage"] : 1;

		if(!empty($_GET["sort"]) && $_GET["sort"] == "name"){
			$sort = "CompanyName";
		}

		$param = array();
	
		$param["name"] = empty($_GET["txtSearchName"]) ? "" : $_GET["txtSearchName"];
		$param["order"] = $sort;
		$param["nameIdx"] = isset($_GET["idx"]) ? $_GET["idx"] : "";

		$daoCompany = new DaoCompany();
		$companyCount = $daoCompany->getCount($param);
		$companyList = $daoCompany->search($param, $cPage);

		$this->tplh['companyList'] = $companyList;
		$this->tplh['companyCount'] = $companyCount;
		$this->tplh['companyIndexList'] = parse_ini_file(PROPATY_DIR . 'company_index.ini');;
	}
}
