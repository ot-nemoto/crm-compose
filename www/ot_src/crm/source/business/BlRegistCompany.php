<?php
class BlRegistCompany
{
	private $companyId = null;
	private $daoCompany = null;
	private $isNewFlg = null;

	/**
	* 初期処理を行います。
	* 
	* @param string $cid 会社ID
	*/
	public function __construct($cid = null)
	{
		$this->daoCompany = new DaoCompany();
		$this->isNewFlg = true;

		if(!empty($cid)){
			$this->companyId = $cid;
			$this->isNewFlg = false;
		}
	}

	/**
	* 所属する会社IDを取得します。
	* 
	* @return string 会社ID
	*/
	public function getCompanyId()
	{
		return $this->companyId;
	}

	/**
	* 所属する会社IDを取得します。
	* 
	* @return string 会社ID
	*/
	public function isNew()
	{
		return $this->isNewFlg;
	}

	/**
	* 会社情報の入力チェックします。
	* 
	* @param string $errorList エラーリスト
	* @return array エラー内容
	*/
	public function checkInput(& $errorList, $requireFlg = false)
	{
		if($requireFlg){
			// 会社名が未入力で、他の何かしらの項目に入力がある場合
			if(empty($_POST["txtCompanyName"])){
				// 会社名は入力必須
				$errorList[] = '1001';
			}
		}else{
			// 会社名が未入力で、他の何かしらの項目に入力がある場合
			if( empty($_POST["txtCompanyName"]) &&
				!(	empty($_POST["txtShortCompanyName"]) &&
					empty($_POST["txtCompanyZipCode1"]) &&
					empty($_POST["txtCompanyZipCode2"]) &&
					empty($_POST["txtCompanyAddress1"]) &&
					empty($_POST["txtCompanyAddress2"]) &&
					empty($_POST["txtCompanyAddress3"]) &&
					empty($_POST["txtCompanyTelephoneNumber1"]) &&
					empty($_POST["txtCompanyTelephoneNumber2"]) &&
					empty($_POST["txtCompanyFaxNumber1"]) &&
					empty($_POST["txtCompanyMailAddress1"]) &&
					empty($_POST["txtCompanyNote"]) ) ){

				// 会社名は入力必須
				$errorList[] = '1001';
			}
		}
	}

	/**
	* 会社情報を登録します。
	* 
	* @return bool 登録結果
	*/
	public function regist()
	{
		// クエリ実行
		$this->companyId = $this->daoCompany->insert($this->setParams());
		
		if(empty($this->companyId)){
			return false;
		}

		return true;
	}

	/**
	* 会社情報を更新します。
	* 
	* @return bool 登録結果
	*/
	public function modify()
	{
		if(empty($this->companyId)){
			return false;
		}

		// 更新対象を設定
		$conditions = array();
		$conditions["CompanyID"] = $this->companyId;

		// クエリ実行
		return $this->daoCompany->update($this->setParams(), $conditions);
	}

	/**
	* 会社情報を論理削除します。
	* 
	* @return bool 登録結果
	*/
	public function delete()
	{
		if(empty($this->companyId)){
			return false;
		}

		// 更新対象を設定
		$params = array();
		$params["DeleteFlg"] = 1;

		$conditions = array();
		$conditions["CompanyID"] = $this->companyId;

		// クエリ実行
		return $this->daoCompany->update($params, $conditions);
	}

	/**
	* 送信された会社情報を設定します。
	* 
	* @return bool 登録結果
	*/
	public function getCompanyInfo()
	{
		$info = array();
		$info["CompanyID"] = $_POST["cid"];
		$info["CompanyName"] = $_POST["txtCompanyName"];
		$info["SearchCompanyName"] = $_POST["txtCompanyName"];
		$info["ShortCompanyName"] = $_POST["txtShortCompanyName"];
		$info["IndexCompanyName"] = $_POST["cmbIndexCompanyName"];
		$info["ZipCode"] = $_POST["txtCompanyZipCode1"];
		if(!empty($_POST["txtCompanyZipCode2"])){
			$info["ZipCode"] .= "-" . $_POST["txtCompanyZipCode2"];
		}
		if($_POST["hdnCompanyAddressInputType"] == "text"){
			$info["Address1"] = $_POST["txtCompanyAddress1"];
		}else{
			$info["Address1"] = CommonUtil::getIniValue('prefecture', $_POST["cmbCompanyAddress1"]);
		}

		$info["Address2"] = $_POST["txtCompanyAddress2"];
		$info["Address3"] = $_POST["txtCompanyAddress3"];
		$info["TelephoneNumber1"] = $_POST["txtCompanyTelephoneNumber1"];
		$info["TelephoneNumber2"] = $_POST["txtCompanyTelephoneNumber2"];
		$info["FaxNumber1"] = $_POST["txtCompanyFaxNumber1"];
		$info["MailAddress1"] = $_POST["txtCompanyMailAddress1"];
		$info["Note"] = $_POST["txtCompanyNote"];

		return $info;
	}

	private function setParams()
	{
		$params = array();
		$params["CompanyName"] = $_POST["txtCompanyName"];
		$params["SearchCompanyName"] = CommonUtil::convertSearchValue($_POST["txtCompanyName"]);
		$params["ShortCompanyName"] = CommonUtil::convertSearchValue($_POST["txtShortCompanyName"]);
		$params["IndexCompanyName"] = empty($_POST["cmbIndexCompanyName"]) ? 0 : $_POST["cmbIndexCompanyName"];
		$params["ZipCode"] = CommonUtil::convertNumberValue($_POST["txtCompanyZipCode1"]);
		if(!empty($_POST["txtCompanyZipCode2"])){
			$params["ZipCode"] .= "-" . CommonUtil::convertNumberValue($_POST["txtCompanyZipCode2"]);
		}
		if($_POST["hdnCompanyAddressInputType"] == "text"){
			$params["Address1"] = CommonUtil::convertNumberValue($_POST["txtCompanyAddress1"]);
		}else{
			$params["Address1"] = CommonUtil::getIniValue('prefecture', $_POST["cmbCompanyAddress1"]);
		}

		$params["Address2"] = CommonUtil::convertNumberValue($_POST["txtCompanyAddress2"]);
		$params["Address3"] = CommonUtil::convertNumberValue($_POST["txtCompanyAddress3"]);
		$params["TelephoneNumber1"] = CommonUtil::convertNumberValue($_POST["txtCompanyTelephoneNumber1"]);
		$params["TelephoneNumber2"] = CommonUtil::convertNumberValue($_POST["txtCompanyTelephoneNumber2"]);
		$params["FaxNumber1"] = CommonUtil::convertNumberValue($_POST["txtCompanyFaxNumber1"]);
		$params["MailAddress1"] = CommonUtil::convertNumberValue($_POST["txtCompanyMailAddress1"]);
		$params["Note"] = $_POST["txtCompanyNote"];

		return $params;
	}
}
