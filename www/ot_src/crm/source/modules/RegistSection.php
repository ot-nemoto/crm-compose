<?php
class RegistSection extends SiteBase
{
	public function transact()
	{
		// 削除ボタン押下処理
		if(isset($_POST["btnSectionDelete"])){
			// 論理削除します。
			$blRegistSection = new BlRegistSection($_POST["sid"]);
			$ret = $blRegistSection->delete();
			$this->tplh['resultMessage'] = $ret ? 3003 : 3006;
			$this->tplFile = "Result.html";

			return;
		}

		// エラーリスト初期化
		$errorList = array();

		$cid = $_POST["cid"];
		$sid = $_POST["sid"];

		// 会社情報初期化
		$blCompany = new BlCompany($cid);

		// 部署情報初期化
		$blSection = new BlSection($sid);
		$blRegistSection = new BlRegistSection($sid, $cid);

		// 表示内容再設定
		$this->tplh['companyInfo']		= $blCompany->getCompanyInfo();				// 会社情報
		$this->tplh['sectionInfo']		= $blSection->getSectionInfo();				// 部署情報
		$this->tplh['personList']		= $blSection->getPersonList();

		// 入力チェック
		$blRegistSection->checkInput($errorList, true);

		// エラーがあった場合
		if(!empty($errorList)){
			// 入力内容再設定
			$this->tplh['sectionInfo']		= $blRegistSection->getSectionInfo();
			$this->tplh['errorList']		= $errorList;

			return;
		}

		// 更新処理
		if($blRegistSection->modify()){
			$this->tplh['resultMessage'] = 3002;
		}else{
			$this->tplh['resultMessage'] = 3005;
		}

		// 入力内容再設定
		$this->tplh['sectionInfo']		= $blRegistSection->getSectionInfo();
	}
}
