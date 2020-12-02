<?php
class SearchPerson extends SiteBase
{
	public function transact()
	{
		// 並び順設定
		$sort = "UpdatedDatetime desc";

		// ページ設定
		$pPage = isset($_GET["pPage"]) ? $_GET["pPage"] : 1;

		if(!empty($_GET["sort"]) && $_GET["sort"] == "name"){
			$sort = "Name";
		}

		$param = array();
	
		$param["name"] = empty($_GET["txtSearchName"]) ? "" : $_GET["txtSearchName"];
		$param["order"] = $sort;

		$daoPerson = new DaoPerson();
		$personCount = $daoPerson->getCount($param);
		$personList = $daoPerson->searchList($param, $pPage);

		$this->tplh['personCount'] = $personCount;
		$this->tplh['personList'] = $personList;
	}
}
