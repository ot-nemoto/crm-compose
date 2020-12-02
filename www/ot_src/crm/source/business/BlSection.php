<?php
class BlSection
{
	private $daoSection = null;
	private $daoPerson = null;

	private $sectionInfo = null;
	private $personList = null;

	private $sectionId = null;
	private $companyId = null;

	/**
	* 初期処理を行います。
	* 
	* @param string $pid 部署ID
	*/
	public function __construct($sid = null)
	{
		$this->daoSection = new DaoSection();

		if(!empty($sid)){
			$this->sectionId = $sid;
		}
	}

	/**
	* 所属する会社IDを取得します。
	* 
	* @return string 会社ID
	*/
	public function getCompanyId()
	{
		if(empty($this->companyId)){
			$section = $this->getSectionInfo();
			$this->companyId = empty($section["CompanyID"]) ? "" : $section["CompanyID"];
		}

		return $this->companyId;
	}

	/**
	* 部署の詳細情報を取得します。
	* 
	* @return array 担当者情報の連想配列
	*/
	public function getSectionInfo()
	{
		if(empty($this->sectionInfo)){
			$this->sectionInfo = $this->getSectionDetail();
		}

		return $this->sectionInfo;
	}


	public function getSectionListForSearch()
	{
	}

	/**
	* 部署に配属されている担当者の一覧情報を取得します。
	* 
	* @return array 担当者一覧の連想配列
	*/
	public function getPersonList()
	{
		if(empty($this->personList)){

			$cid = $this->getCompanyId();

			if(empty($this->sectionId) || empty($cid)){
				return null;
			}

			$param = array();
			$param["cid"] = $cid;
			$param["sid"] = $this->sectionId;

			$this->personList = $this->getDaoPerson()->search($param);
		}

		return $this->personList;
	}

	private function getSectionDetail()
	{
		if(empty($this->sectionId)){
			return null;
		}

		$param = array();
		$param["sid"] = $this->sectionId;

		return $this->daoSection->get($param);
	}

	private function getDaoPerson()
	{
		if(empty($this->daoPerson)){
			$this->daoPerson = new DaoPerson();
		}

		return $this->daoPerson;
	}
}
