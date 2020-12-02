<?php
class Person extends SiteBase
{
	public function transact()
	{
		$mode			= empty($_GET["mode"]) ? "" : $_GET["mode"];
		$cmbSectionList	= array("" => '部署を新規登録する');

		// 使用するテンプレートを指定
		if($mode == "mod"){
			$this->tplFile = "RegistPerson.html";
		}else{
			$this->tplFile = "DetailPerson.html";
		}

		// 個人IDも会社IDが渡されていない場合、処理終了（新規登録モード）
		if(empty($_GET["pid"]) && empty($_GET["cid"])){
			$this->tplh['cmbSectionList']	= $cmbSectionList;

			return;
		}

		// 個人ID取得
		$pid = empty($_GET["pid"]) ? "" : $_GET["pid"];

		// 担当者情報初期化
		$blPerson = new BlPerson($pid);

		// 部署情報初期化
		if(isset($_GET["cid"])){
			$blSection = new BlSection();
		}else{
			$blSection = new BlSection($blPerson->getSectionId());
		}

		// 会社ID取得
		// (個人情報の会社IDよりもGET値の会社IDを優先する)
		$cid = isset($_GET["cid"]) ? $_GET["cid"] : $blPerson->getCompanyId();

		// 会社情報初期化
		$blCompany = new BlCompany($cid);

		$this->tplh['personInfo']		= $blPerson->getPersonInfo();		// 担当者情報
		$this->tplh['companyInfo']		= $blCompany->getCompanyInfo();		// 会社情報
		$this->tplh['sectionInfo']		= $blSection->getSectionInfo();		// 部署情報
		$this->tplh['sectionList']		= $blCompany->getSectionList();		// 部署一覧
		$this->tplh['cmbSectionList']	= $blCompany->getSectionListForComboBox();	// 部署一覧
	}
}
