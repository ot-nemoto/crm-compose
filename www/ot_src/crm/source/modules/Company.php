<?php
class Company extends SiteBase
{
	public function transact()
	{
		$mode = empty($_GET["mode"]) ? "" : $_GET["mode"];

		// 使用するテンプレートを指定
		if($mode == "mod"){
			$this->tplFile = "RegistCompany.html";
		}else{
			$this->tplFile = "DetailCompany.html";
		}

		// 会社IDが渡されていない場合、処理終了
		if(empty($_GET["cid"])){
			return;
		}

		// 会社情報初期化
		$blCompany = new BlCompany($_GET["cid"]);

		$this->tplh['companyInfo']	= $blCompany->getCompanyInfo();
		$this->tplh['sectionList']	= $blCompany->getSectionList();
		$this->tplh['personList']	= $blCompany->getPersonList();
	}
}
