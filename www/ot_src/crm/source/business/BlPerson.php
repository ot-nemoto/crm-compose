<?php
class BlPerson
{
	private $daoPerson = null;
	private $personInfo = null;

	private $personId = null;
	private $companyId = null;
	private $sectionId = null;

	/**
	* 初期処理を行います。
	* 
	* @param string $pid 担当者ID
	*/
	public function __construct($pid = null)
	{
		$this->daoPerson = new DaoPerson();

		if(!empty($pid)){
			$this->personId = $pid;
		}
	}

	public function getPersonId()
	{
	}

	/**
	* 担当者の所属する会社IDを取得します。
	* 
	* @return string 会社ID
	*/
	public function getCompanyId()
	{
		if(empty($this->personInfo)){
			$this->getPersonInfo();
		}

		return isset($this->personInfo["CompanyID"]) ? $this->personInfo["CompanyID"] : "";
	}

	/**
	* 担当者の所属する部署IDを取得します。
	* 
	* @return string 部署ID
	*/
	public function getSectionId()
	{
		if(empty($this->personInfo)){
			$this->getPersonInfo();
		}

		return isset($this->personInfo["SectionID"]) ? $this->personInfo["SectionID"] : "";
	}

	/**
	* 担当者の詳細情報を取得します。
	* 
	* @return array 担当者情報の連想配列
	*/
	public function getPersonInfo()
	{
		if(empty($this->personInfo)){
			$this->personInfo = $this->getPersonDetail();
		}

		return $this->personInfo;
	}

	public function getPersonListForSearch()
	{
	}

	private function getPersonDetail()
	{
		if(empty($this->personId)){
			return null;
		}

		$param = array();
		$param["pid"] = $this->personId;

		return $this->daoPerson->get($param);
	}
}
