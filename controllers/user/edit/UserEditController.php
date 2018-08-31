<?php
Sp::import('UsersDao', 'dao');
Sp::import('ProfileImage', 'libs');
/**
 * 登録情報の変更
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserEditController extends BaseController
{
	/**
	 *
	 */
	public function index()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();
		$userInfo = $this->getUserInfo();

		$this->form->setSp('publicOptions', Util::arrayToTextValue(AppConst::$publicFlag));

		$this->form->set('htitle', '登録情報');
		$this->setTitle($this->form->get('htitle'), $userInfo['penname']);

		return $this->forward('user/edit/user_edit_index', APP_CONST_USER_FRAME);
	}

	/**
	 * 変更画面
	 */
	public function change()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();
		$userInfo = $this->getUserInfo();

		$mode = $this->form->get('mode');
		if ($mode == 'password') {
			$tpl_name = 'user/edit/user_edit_change_password';
		} else if ($mode == 'email') {
			$tpl_name = 'user/edit/user_edit_change_email';
		} else {
			return $this->notfoundPage();
		}

		$this->form->setParameterForm('mode');

		$this->form->set('htitle', '登録情報');
		$this->setTitle($this->form->get('htitle'), $userInfo['penname']);

		return $this->forward($tpl_name, APP_CONST_USER_FRAME);
	}

	/**
	 * 変更処理
	 */
	public function changed()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();
		$userInfo = $this->getUserInfo();

		$mode = $this->form->get('mode');
		if ($mode == 'password') {
			if ($this->_validatePassword() === false) {
				$this->form->set('errors', $this->form->getValidateErrors());
				return $this->change();
			}
			$redirect = '/user/edit/change_password?success=true';
		} else if ($mode == 'email') {
			if ($this->_validateEmail() === false) {
				$this->form->set('errors', $this->form->getValidateErrors());
				return $this->change();
			}
			$redirect = '/user/edit/change_email?success=true';
		} else {
			return $this->notfoundPage();
		}

		try {

			$this->db->beginTransaction();

			$dao = new UsersDao($this->db);
			if ($mode == 'password') {
				$dao->addValueStr(UsersDao::COL_PASSWORD, Util::password($this->form->get('new_password')));
				$dao->addValue(UsersDao::COL_LASTUPDATE, Dao::DATE_NOW);
			} else if ($mode == 'email') {
				$temp_key = md5(Util::uniqId());
				$dao->addValueStr(UsersDao::COL_TEMP_KEY, $temp_key);
				$dao->addValueStr(UsersDao::COL_CHANGE_EMAIL, $this->form->get('new_email'));
			}
			$dao->addWhere(UsersDao::COL_USER_ID, $userInfo['id']);
			$dao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
			$dao->addWhere(UsersDao::COL_DELETE_FLAG, UsersDao::DELETE_FLAG_ON);
			$dao->doUpdate();

			if ($mode == 'email') {
				// 確認メール送信
				$mail_arr = $userInfo;
				$mail_arr['change_url'] = constant('app_site_url').'user/edit/changed_email?key='.$temp_key;

				$mail_to = $this->form->get('new_email');
				$mail_title = '【'.APP_CONST_SITE_TITLE_J.'】メールアドレスの変更';
				$mail_body = $this->form->getTemplateContents($mail_arr, '_mail/change_email_send');
				$mail_from = APP_CONST_SERVICE_EMAIL;
				$mail_from_name = APP_CONST_BIZ_NAME;

				$send_errmsg = '';
				if (Util::sendMail($mail_to, $mail_title, $mail_body, $mail_from, $mail_from_name, 'UTF-8', &$send_errmsg) === false) {
					$this->logger->error("メール送信に失敗しました。[To:${mail_to}, From:${mail_from}]\n".$send_errmsg);
					$errmsg = 'メール送信に失敗。有効なメールアドレスであることを確認して再度送信をおこなってください。';
					$this->form->setValidateErrors('new_email', $errmsg);
					$this->form->set('errors', $this->form->getValidateErrors());
					$this->db->rollback();
					return $this->change();
				}
			}

			$this->db->commit();

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
			$this->form->setValidateErrors('new_password', $e->getMessage());
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->change();
		}

		$this->form->set('htitle', '登録情報');
		$this->setTitle($this->form->get('htitle'), $userInfo['penname']);

		return $this->resp->sendRedirect($redirect);
	}

	/**
	 * メールアドレス変更の完了
	 */
	public function changed_email()
	{
		$key = $this->form->get('key');
		if (empty($key)) return $this->notfoundPage();

		$usersDao = new UsersDao($this->db);
		$usersDao->addWhereStr(UsersDao::COL_TEMP_KEY, $key);
		$usersDao->addWhereStr(UsersDao::COL_CHANGE_EMAIL, null, '!=');
		$usersDao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
		$usersDao->addWhere(UsersDao::COL_DELETE_FLAG, UsersDao::DELETE_FLAG_ON);
		$user = $usersDao->selectRow();
		if (count($user)==0) return $this->notfoundPage();

		if ($user['change_email']!='') {
			$usersDao->reset();
			$usersDao->addValueStr(UsersDao::COL_EMAIL, $user['change_email']);
			$usersDao->addValueStr(UsersDao::COL_CHANGE_EMAIL, null);
			$usersDao->addValue(UsersDao::COL_LASTUPDATE, Dao::DATE_NOW);
			$usersDao->addWhere(UsersDao::COL_USER_ID, $user['user_id']);
			$usersDao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
			$usersDao->addWhere(UsersDao::COL_DELETE_FLAG, UsersDao::DELETE_FLAG_ON);
			$usersDao->doUpdate();

			if ($this->checkUserAuth()) {
				$new_info = array('email' => $user['change_email']);
				$this->updateUserInfo($new_info);
			}
		}

		$this->form->set('htitle', '登録情報');
		$this->setTitle('');

		return $this->forward('user/edit/user_edit_changed_email', APP_CONST_USER_FRAME);
	}

	/**
	 * 登録情報の変更(ajax)
	 */
	public function profile_edit_api()
	{
		if ($this->form->isGetMethod() || $this->checkUserAuth() === false) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$data = array();
		$status = self::AJAX_STATUS_SUCCESS;
		$col = '';
		$val = '';

		$mode = $this->form->get('mode');
		if ($mode == 'profile_msg') {
			if ($this->form->validate($this->form->getValidates(3)) === false) {
				$data['errors'] = $this->form->getValidateErrors();
				$status = self::AJAX_STATUS_ERROR;
			}
			$col = UsersDao::COL_PROFILE_MSG;
			$val = $this->form->get('edit_profile_msg');
			$data['edit_profile_msg'] = $val;
		} else if ($mode == 'penname') {
			if ($this->form->validate($this->form->getValidates(4)) === false) {
				$data['errors'] = $this->form->getValidateErrors();
				$status = self::AJAX_STATUS_ERROR;
			} else {
				$dao = new UsersDao($this->db);
				if ($dao->isDuplicationByPenname($this->form->get('edit_penname'), $userInfo['id']) !== false) {
					$this->form->setValidateErrors('edit_penname', 'このペンネームは既に使われています。');
					$status = self::AJAX_STATUS_ERROR;
				}
			}
			$col = UsersDao::COL_PENNAME;
			$val = $this->form->get('edit_penname');
			$data['edit_penname'] = $val;
		} else if ($mode == 'zip') {
			if (preg_match('/^([0-9]{3})([0-9]{4})$/', $this->form->get('edit_zip'), $m)) $this->form->set('edit_zip', $m[1].'-'.$m[2]);
			if ($this->form->validate($this->form->getValidates(5)) === false) {
				$data['errors'] = $this->form->getValidateErrors();
				$status = self::AJAX_STATUS_ERROR;
			}
			$col = UsersDao::COL_ZIP;
			$val = $this->form->get('edit_zip');
			$data['edit_zip'] = $val;
		} else if ($mode == 'gender_public') {
			$col = UsersDao::COL_GENDER_PUBLIC;
			$val = $this->form->getInt('edit_gender_public');
			$data['edit_gender_public'] = $val;
		} else if ($mode == 'birthday_public') {
			$col = UsersDao::COL_BIRTHDAY_PUBLIC;
			$val = $this->form->getInt('edit_birthday_public');
			$data['edit_birthday_public'] = $val;
		} else {
			return $this->notfoundPage();
		}

		if ($status == self::AJAX_STATUS_SUCCESS) {

			try {

				$usersDao = new UsersDao($this->db);
				$usersDao->addValueStr($col, $val);
				$usersDao->addValue(UsersDao::COL_LASTUPDATE, Dao::DATE_NOW);
				$usersDao->addWhere(UsersDao::COL_USER_ID, $userInfo['id']);
				$usersDao->addWhereStr(UsersDao::COL_LOGIN, $userInfo['login']);
				$usersDao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
				$usersDao->addWhere(UsersDao::COL_DELETE_FLAG, UsersDao::DELETE_FLAG_ON);
				$usersDao->doUpdate();

				$update_info = array($col => $val);
				$this->updateUserInfo($update_info);

			} catch (SpException $e) {
				$this->logger->exception($e);
				$status = self::AJAX_STATUS_ERROR;
				$data['message'] = $e->getMessage();
			}
		}

		$data['status'] = $status;
		$this->form->set('data', Util::jsonEncode($data));

		$this->resp->setContentType(SpResponse::CTYPE_JSON);
		return $this->forward('json', APP_CONST_EMPTY_FRAME);
	}

	/**
	 * プロフィール画像の変更
	 */
	public function picture()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();
		$userInfo = $this->getUserInfo();

		$this->form->set('htitle', 'プロフィール画像');
		$this->setTitle($this->form->get('htitle'), '登録情報');

		$this->resp->noCache();

		return $this->forward('user/edit/user_edit_picture', APP_CONST_USER_FRAME);
	}

	/**
	 * プロフィール画像のアップロード処理
	 */
	public function uploadpicture()
	{
		if ($this->form->isGetMethod()) return $this->notfoundPage();
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		// チェック
		if ($this->_validatePicture() === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->picture();
		// tmpコピー
		} else if ($this->copyFileTemp('upload', APP_CONST_PROFILE_IMAGE_TMP_DIR, '', $userInfo['id'], 0) === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->picture();
		}

		try {

//			$bigger_path = '';
//			if ($this->_createPicture($this->form->get('upload_path'), &$bigger_path, APP_CONST_PROFILE_IMAGE_BIGGER_SIZE, 'bigger') === false) {
//				throw new SpException('画像(bigger)の生成に失敗しました。');
//			}
//			$normal_path = '';
//			if ($this->_createPicture($this->form->get('upload_path'), &$normal_path, APP_CONST_PROFILE_IMAGE_NORMAL_SIZE, 'normal') === false) {
//				throw new SpException('画像(normal)の生成に失敗しました。');
//			}
//			$small_path = '';
//			if ($this->_createPicture($this->form->get('upload_path'), &$small_path, APP_CONST_PROFILE_IMAGE_SMALL, 'small') === false) {
//				throw new SpException('画像(small)の生成に失敗しました。');
//			}
//
//			// 公開ディレクトリにコピー
//			$img_dir = str_replace('[login]', $userInfo['login'], APP_CONST_PROFILE_IMAGE_DIR);
//			@mkdir($img_dir, 0705, true);
//
//			$bigger_tmp = APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$bigger_path;
//			$bigger_new = $img_dir.'/'.$bigger_path;
//
//			if (UtilFile::rename($bigger_tmp, $bigger_new) === false) {
//				$this->logger->error('画像(bigger)のコピーに失敗しました。'.$bigger_tmp);
//				throw new SpException('画像(bigger)のコピーに失敗しました。');
//			}
//			$normal_tmp = APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$normal_path;
//			$normal_new = $img_dir.'/'.$normal_path;
//			if (UtilFile::rename($normal_tmp, $normal_new) === false) {
//				$this->logger->error('画像(normal)のコピーに失敗しました。'.$normal_tmp);
//				throw new SpException('画像(normal)のコピーに失敗しました。');
//			}
//			$small_tmp = APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$small_path;
//			$small_new = $img_dir.'/'.$small_path;
//			if (UtilFile::rename($small_tmp, $small_new) === false) {
//				$this->logger->error('画像(small)のコピーに失敗しました。'.$small_tmp);
//				throw new SpException('画像(small)のコピーに失敗しました。');
//			}
//			$bigger_size = filesize($bigger_new);
//			$normal_size = filesize($normal_new);
//			$small_size = filesize($small_new);

			// 48px 73px 128px サイズを作成
			$profileImage = new ProfileImage($this->logger);
			$profileImage->create($this->form->get('upload_path'), $userInfo['login']);

			// DB保存
			$this->db->beginTransaction();

			$usersDao = new UsersDao($this->db);
			$usersDao->addValueStr(UsersDao::COL_PROFILE_FILE, $this->form->get('upload_file'));
			$usersDao->addValueStr(UsersDao::COL_PROFILE_PATH, $profileImage->normalPath);
			$usersDao->addValue(UsersDao::COL_PROFILE_SIZE, $profileImage->normalSize);
			$usersDao->addValueStr(UsersDao::COL_PROFILE_S_FILE, '');
			$usersDao->addValueStr(UsersDao::COL_PROFILE_S_PATH, $profileImage->smallPath);
			$usersDao->addValue(UsersDao::COL_PROFILE_S_SIZE, $profileImage->smallSize);
			$usersDao->addValueStr(UsersDao::COL_PROFILE_B_FILE, '');
			$usersDao->addValueStr(UsersDao::COL_PROFILE_B_PATH, $profileImage->biggerPath);
			$usersDao->addValue(UsersDao::COL_PROFILE_B_SIZE, $profileImage->biggerSize);
			$usersDao->addValue(UsersDao::COL_LASTUPDATE, Dao::DATE_NOW);
			$usersDao->addWhere(UsersDao::COL_USER_ID, $userInfo['id']);
			$usersDao->addWhereStr(UsersDao::COL_LOGIN, $userInfo['login']);
			$usersDao->doUpdate();

			$update_info = array(
				'profile_file' => $this->form->get('upload_file'),
				'profile_path' => $profileImage->normalPath,
				'profile_size' => $profileImage->normalSize,
				'profile_s_path' => $profileImage->smallPath,
				'profile_s_size' => $profileImage->smallSize,
				'profile_b_path' => $profileImage->biggerPath,
				'profile_b_size' => $profileImage->biggerSize,
				'lastupdate' => date('Y-m-d H:i:s')
			);
			$this->updateUserInfo($update_info);

			$this->db->commit();

		} catch (SpException $e) {
			$this->logger->exception($e);
			$this->form->setValidateErrors('upload_file', $e->getMessage());
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->picture();
		}

		// アップロードファイルの削除
		if (file_exists(APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$this->form->get('upload_path'))) {
			@unlink(APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$this->form->get('upload_path'));
		}

		return $this->resp->sendRedirect('/user/edit/picture?save=true');
	}

	/**
	 * プロフィール画像の削除
	 */
	public function deletepicture()
	{
		if ($this->form->isGetMethod()) return $this->notfoundPage();
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		try {

			// DB保存
			$this->db->beginTransaction();

			$usersDao = new UsersDao($this->db);
			$usersDao->addValueStr(UsersDao::COL_PROFILE_FILE, '');
			$usersDao->addValueStr(UsersDao::COL_PROFILE_PATH, '');
			$usersDao->addValue(UsersDao::COL_PROFILE_SIZE, 0);
			$usersDao->addValueStr(UsersDao::COL_PROFILE_S_FILE, '');
			$usersDao->addValueStr(UsersDao::COL_PROFILE_S_PATH, '');
			$usersDao->addValue(UsersDao::COL_PROFILE_S_SIZE, 0);
			$usersDao->addValueStr(UsersDao::COL_PROFILE_B_FILE, '');
			$usersDao->addValueStr(UsersDao::COL_PROFILE_B_PATH, '');
			$usersDao->addValue(UsersDao::COL_PROFILE_B_SIZE, 0);
			$usersDao->addValue(UsersDao::COL_LASTUPDATE, Dao::DATE_NOW);
			$usersDao->addWhere(UsersDao::COL_USER_ID, $userInfo['id']);
			$usersDao->addWhereStr(UsersDao::COL_LOGIN, $userInfo['login']);
			$usersDao->doUpdate();

			$update_info = array(
				'profile_file' => '',
				'profile_path' => '',
				'profile_size' => 0,
				'profile_s_path' => '',
				'profile_s_size' => 0,
				'profile_b_path' => '',
				'profile_b_size' => 0,
				'lastupdate' => date('Y-m-d H:i:s')
			);

			$this->updateUserInfo($update_info);

			$this->db->commit();

		} catch (SpException $e) {
			$this->logger->exception($e);
			$this->form->setValidateErrors('upload_file', $e->getMessage());
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->picture();
		}

		// ファイルの削除
		$img_dir = str_replace('[login]', $userInfo['login'], APP_CONST_PROFILE_IMAGE_DIR);
		if (file_exists($img_dir.'/'.$userInfo['profile_path'])) @unlink($img_dir.'/'.$userInfo['profile_path']);
		if (file_exists($img_dir.'/'.$userInfo['profile_s_path'])) @unlink($img_dir.'/'.$userInfo['profile_s_path']);
		if (file_exists($img_dir.'/'.$userInfo['profile_b_path'])) @unlink($img_dir.'/'.$userInfo['profile_b_path']);

		return $this->resp->sendRedirect('/user/edit/picture?delete=true');
	}

