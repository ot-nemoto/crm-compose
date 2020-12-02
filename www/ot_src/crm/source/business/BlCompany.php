<?php
class BlCompany
{
	private $daoCompany = null;
	private $daoSection = null;
	private $daoPerson = null;

	private $companyInfo = null;
	private $sectionList = null;

	private $companyId = null;

	/**
	* 初期処理を行います。
	* 
	* @param string $cid 会社ID
	*/
	public function __construct($cid = null)
	{
		$this->daoCompany = new DaoCompany();

		if(!empty($cid)){
			$this->companyId = $cid;
		}
	}

	/**
	* 会社の詳細情報を取得します。
	* 
	* @return array 会社情報の連想配列
	*/
	public function getCompanyInfo()
	{
		if(empty($this->companyInfo)){
			$this->companyInfo = $this->getCompanyDetail();
		}

		return $this->companyInfo;
	}

	public function getCompanyListForSearch()
	{
	}

	/**
	* 会社に登録されている部署の一覧情報を取得します。
	* 
	* @return array 部署一覧の連想配列
	*/
	public function getSectionList()
	{
		if(empty($this->sectionList)){
			if(empty($this->companyId)){
				return null;
			}

			$param = array();
			$param["cid"] = $this->companyId;

			// 部署一覧情報取得
			$this->sectionList = $this->getDaoSection()->search($param);
		}

		return $this->sectionList;
	}

	/**
	* 会社の担当者一覧情報を取得します。
	* 
	* @return array 担当者一覧の連想配列
	*/
	public function getPersonList()
	{
		if(empty($this->personList)){
			if(empty($this->companyId)){
				return null;
			}

			$param = array();
			$param["cid"] = $this->companyId;

			$this->personList = $this->getDaoPerson()->search($param);
		}

		return $this->personList;
	}

	/**
	* 会社の部署一覧（プルダウン用）情報を取得します。
	* 
	* @return array 担当者一覧の連想配列
	*/
	public function getSectionListForComboBox()
	{
		$cmbSectionList	= array("" => '部署を新規登録する');

		// プルダウン用部署一覧
		$sList = array();

		// 部署一覧が取得できない場合、処理終了
		if(!is_array($this->getSectionList())){
			return $cmbSectionList;
		}

		foreach($this->getSectionList() as $section){
			$workId = $section['SectionID'];
			$sList[$workId] = $section['SectionName'];
		}

		// 上で定義した初期値をマージ。
		$cmbSectionList = $cmbSectionList + $sList;

		return $cmbSectionList;
	}

	private function getCompanyDetail()
	{
		if(empty($this->companyId)){
			return null;
		}

		$param = array();
		$param["cid"] = $this->companyId;

		return $this->daoCompany->get($param);
	}

	private function getDaoSection()
	{
		if(empty($this->daoSection)){
			$this->daoSection = new DaoSection();
		}

		return $this->daoSection;
	}

	private function getDaoPerson()
	{
		if(empty($this->daoPerson)){
			$this->daoPerson = new DaoPerson();
		}

		return $this->daoPerson;
	}
}
