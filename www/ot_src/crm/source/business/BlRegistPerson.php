<?php
class BlRegistPerson
{
	private $personId = null;
	private $campanyId = null;
	private $sectionId = null;
	private $daoPerson = null;
	private $isNewFlg = null;

	/**
	* 初期処理を行います。
	* 
	* @param string $pid 担当者ID
	* @param string $cid 会社ID
	* @param string $sid 部署ID
	*/
	public function __construct($pid, $cid=null, $sid=null)
	{
		$this->daoPerson = new DaoPerson();
		$this->isNewFlg = true;
		$this->campanyId = $cid;
		$this->sectionId = $sid;

		if(!empty($pid)){
			$this->personId = $pid;
			$this->isNewFlg = false;
		}
	}

	/**
	* 担当者IDを取得します。
	* 
	* @return string 担当者ID
	*/
	public function getPersonId()
	{
		return $this->personId;
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
	* 部署IDを設定します。
	* 
	* @return string 部署ID
	*/
	public function setSectionId($sid)
	{
		$this->sectionId = $sid;
	}

	/**
	* 所属する担当者IDを取得します。
	* 
	* @return string 担当者ID
	*/
	public function isNew()
	{
		return $this->isNewFlg;
	}

	/**
	* 担当者情報の入力チェックします。
	* 
	* @param string $errorList エラーリスト
	* @return array エラー内容
	*/
	public function checkInput(& $errorList, $requireFlg = false)
	{
		if($requireFlg){
			// 担当者名が未入力で、他の何かしらの項目に入力がある場合
			if(empty($_POST["txtPersonName"])){
				// 担当者名は入力必須
				$errorList[] = '1003';
			}
		}else{
			// 担当者名が未入力で、他の何かしらの項目に入力がある場合
			if( empty($_POST["txtPersonName"]) &&
				!(	empty($_POST["txtPersonPersonRoleName"]) &&
					empty($_POST["txtPersonRetireDate"]) &&
					empty($_POST["txtPersonZipCode1"]) &&
					empty($_POST["txtPersonZipCode2"]) &&
					empty($_POST["txtPersonAddress1"]) &&
					empty($_POST["txtPersonAddress2"]) &&
					empty($_POST["txtPersonAddress3"]) &&
					empty($_POST["txtPersonTelephoneNumber1"]) &&
					empty($_POST["txtPersonTelephoneNumber2"]) &&
					empty($_POST["txtPersonFaxNumber1"]) &&
					empty($_POST["txtPersonMailAddress1"]) &&
					empty($_POST["txtPersonNote"]) ) ){

				// 担当者名は入力必須
				$errorList[] = '1003';
			}
		}

		// 日付の妥当性チェック
		if( !empty($_POST["txtPersonRetireDate"]) ){

			$date  = date_parse($_POST["txtPersonRetireDate"]);

			// 日付変換が出来たか判定
			if( !date_create_from_format('Y/m/d', $_POST["txtPersonRetireDate"]) ) {
				$errorList[] = '1005';
			}
			else if ( !checkdate($date["month"], $date["day"], $date["year"]) ) 
			{ 
				// 日付の妥当性判定その２
				$errorList[] = '1006';
			}
		}
	}

	/**
	* 担当者情報を登録します。
	* 
	* @return bool 登録結果
	*/
	public function regist()
	{
		// クエリ実行
		$this->personId = $this->daoPerson->insert($this->setParams());
		
		if(empty($this->personId)){
			return false;
		}

		return true;
	}

	/**
	* 担当者情報を更新します。
	* 
	* @return bool 登録結果
	*/
	public function modify()
	{
		if(empty($this->personId)){
			return false;
		}

		// 更新対象を設定
		$conditions = array();
		$conditions["PersonID"] = $this->personId;

		// クエリ実行
		return $this->daoPerson->update($this->setParams(), $conditions);
	}

	/**
	* 担当者情報を論理削除します。
	* 
	* @return bool 登録結果
	*/
	public function delete()
	{
		if(empty($this->personId)){
			return false;
		}

		// 更新対象を設定
		$params = array();
		$params["DeleteFlg"] = 1;

		$conditions = array();
		$conditions["PersonID"] = $this->personId;

		// クエリ実行
		return $this->daoPerson->update($params, $conditions);
	}

	/**
	* 送信された担当者情報を設定します。
	* 
	* @return bool 登録結果
	*/
	public function getPersonInfo()
	{
		$info = array();
		$info["CompanyID"] = $this->campanyId;
		$info["SectionID"] = $this->sectionId;
		$info["PersonID"] = $this->personId;
		$info["PersonRoleName"] = $_POST["txtPersonPersonRoleName"];
		$info["Name"] = $_POST["txtPersonName"];
		$info["ZipCode"] = $_POST["txtPersonZipCode1"];
		if(!empty($_POST["txtPersonZipCode2"])){
			$info["ZipCode"] .= "-" . $_POST["txtPersonZipCode2"];
		}
		if($_POST["hdnPersonAddressInputType"] == "text"){
			$info["Address1"] = $_POST["txtPersonAddress1"];
		}else{
			$info["Address1"] = CommonUtil::getIniValue('prefecture', $_POST["cmbPersonAddress1"]);
		}
		$info["Address2"] = $_POST["txtPersonAddress2"];
		$info["Address3"] = $_POST["txtPersonAddress3"];
		$info["TelephoneNumber1"] = $_POST["txtPersonTelephoneNumber1"];
		$info["TelephoneNumber2"] = $_POST["txtPersonTelephoneNumber2"];
		$info["FaxNumber1"] = $_POST["txtPersonFaxNumber1"];
		$info["MailAddress1"] = $_POST["txtPersonMailAddress1"];
		$info["Note"] = $_POST["txtPersonNote"];
		$info["RetireFlag"] = $_POST["chkPersonRetireFlag"];
		$info["RetireDate"] = $_POST["txtPersonRetireDate"];

		return $info;
	}

	private function setParams()
	{
		$params = array();
		$params["CompanyID"] = $this->campanyId;
		$params["SectionID"] = $this->sectionId;
		$params["Name"] = $_POST["txtPersonName"];
		$params["PersonRoleName"] = $_POST["txtPersonPersonRoleName"];
		$params["SearchName"] = CommonUtil::convertSearchValue($_POST["txtPersonName"]);
		$params["ZipCode"] = CommonUtil::convertNumberValue($_POST["txtPersonZipCode1"]);
		if(!empty($_POST["txtPersonZipCode2"])){
			$params["ZipCode"] .= "-" . CommonUtil::convertNumberValue($_POST["txtPersonZipCode2"]);
		}
		if($_POST["hdnPersonAddressInputType"] == "text"){
			$params["Address1"] = CommonUtil::convertNumberValue($_POST["txtPersonAddress1"]);
		}else{
			$params["Address1"] = CommonUtil::getIniValue('prefecture', $_POST["cmbPersonAddress1"]);
		}
		$params["Address2"] = CommonUtil::convertNumberValue($_POST["txtPersonAddress2"]);
		$params["Address3"] = CommonUtil::convertNumberValue($_POST["txtPersonAddress3"]);
		$params["TelephoneNumber1"] = CommonUtil::convertNumberValue($_POST["txtPersonTelephoneNumber1"]);
		$params["TelephoneNumber2"] = CommonUtil::convertNumberValue($_POST["txtPersonTelephoneNumber2"]);
		$params["FaxNumber1"] = CommonUtil::convertNumberValue($_POST["txtPersonFaxNumber1"]);
		$params["MailAddress1"] = CommonUtil::convertNumberValue($_POST["txtPersonMailAddress1"]);
		$params["Note"] = $_POST["txtPersonNote"];
		$params["RetireFlag"] = $_POST["chkPersonRetireFlag"] ? 1 : 0;
		$params["RetireDate"] = $_POST["txtPersonRetireDate"];

		return $params;
	}
}