//	/**
//	 * 画像のリサイズ
//	 * @param unknown_type $upload_path
//	 * @param unknown_type $create_path
//	 * @param unknown_type $size
//	 * @param unknown_type $prefix
//	 */
//	private function _createPicture($upload_path, &$create_path, $size, $prefix)
//	{
//		$create_path = preg_replace('/\.[a-z]+$/i', '_'.$prefix.'.jpg', $upload_path);
//		$upload_img = APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$upload_path;
//		$create_img = APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$create_path;
//		if (Util::resizeImage($upload_img, $create_img, $size, $size, 'jpg', &$errmsg) === false) {
//			$this->logger->error($errmsg);
//			return false;
//		} else {
//			return true;
//		}
//	}

	/**
	 * 画像アップロードの入力チェック
	 */
	private function _validatePicture()
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
				if (!preg_match(APP_CONST_PROFILE_IMAGE_EXT_REG, $ext, $m)) {
					$this->logger->error('不正なファイル形式によるアップロード（'.$m[1].'）');
					$errmsg = 'アップロード可能な画像形式は'.APP_CONST_PROFILE_IMAGE_EXT_TXT.'のみです。';
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

	/**
	 * パスワード変更の入力チェック
	 */
	private function _validatePassword()
	{
		$userInfo = $this->getUserInfo();

		$ret = $this->form->validate($this->form->getValidates(1));

		if ($this->form->get('new_password') != $this->form->get('new_password_confirm')) {
			$this->form->setValidateErrors('new_password_confirm', '新しいパスワードが一致していません。');
			$ret = false;
		}

		$old_password = $this->form->get('old_password');
		if ($ret && $old_password!='') {
			$usersDao = new UsersDao($this->db);
			$user = $usersDao->getItem($userInfo['id'], UsersDao::STATUS_REGULAR);
			if ($user['password'] != Util::password($old_password)) {
				$this->form->setValidateErrors('old_password', '古いパスワードが間違っています。');
				$ret = false;
			} else if ($this->form->get('new_password') == $old_password) {
				$this->form->setValidateErrors('new_password', '新しいパスワードが変更されていません。');
				$ret = false;
			}
		}

		return $ret;
	}

	/**
	 * メールアドレス変更の入力チェック
	 */
	private function _validateEmail()
	{
		$userInfo = $this->getUserInfo();

		$ret = $this->form->validate($this->form->getValidates(2));

		if ($this->form->get('new_email') != $this->form->get('new_email_confirm')) {
			$this->form->setValidateErrors('new_email_confirm', '新しいメールアドレスが一致していません。');
			$ret = false;
		}

		if ($userInfo['email'] == $this->form->get('new_email')) {
			$this->form->setValidateErrors('new_email', 'メールアドレスが変更されていません。');
			$ret = false;
		}

		if ($ret !== false) {
			$dao = new UsersDao($this->db);
			if ($dao->isDuplicationByEmail($this->form->get('new_email'), $userInfo['id']) !== false) {
				$this->form->setValidateErrors('new_email', 'このメールアドレスは既に使われています。');
				$ret = false;
			}
		}

		return $ret;
	}
}
?>
