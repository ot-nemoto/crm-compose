<?php
class RegistCompany extends SiteBase
{
	public function transact()
	{
		// 削除ボタン押下処理
		if(isset($_POST["btnCompanyDelete"])){
			// 論理削除します。
			$blRegistCompany = new BlRegistCompany($_POST["cid"]);
			$ret = $blRegistCompany->delete();
			$this->tplh['resultMessage'] = $ret ? 3003 : 3006;
			$this->tplFile = "Result.html";

			return;
		}

		// エラーリスト初期化
		$errorList = array();

		// 会社情報初期化
		$blCompany = new BlCompany($_POST["cid"]);
		$blRegistCompany = new BlRegistCompany($_POST["cid"]);

		// 入力チェック
		$blRegistCompany->checkInput($errorList, true);

		// エラーがあった場合
		if(!empty($errorList)){
			// 入力内容再設定
			$this->tplh['companyInfo']	= $blRegistCompany->getCompanyInfo();
			$this->tplh['sectionList']	= $blCompany->getSectionList();
			$this->tplh['personList']	= $blCompany->getPersonList();
			$this->tplh['errorList']	= $errorList;

			return;
		}

		// 新規・更新判定
		if($blRegistCompany->isNew()){
			// 新規登録処理
			if($blRegistCompany->regist()){
				$this->tplh['resultMessage'] = 3001;
			}else{
				$this->tplh['resultMessage'] = 3004;
			}
		}else{
			// 更新処理
			if($blRegistCompany->modify()){
				$this->tplh['resultMessage'] = 3002;
			}else{
				$this->tplh['resultMessage'] = 3005;
			}

			// 会社情報再設定用
			$this->tplh['companyInfo']	= $blCompany->getCompanyInfo();
			$this->tplh['sectionList']	= $blCompany->getSectionList();
			$this->tplh['personList']	= $blCompany->getPersonList();
		}
	}
}
