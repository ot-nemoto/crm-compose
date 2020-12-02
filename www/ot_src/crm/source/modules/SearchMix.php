<?php
class SearchMix extends SiteBase
{
	public function transact()
	{
		$param = array();

		// ページ設定
		$cPage = isset($_GET["cPage"]) ? $_GET["cPage"] : 1;
		$sPage = isset($_GET["sPage"]) ? $_GET["sPage"] : 1;
		$pPage = isset($_GET["pPage"]) ? $_GET["pPage"] : 1;

		$param["name"] = empty($_GET["txtSearchContactName"]) ? "" : $_GET["txtSearchContactName"];

		$daoCompany = new DaoCompany();
		$daoSection = new DaoSection();
		$daoPerson = new DaoPerson();

		$companyCount = $daoCompany->getCount($param);
		$companyList = $daoCompany->search($param, $cPage);
		$sectionCount = $daoSection->getCount($param);
		$sectionList = $daoSection->searchList($param, $sPage);
		$personCount = $daoPerson->getCount($param);
		$personList = $daoPerson->searchList($param, $pPage);

		$this->tplh['companyCount'] = $companyCount;
		$this->tplh['sectionCount'] = $sectionCount;
		$this->tplh['personCount'] = $personCount;
		$this->tplh['companyList'] = $companyList;
		$this->tplh['sectionList'] = $sectionList;
		$this->tplh['personList'] = $personList;
	}
}
