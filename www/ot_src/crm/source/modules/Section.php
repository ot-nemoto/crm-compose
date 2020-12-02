<?php
class Section extends SiteBase
{
	public function transact()
	{
		$mode = empty($_GET["mode"]) ? "" : $_GET["mode"];

		// 使用するテンプレートを指定
		if($mode == "mod"){
			$this->tplFile = "RegistSection.html";
		}else{
			$this->tplFile = "DetailSection.html";
		}

		// 部署IDが渡されていない場合、処理終了
		if(empty($_GET["sid"])){
			return;
		}

		// 部署情報初期化
		$blSection = new BlSection($_GET["sid"]);

		// 会社情報初期化
		$blCompany = new BlCompany($blSection->getCompanyId());

		$this->tplh['companyInfo']	= $blCompany->getCompanyInfo();
		$this->tplh['sectionInfo']	= $blSection->getSectionInfo();
		$this->tplh['personList']	= $blSection->getPersonList();
	}
}
