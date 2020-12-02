<?php
class RegistPerson extends SiteBase
{
	public function transact()
	{
		// 削除ボタン押下処理
		if(isset($_POST["btnPersonDelete"])){
			// 論理削除します。
			$blRegistPerson = new BlRegistPerson($_POST["pid"]);
			$ret = $blRegistPerson->delete();
			$this->tplh['resultMessage'] = $ret ? 3003 : 3006;
			$this->tplFile = "Result.html";

			return;
		}

		// エラーリスト初期化
		$errorList = array();

		// Key情報取得
		$cid = $_POST["cid"];
		$sid = $_POST["sid"];
		$pid = $_POST["pid"];

		// 登録用クラス初期化
		$blRegistCompany = new BlRegistCompany($cid);
		$blRegistSection = new BlRegistSection($sid, $cid);
		$blRegistPerson = new BlRegistPerson($pid, $cid, $sid);

		if($blRegistCompany->isNew()){
			// 会社情報入力チェック
			$blRegistCompany->checkInput($errorList);
		}

		if($blRegistSection->isNew()){
			// 部署情報入力チェック
			$blRegistSection->checkInput($errorList);
		}

		// 担当者情報入力チェック
		$blRegistPerson->checkInput($errorList, true);

		// エラーがあった場合
		if(!empty($errorList)){

			// 入力内容再設定して、処理終了
			$blCompany = new BlCompany($cid);
			$blSection = new BlSection($sid);

			// 部署プルダウンの初期化
			$this->tplh['sectionList']		= $blCompany->getSectionList();				// 部署一覧
			$this->tplh['cmbSectionList']	= $blCompany->getSectionListForComboBox();	// 部署一覧

			$this->tplh['companyInfo']		= $blRegistCompany->isNew() ? $blRegistCompany->getCompanyInfo() : $blCompany->getCompanyInfo();
			$this->tplh['sectionInfo']		= $blRegistSection->isNew() ? $blRegistSection->getSectionInfo() : $blSection->getSectionInfo();
			$this->tplh['personInfo']		= $blRegistPerson->getPersonInfo();
			$this->tplh['errorList']		= $errorList;

			return;
		}

		// -------------------------------------------
		//              ここから登録処理
		// -------------------------------------------
		// 会社情報登録
		if($blRegistCompany->isNew() && !empty($_POST["txtCompanyName"])){
			// 新規登録処理
			$blRegistCompany->regist();
			$cid = $blRegistCompany->getCompanyId();

			// 新しい会社IDを設定
			$blRegistSection->setCampanyId($cid);
			$blRegistPerson->setCampanyId($cid);
		}

		// 部署情報登録
		if($blRegistSection->isNew() && !empty($_POST["txtSectionName"])){
			// 新規登録処理
			$blRegistSection->regist();
			$sid = $blRegistSection->getSectionId();

			// 新しい部署IDを設定
			$blRegistPerson->setSectionId($sid);
		}

		// 担当者情報登録
		if($blRegistPerson->isNew()){
			// 新規登録処理
			if($blRegistPerson->regist()){
				// 登録成功
				$this->tplh['resultMessage'] = 3001;

				// 画面表示用設定
				$blCompany = new BlCompany();

				$this->tplh['companyInfo']		= array();
				$this->tplh['sectionInfo']		= array();
				$this->tplh['personInfo']		= array();

				$this->tplh['sectionList']		= array();									// 部署一覧
				$this->tplh['cmbSectionList']	= $blCompany->getSectionListForComboBox();	// 部署一覧(プルダウン用)

				return;
			}else{
				// 登録失敗
				$this->tplh['resultMessage'] = 3004;
			}
		}else{
			// 更新処理
			if($blRegistPerson->modify()){
				$this->tplh['resultMessage'] = 3002;
			}else{
				$this->tplh['resultMessage'] = 3005;
			}
		}

		// 情報再取得
		$blCompany = new BlCompany($cid);
		$blSection = new BlSection($sid);
		$blPerson = new BlPerson($pid);

		$this->tplh['personInfo']		= $blPerson->getPersonInfo();		// 担当者情報
		$this->tplh['companyInfo']		= $blCompany->getCompanyInfo();		// 会社情報
		$this->tplh['sectionInfo']		= $blSection->getSectionInfo();		// 部署情報
		$this->tplh['sectionList']		= $blCompany->getSectionList();		// 部署一覧
		$this->tplh['cmbSectionList']	= $blCompany->getSectionListForComboBox();	// 部署一覧
	}
}
