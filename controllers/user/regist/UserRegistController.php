<?php
Sp::import('UsersDao', 'dao');
Sp::import('ProfileImage', 'libs');
/**
 * ユーザー新規登録
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserRegistController extends BaseController
{
	/**
	 * メール送信フォーム
	 */
	public function first()
	{
		$this->form->set('htitle', 'スポットワードIDを登録する');
		$this->setTitle('');

		$this->createSecurityCode();

		$this->resp->noCache();

		return $this->forward('user/regist/user_regist_first', APP_CONST_MAIN_FRAME);
	}

	/**
	 * メール送信処理
	 */
	public function send()
	{
		if ($this->checkSecurityCode() === false) return $this->errorPage(self::ERROR_PAGE_MESSAGE5);

		if ($this->_validateSend() === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->first();
		}

		$email = $this->form->get('send_email');

		$is_send_error = false;

		try {

			$dao = new UsersDao($this->db);

			if ($dao->isDuplicationByEmail($email, 0) === false) {

				$temp_key = md5(Util::uniqId());

				$this->db->beginTransaction();

				$dao->reset();
				$dao->addValueStr(UsersDao::COL_TEMP_KEY, $temp_key);
				$dao->addValueStr(UsersDao::COL_STATUS, UsersDao::STATUS_TEMP);
				$dao->addValueStr(UsersDao::COL_EMAIL, $email);
				$dao->addValue(UsersDao::COL_CREATEDATE, Dao::DATE_NOW);
				$dao->addValue(UsersDao::COL_LASTUPDATE, Dao::DATE_NOW);
				$dao->doInsert();

				// 確認メール送信
				$mail_arr = $this->form->getAll();
				$mail_arr['regist_url'] = constant('app_site_url').'user/regist/?key='.$temp_key;

				$mail_to = $email;
				$mail_title = '【'.APP_CONST_SITE_TITLE_J.'】登録を完了させてください';
				$mail_body = $this->form->getTemplateContents($mail_arr, '_mail/regist_send');
				$mail_from = APP_CONST_SERVICE_EMAIL;
				$mail_from_name = APP_CONST_SITE_TITLE_J;

				$send_errmsg = '';
				if (Util::sendMail($mail_to, $mail_title, $mail_body, $mail_from, $mail_from_name, 'UTF-8', &$send_errmsg) === false) {
					$this->logger->error("メール送信に失敗しました。[To:${mail_to}, From:${mail_from}]\n".$send_errmsg);
					$errmsg = 'メール送信に失敗。有効なメールアドレスであることを確認して再度送信をおこなってください。';
					$this->form->setValidateErrors('send_email', $errmsg);
					$this->form->set('errors', $this->form->getValidateErrors());
					throw new SpException($errmsg);
				}
				if ($send_errmsg) $this->logger->error("メール送信に失敗しました。[To:${mail_to}, From:${mail_from}]\n".$send_errmsg);

				$this->db->commit();
			} else {
				// 既に登録済み
				$is_send_error = true;
			}

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
			return $this->first();
		}

		$this->form->set('is_send_error', $is_send_error);

		$this->form->set('htitle', 'スポットワードIDを登録する');
		$this->setTitle('');

		$this->resp->noCache();

		return $this->forward('user/regist/user_regist_send', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 一覧
	 */
	public function index()
	{
		$key = $this->form->get('key');
		if (empty($key)) return $this->resp->sendRedirect('/user/regist/first');

		$usersDao = new UsersDao($this->db);
		$user = $usersDao->getItemByTempKey($key);
		if (count($user)==0) return $this->errorPage(parent::ERROR_PAGE_MESSAGE1);
		$this->form->set('user', $user);
		$this->form->set('email', $user['email']);

		if ($this->form->isGetMethod()) {
			$this->form->setDefault('birthday_y', 1970);
			$this->form->setDefault('birthday_m', 1);
			$this->form->setDefault('birthday_d', 1);
			$this->form->setDefault('gender', 1);
			$this->form->setDefault('melmaga_basic', 1);
		}

		$y_arr = Util::makeNumberArray(1902, date('Y')-1, '%d', 1);
		$m_arr = Util::makeNumberArray(1, 12, '%d', 1);
		$d_arr = Util::makeNumberArray(1, 31, '%d', 1);
		$this->form->setSp('birthdayYearOptions', Util::arrayToTextValue($y_arr, 1));
		$this->form->setSp('birthdayMonthOptions', Util::arrayToTextValue($m_arr, 1));
		$this->form->setSp('birthdayDayOptions', Util::arrayToTextValue($d_arr, 1));

		$this->form->setParameterForm('key');
		$this->form->setParameterForm('email');

		$this->form->set('htitle', 'スポットワードIDを登録する');
		$this->setTitle('');

		$this->resp->noCache();

		return $this->forward('user/regist/user_regist_index', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 確認
	 */
	public function confirm()
	{
		$key = $this->form->get('key');
		if (empty($key)) return $this->resp->sendRedirect('/user/regist/first');

		$usersDao = new UsersDao($this->db);
		$user = $usersDao->getItemByTempKey($key);
		if (count($user)==0) return $this->resp->sendRedirect('/user/regist/first');

		if ($this->_validate() === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->index();
		}

		$this->form->set('password_text', str_repeat('＊', strlen($this->form->get('password'))));

		$this->form->setParameterForm('penname');
		$this->form->setParameterForm('login');
		$this->form->setParameterForm('password');
		$this->form->setParameterForm('password_confirm');
		$this->form->setParameterForm('birthday_y');
		$this->form->setParameterForm('birthday_m');
		$this->form->setParameterForm('birthday_d');
		$this->form->setParameterForm('gender');
		$this->form->setParameterForm('zip');
		$this->form->setParameterForm('melmaga_basic');
		$this->form->setParameterForm('agree');
		$this->form->setParameterForm('email');
		$this->form->setParameterForm('key');

		$this->form->set('htitle', 'スポットワードIDを登録する');
		$this->setTitle('');

		$this->createSecurityCode();

		$this->resp->noCache();

		return $this->forward('user/regist/user_regist_confirm', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 登録完了
	 */
	public function complete()
	{
		if ($this->checkSecurityCode() === false) return $this->errorPage(parent::ERROR_PAGE_MESSAGE5);

		$key = $this->form->get('key');
		if (empty($key)) return $this->resp->sendRedirect('/user/regist/first');

		$usersDao = new UsersDao($this->db);
		$user = $usersDao->getItemByTempKey($key);
		if (count($user)==0) return $this->resp->sendRedirect('/user/regist/first');

		if ($this->_validate() === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->index();
		}

		try {

			$this->db->beginTransaction();

			$temp_key = md5(Util::uniqId());

			$dao = new UsersDao($this->db);
			$dao->addValueStr(UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
			$dao->addValueStr(UsersDao::COL_PENNAME, $this->form->get('penname'));
			$dao->addValueStr(UsersDao::COL_LOGIN, $this->form->get('login'));
			$dao->addValueStr(UsersDao::COL_PASSWORD, Util::password($this->form->get('password')));
			$dao->addValueStr(UsersDao::COL_BIRTHDAY, $this->form->get('birthday'));
			$dao->addValueStr(UsersDao::COL_GENDER, $this->form->get('gender'));
			$dao->addValueStr(UsersDao::COL_ZIP, $this->form->get('zip'));
			$dao->addValue(UsersDao::COL_MELMAGA_SYSTEM, 1);
			$dao->addValue(UsersDao::COL_MELMAGA_BASIC, $this->form->get('melmaga_basic'));
			$dao->addValue(UsersDao::COL_LASTUPDATE, Dao::DATE_NOW);
			$dao->addWhere(UsersDao::COL_USER_ID, $user['user_id']);
			$dao->addWhereStr(UsersDao::COL_TEMP_KEY, $key);
			$dao->doUpdate();

			// 完了メール送信
			$mail_arr = $this->form->getAll();
			list($mail_arr['birthday_y'], $mail_arr['birthday_m'], $mail_arr['birthday_d']) = explode('-', substr($user['birthday'], 0, 10));

			$mail_to = $user['email'];
			$mail_title = '【'.APP_CONST_SITE_TITLE_J.'】ご登録ありがとうございました';
			$mail_body = $this->form->getTemplateContents($mail_arr, '_mail/regist_finish');
			$mail_from = APP_CONST_SERVICE_EMAIL;
			$mail_from_name = APP_CONST_SITE_TITLE_J;

			$send_errmsg = '';
			if (Util::sendMail($mail_to, $mail_title, $mail_body, $mail_from, $mail_from_name, 'UTF-8', &$send_errmsg) === false) {
				$this->logger->error("メール送信に失敗しました。[To:${mail_to}, From:${mail_from}]\n".$send_errmsg);
				//$errormsg = 'メール送信に失敗しました。もう一度本登録用URLをクリックしてください。';
				//throw new SpException($errormsg);
			}

			$this->db->commit();

			$dao->reset();
			$user = $dao->getItem($user['user_id'], UsersDao::STATUS_REGULAR);
			$this->setUserInfo($user);

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
			return $this->index();
		}

		return $this->resp->sendRedirect('/user/regist/finish');
	}

	public function finish()
	{
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$this->form->set('htitle', 'スポットワードIDを登録する');
		$this->setTitle('');

		$this->resp->noCache();

		return $this->forward('user/regist/user_regist_finish', APP_CONST_MAIN_FRAME);
	}

	/**
	 * twitterログイン新規画面
	 */
	public function newuser()
	{
		$key = $this->form->get('key');
		if (empty($key)) return $this->notfoundPage();

		$usersDao = new UsersDao($this->db);
		$usersDao->addWhereStr(UsersDao::COL_TEMP_KEY, $key);
		$usersDao->addWhere(UsersDao::COL_OPEN_LOGIN, 0, '>');
		$usersDao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_TEMP);
		$usersDao->addWhere(UsersDao::COL_DELETE_FLAG, UsersDao::DELETE_FLAG_ON);
		$user = $usersDao->selectRow();
		if (count($user) == 0) return $this->notfoundPage();

		if ($this->form->isGetMethod()) {
			$this->form->setAll($user);
			// ログインID、ペンネームに不備があれば入力させる
			$is_validate = $this->form->validate($this->form->getValidates(2));
			if ($this->form->validate($this->form->getValidates(3)) === false) $is_validate = false;
			if ($this->_validateNewuser() === false) $is_validate = false;
			if ($is_validate === false) {
				$this->form->set('errors', $this->form->getValidateErrors());
			}
			$this->form->set('birthday_y', 1970);
			$this->form->set('birthday_m', 1);
			$this->form->set('birthday_d', 1);
			if ($this->form->get('gender') == 0) $this->form->set('gender', 1);
			$this->form->set('melmaga_basic', 1);
			// 画像のコピー
			if ($user['open_image_url']!='') {
				$name = basename($user['open_image_url']);
				$ext = Util::getExtension($name);
				$tmpfile = $user['user_id'].'.'.$ext;
				// ネットからコピー
				// オリジナル画像を試す
				//default_profile_0_normal.png
				if (Util::startsWith($name, 'default_profile_')) {
					$image_url = str_replace('_normal.', '_bigger.', $user['open_image_url']);
				} else {
					$image_url = str_replace('_normal.', '.', $user['open_image_url']);
				}
				if (@copy($image_url, APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$tmpfile)) {
					$size = filesize(APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$tmpfile);
					$this->form->set('image_file', $name);
					$this->form->set('image_path', $tmpfile);
					$this->form->set('image_size', $size);
				} else if (@copy($user['open_image_url'], APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$tmpfile)) {
					$size = filesize(APP_CONST_PROFILE_IMAGE_TMP_DIR.'/'.$tmpfile);
					$this->form->set('image_file', $name);
					$this->form->set('image_path', $tmpfile);
					$this->form->set('image_size', $size);
				}
			}
		}

		$y_arr = Util::makeNumberArray(1902, date('Y')-1, '%d', 1);
		$m_arr = Util::makeNumberArray(1, 12, '%d', 1);
		$d_arr = Util::makeNumberArray(1, 31, '%d', 1);
		$this->form->setSp('birthdayYearOptions', Util::arrayToTextValue($y_arr, 1));
		$this->form->setSp('birthdayMonthOptions', Util::arrayToTextValue($m_arr, 1));
		$this->form->setSp('birthdayDayOptions', Util::arrayToTextValue($d_arr, 1));

		$this->form->setParameterForm('key');
		$this->form->setParameterForm('image_file');
		$this->form->setParameterForm('image_path');
		$this->form->setParameterForm('image_size');
		$this->form->setParameterForm('twitter_id');

		$this->form->setSession('before_login', $user['login']);

		$this->form->set('htitle', '新規登録');
		$this->setTitle($this->form->get('htitle'));

		$this->resp->noCache();

		return $this->forward('user/regist/user_regist_newuser', APP_CONST_MAIN_FRAME);
	}

	/**
	 * twitterログイン新規登録処理
	 */
	public function newuser_finish()
	{
		$key = $this->form->get('key');
		if (empty($key)) return $this->notfoundPage();

		$birthday = sprintf("%04d-%02d-%02d", $this->form->get('birthday_y'), $this->form->get('birthday_m'), $this->form->get('birthday_d'));
		$this->form->set('birthday', $birthday);

//		$zip = $this->form->get('zip');
//		if (preg_match('/^([0-9]{3})([0-9]{4})$/', $zip, $m)) $this->form->set('zip', $m[1].'-'.$m[2]);

		$is_validate = $this->form->validate($this->form->getValidates(4));
		if ($this->form->get('input_login')>0 && $this->form->validate($this->form->getValidates(2)) === false) $is_validate = false;
		if ($this->form->get('input_penname')>0 && $this->form->validate($this->form->getValidates(3)) === false) $is_validate = false;
		if ($this->_validateNewuser() === false) $is_validate = false;
		if ($is_validate === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->newuser();
		}

		$login = ($this->form->get('login')!='') ? $this->form->get('login') : $this->form->get('before_login');
		$this->form->clearSession('before_login');
		$penname = $this->form->get('penname');

		try {

			// 48px 73px 128px サイズ画像を作成
			if ($this->form->get('image_path')!='') {
				$profileImage = new ProfileImage($this->logger);
				$profileImage->create($this->form->get('image_path'), $login);
			}

			$this->db->beginTransaction();

			$usersDao = new UsersDao($this->db);
			$usersDao->addValue(UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
			if ($login != '') $usersDao->addValueStr(UsersDao::COL_LOGIN, $login);
			if ($penname != '') $usersDao->addValueStr(UsersDao::COL_PENNAME, $penname);
			$usersDao->addValueStr(UsersDao::COL_BIRTHDAY, $this->form->get('birthday'));
			$usersDao->addValueStr(UsersDao::COL_GENDER, $this->form->get('gender'));
			//$usersDao->addValueStr(UsersDao::COL_ZIP, $this->form->get('zip'));
			$usersDao->addValue(UsersDao::COL_MELMAGA_SYSTEM, 1);
			//$usersDao->addValue(UsersDao::COL_MELMAGA_BASIC, $this->form->get('melmaga_basic'));
			$usersDao->addValue(UsersDao::COL_MELMAGA_BASIC, 0);
			if ($this->form->get('image_path')!='') {
				$usersDao->addValueStr(UsersDao::COL_PROFILE_FILE, $this->form->get('image_file'));
				$usersDao->addValueStr(UsersDao::COL_PROFILE_PATH, $profileImage->normalPath);
				$usersDao->addValue(UsersDao::COL_PROFILE_SIZE, $profileImage->normalSize);
				$usersDao->addValueStr(UsersDao::COL_PROFILE_S_FILE, '');
				$usersDao->addValueStr(UsersDao::COL_PROFILE_S_PATH, $profileImage->smallPath);
				$usersDao->addValue(UsersDao::COL_PROFILE_S_SIZE, $profileImage->smallSize);
				$usersDao->addValueStr(UsersDao::COL_PROFILE_B_FILE, '');
				$usersDao->addValueStr(UsersDao::COL_PROFILE_B_PATH, $profileImage->biggerPath);
				$usersDao->addValue(UsersDao::COL_PROFILE_B_SIZE, $profileImage->biggerSize);
			}
			$usersDao->addWhereStr(UsersDao::COL_TEMP_KEY, $key);
			$usersDao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_TEMP);
			$usersDao->doUpdate();

			$this->db->commit();

			$usersDao->reset();
			$usersDao->addWhereStr(UsersDao::COL_TEMP_KEY, $key);
			$user = $usersDao->selectRow();
			$this->resp->sessionChangeId();
			if ($user[UsersDao::COL_OPEN_LOGIN] == UsersDao::OPEN_LOGIN_TWITTER) {
				$user['twitter_access_token'] = $this->form->get('twitter_access_token');
				$user['twitter_access_token_secret'] = $this->form->get('twitter_access_token_secret');
			}
			$this->setUserInfo($user);
			return $this->resp->sendRedirect('/user/regist/finish?nonavi=true');

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
		}

		return $this->newuser();
	}

	/**
	 * 入力チェック
	 */
	private function _validateSend()
	{
		$ret = $this->form->validate($this->form->getValidates(0));
		return $ret;
	}

	/**
	 * 入力チェック
	 */
	private function _validate()
	{
		$birthday = sprintf("%04d-%02d-%02d", $this->form->get('birthday_y'), $this->form->get('birthday_m'), $this->form->get('birthday_d'));
		$this->form->set('birthday', $birthday);

		$zip = $this->form->get('zip');
		if (preg_match('/^([0-9]{3})([0-9]{4})$/', $zip, $m)) $this->form->set('zip', $m[1].'-'.$m[2]);

		$ret = $this->form->validate($this->form->getValidates(1));

		if ($this->form->get('password') != $this->form->get('password_confirm')) {
			$this->form->setValidateErrors('password', 'パスワードが一致していません。');
			$ret = false;
		}

		$login = $this->form->get('login');
		if ($login != '') {
			$dao = new UsersDao($this->db);
			if ($dao->isDuplicationByLogin($login) !== false || in_array($login, AppConst::$notLogin)) {
				$this->form->setValidateErrors('login', 'このスポットワードIDは既に使われています。');
				$ret = false;
			}
		}

		$penname = $this->form->get('penname');
		if ($penname != '') {
			$dao = new UsersDao($this->db);
			if ($dao->isDuplicationByPenname($penname) !== false) {
				$this->form->setValidateErrors('penname', 'このペンネームは既に使われています。');
				$ret = false;
			}
		}

		$email = $this->form->get('email');
		if (Validate::email($email, null) !== false) {
			$dao = new UsersDao($this->db);
			if ($dao->isDuplicationByEmail($email) !== false) {
				$this->form->setValidateErrors('email', 'このメールアドレスは既に使われています。');
				$ret = false;
			}
		}

		return $ret;
	}

	/**
	 *
	 */
	private function _validateNewuser()
	{
		$ret = true;

		$usersDao = new UsersDao($this->db);

		$login = $this->form->get('login');
		if ($login != '') {
			if ($usersDao->isDuplicationByLogin($login) || in_array($login, AppConst::$notLogin)) {
				$this->form->setValidateErrors('login', 'このスポットワードIDは既に使われています。他のIDを入力してください。');
				$ret = false;
			}
		}

		$usersDao->reset();

		$penname = $this->form->get('penname');
		if ($penname != '') {
			if ($usersDao->isDuplicationByPenname($penname)) {
				$this->form->setValidateErrors('penname', 'このペンネームは既に使われています。他のペンネームを入力してください。');
				$ret = false;
			}
		}

		return $ret;
	}
}
?>
