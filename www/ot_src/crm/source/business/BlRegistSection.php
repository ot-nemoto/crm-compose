<?php
class BlRegistSection
{
	private $sectionId = null;
	private $campanyId = null;
	private $daoSection = null;
	private $isNewFlg = null;

	/**
	* 初期処理を行います。
	* 
	* @param string $sid 部署ID
	* @param string $cid 会社ID
	*/
	public function __construct($sid, $cid=null)
	{
		$this->daoSection = new DaoSection();
		$this->isNewFlg = true;
		$this->campanyId = $cid;

		if(!empty($sid)){
			$this->sectionId = $sid;
			$this->isNewFlg = false;
		}
	}

	/**
	* 所属する部署IDを取得します。
	* 
	* @return string 部署ID
	*/
	public function getSectionId()
	{
		return $this->sectionId;
	}

	/**
	* 会社IDを設定します。
	* 
	* @return string 会社ID
	*/
	public function setCampanyId($cid)
	{
		$this->campanyId = $cid;
	}

	/**
	* 所属する部署IDを取得します。
	* 
	* @return string 部署ID
	*/
	public function isNew()
	{
		return $this->isNewFlg;
	}

	/**
	* 部署情報の入力チェックします。
	* 
	* @param string $errorList エラーリスト
	* @return array エラー内容
	*/
	public function checkInput(& $errorList, $requireFlg = false)
	{
		if($requireFlg){
			// 部署名が未入力で、他の何かしらの項目に入力がある場合
			if( empty($_POST["txtSectionName"]) ){
				// 部署名は入力必須
				$errorList[] = '1002';
			}
			// 会社IDがない and 会社名が入力されていない場合
			else if ( empty($this->campanyId) && empty($_POST["txtCompanyName"]) ){
				// 部署を登録するには会社情報が必須
				$errorList[] = '1004';
			}
		}else{
			// 部署名が未入力で、他の何かしらの項目に入力がある場合
			if( empty($_POST["txtSectionName"]) &&
				!(	empty($_POST["txtSectionZipCode1"]) &&
					empty($_POST["txtSectionZipCode2"]) &&
					empty($_POST["txtSectionAddress1"]) &&
					empty($_POST["txtSectionAddress2"]) &&
					empty($_POST["txtSectionAddress3"]) &&
					empty($_POST["txtSectionTelephoneNumber1"]) &&
					empty($_POST["txtSectionTelephoneNumber2"]) &&
					empty($_POST["txtSectionFaxNumber1"]) &&
					empty($_POST["txtSectionMailAddress1"]) &&
					empty($_POST["txtSectionNote"]) ) ){

				// 部署名は入力必須
				$errorList[] = '1002';
			}
			// 部署名が入力されていて、会社IDがない and 会社名が入力されていない場合
			else if (!empty($_POST["txtSectionName"]) && 
						( empty($this->campanyId) && empty($_POST["txtCompanyName"]) ) ){
				// 部署を登録するには会社情報が必須
				$errorList[] = '1004';
			}
		}
	}

	/**
	* 部署情報を登録します。
	* 
	* @return bool 登録結果
	*/
	public function regist()
	{
		// クエリ実行
		$this->sectionId = $this->daoSection->insert($this->setParams());

		if(empty($this->sectionId)){
			return false;
		}

		return true;
	}

	/**
	* 部署情報を更新します。
	* 
	* @return bool 登録結果
	*/
	public function modify()
	{
		if(empty($this->sectionId)){
			return false;
		}

		// 更新対象を設定
		$conditions = array();
		$conditions["SectionID"] = $this->sectionId;

		// クエリ実行
		return $this->daoSection->update($this->setParams(), $conditions);
	}

	/**
	* 部署情報を論理削除します。
	* 
	* @return bool 登録結果
	*/
	public function delete()
	{
		if(empty($this->sectionId)){
			return false;
		}

		// 更新対象を設定
		$params = array();
		$params["DeleteFlg"] = 1;

		$conditions = array();
		$conditions["SectionID"] = $this->sectionId;

		// クエリ実行
		return $this->daoSection->update($params, $conditions);
	}

	/**
	* 送信された部署情報を設定します。
	* 
	* @return bool 登録結果
	*/
	public function getSectionInfo()
	{
		$info = array();
		$info["CompanyID"] = $this->campanyId;
		$info["SectionID"] = $this->sectionId;
		$info["SectionName"] = $_POST["txtSectionName"];
		$info["ZipCode"] = $_POST["txtSectionZipCode1"];
		if(!empty($_POST["txtSectionZipCode2"])){
			$info["ZipCode"] .= "-" . $_POST["txtSectionZipCode2"];
		}
		if($_POST["hdnSectionAddressInputType"] == "text"){
			$info["Address1"] = $_POST["txtSectionAddress1"];
		}else{
			$info["Address1"] = CommonUtil::getIniValue('prefecture', $_POST["cmbSectionAddress1"]);
		}

		$info["Address2"] = $_POST["txtSectionAddress2"];
		$info["Address3"] = $_POST["txtSectionAddress3"];
		$info["TelephoneNumber1"] = $_POST["txtSectionTelephoneNumber1"];
		$info["TelephoneNumber2"] = $_POST["txtSectionTelephoneNumber2"];
		$info["FaxNumber1"] = $_POST["txtSectionFaxNumber1"];
		$info["MailAddress1"] = $_POST["txtSectionMailAddress1"];
		$info["Note"] = $_POST["txtSectionNote"];

		return $info;
	}

	private function setParams()
	{
		$params = array();
		$params["CompanyID"] = $this->campanyId;
		$params["SectionName"] = $_POST["txtSectionName"];
		$params["SearchSectionName"] = CommonUtil::convertSearchValue($_POST["txtSectionName"]);
		$params["ZipCode"] = CommonUtil::convertNumberValue($_POST["txtSectionZipCode1"]);
		if(!empty($_POST["txtSectionZipCode2"])){
			$params["ZipCode"] .= "-" . CommonUtil::convertNumberValue($_POST["txtSectionZipCode2"]);
		}
		if($_POST["hdnSectionAddressInputType"] == "text"){
			$params["Address1"] = CommonUtil::convertNumberValue($_POST["txtSectionAddress1"]);
		}else{
			$params["Address1"] = CommonUtil::getIniValue('prefecture', $_POST["cmbSectionAddress1"]);
		}

		$params["Address2"] = CommonUtil::convertNumberValue($_POST["txtSectionAddress2"]);
		$params["Address3"] = CommonUtil::convertNumberValue($_POST["txtSectionAddress3"]);
		$params["TelephoneNumber1"] = CommonUtil::convertNumberValue($_POST["txtSectionTelephoneNumber1"]);
		$params["TelephoneNumber2"] = CommonUtil::convertNumberValue($_POST["txtSectionTelephoneNumber2"]);
		$params["FaxNumber1"] = CommonUtil::convertNumberValue($_POST["txtSectionFaxNumber1"]);
		$params["MailAddress1"] = CommonUtil::convertNumberValue($_POST["txtSectionMailAddress1"]);
		$params["Note"] = $_POST["txtSectionNote"];

		return $params;
	}
}
