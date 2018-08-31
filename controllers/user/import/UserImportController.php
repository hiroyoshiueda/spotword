<?php
Sp::import('PublicationsDao', 'dao');
Sp::import('PublicationPagesDao', 'dao');
Sp::import('PublicationImagesDao', 'dao');
Sp::import('EpubConvert', 'libs');
/**
 * 読み込み機能(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserImportController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();

		$userInfo = $this->getUserInfo();

		$this->form->set('htitle', '本の読み込み');
		$this->setTitle($this->form->get('htitle'));

		$this->createSecurityCode();

		return $this->forward('user/import/user_import_index', APP_CONST_USER_FRAME);
	}

	/**
	 * 読み込み処理
	 */
	public function uploadfile()
	{
		if ($this->form->isGetMethod()) return $this->notfoundPage();
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		// チェック
		if ($this->checkSecurityCode() === false) {
			$this->form->setValidateErrors('upload_file', '重複して送信することはできません。');
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->index();
		} else if ($this->_validate() === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->index();
		}

		$id_qstr = '';
		$tmp_dir = APP_CONST_IMPORT_FILE_TMP_DIR;
		$tmp_name = '';
		$tmp_file = '';

		try {
			if (isset($_FILES['upload_file']) && $_FILES['upload_file']['name']!='') {

				$this->db->beginTransaction();

				$tmp_name = uniqid($userInfo['login'], true);
				$tmp_file = $tmp_name.'.epub.zip';
				if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $tmp_dir.'/'.$tmp_file) === false) {
					throw new SpException('一時ファイルのコピーに失敗しました。');
				}

				$publicationsDao = new PublicationsDao($this->db);
				$publicationPagesDao = new PublicationPagesDao($this->db);
				$publicationImagesDao = new PublicationImagesDao($this->db);
				// epubの読み込み
				$id = EpubConvert::read($tmp_dir.'/'.$tmp_file, $tmp_dir.'/'.$tmp_name, $userInfo['id'], $publicationsDao, $publicationPagesDao, $publicationImagesDao);
				if ($id == 0) throw new SpException('読み込みに失敗しました。');
				$id_qstr = '&id='.$id;

				$this->db->commit();
			}
		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
			$this->form->setValidateErrors('upload_file', $e->getMessage());
			$this->form->set('errors', $this->form->getValidateErrors());
			if ($tmp_file!='') @unlink($tmp_dir.'/'.$tmp_file);
			if ($tmp_name!='') UtilFile::removeDir($tmp_dir.'/'.$tmp_name);
			return $this->index();
		}

		if ($tmp_file!='') @unlink($tmp_dir.'/'.$tmp_file);
		if ($tmp_name!='') UtilFile::removeDir($tmp_dir.'/'.$tmp_name);

		return $this->resp->sendRedirect('/user/import/?success=true'.$id_qstr);
	}

	/**
	 * 入力チェック
	 */
	private function _validate()
	{
		$ret = true;
		$errmsg = '';
		if (isset($_FILES['upload_file']) && $_FILES['upload_file']['name']!='') {
			if (UtilFile::uploadFileCheck(&$_FILES['upload_file'], &$errmsg) === false) {
				$this->logger->error($errmsg);
				$ret = false;
			} else {
				$name = SpFilter::sanitize($_FILES['upload_file']['name']);
				$ext = Util::getExtension($name);
				if (!preg_match(APP_CONST_IMPORT_FILE_EXT_REG, $ext, $m)) {
					$this->logger->error('不正なファイル形式によるアップロード（'.$m[1].'）');
					$errmsg = 'アップロード可能なファイルは'.APP_CONST_IMPORT_FILE_EXT_TXT.'のみです。';
					$ret = false;
				} else {
					$this->form->set('upload_file', $name);
				}
			}
			if ($ret === false) $this->form->setValidateErrors('upload_file', $errmsg);
		}

		if ($this->form->validate($this->form->getValidates(0)) === false) $ret = false;

		return $ret;
	}
}
?>
