<?php
Sp::import('UsersDao', 'dao');
/**
 * パスワード再設定
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class AccountPasswordController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		$this->form->set('htitle', 'パスワードを再設定する');
		$this->setTitle($this->form->get('htitle'));

		return $this->forward('account/password/account_password_index', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 再発行送信
	 */
	public function send()
	{
		$login = $this->form->get('login');
		$email = $this->form->get('email');

		if (!empty($login) && !empty($email) && Validate::email($email, null)) {
			$dao = new UsersDao($this->db);
			$item = $dao->getItemByLoginAndEmail($login, $email);
			if (count($item)>0) {
				$user_type = 'user';
				// 確認メール送信
				$mail_arr = $item;
				$mail_arr['reset_password_url'] = constant('app_site_url').'account/password/reset_'.$user_type.'?key='.$item['temp_key'];

				$mail_to = $email;
				$mail_title = '【'.APP_CONST_SITE_TITLE_J.'】パスワード再設定のお知らせ';
				$mail_body = $this->form->getTemplateContents($mail_arr, '_mail/password_send');
				$mail_from = APP_CONST_SERVICE_EMAIL;
				$mail_from_name = APP_CONST_BIZ_NAME;
				$send_errmsg = '';

				if (Util::sendMail($mail_to, $mail_title, $mail_body, $mail_from, $mail_from_name, 'UTF-8', &$send_errmsg) === false) {
					$this->logger->error("メール送信に失敗しました。[To:${mail_to}, From:${mail_from}]".$send_errmsg);
					$this->form->setValidateErrors('msg', 'メール送信に失敗しました。'.$send_errmsg);
					$this->form->set('sys_errors', $this->form->getValidateErrors());
					return $this->index();
				}

				$this->form->set('htitle', 'パスワードを再設定する');
				$this->setTitle($this->form->get('htitle'));

				return $this->forward('account/password/account_password_send', APP_CONST_MAIN_FRAME);

			} else {
				$this->form->setValidateErrors('email', 'ご指定のスポットワードID・メールアドレスは登録されていません。');
			}
		} else {
			$this->form->setValidateErrors('email', 'スポットワードID・メールアドレスを入力してください。');
		}

		if ($this->form->isValidateErrors()) {
			$this->form->set('errors', $this->form->getValidateErrors());
		}

		return $this->index();
	}

	public function reset_user()
	{
		return $this->_reset('user');
	}

//	public function reset_publisher()
//	{
//		return $this->_reset('publisher');
//	}

	private function _reset($user_type)
	{
		$key = $this->form->get('key');
		if (empty($key)) return $this->notfoundPage();
		$dao = new UsersDao($this->db);
		$item = $dao->getItemByTempKeyRegular($key);
		if (count($item)==0) return $this->errorPage(parent::ERROR_PAGE_MESSAGE1);

		$this->form->setParameter('user_type', $user_type);
		$this->form->setParameterForm('key');

		$this->form->set('htitle', 'パスワードを再設定する');
		$this->setTitle($this->form->get('htitle'));

		return $this->forward('account/password/account_password_reset', APP_CONST_MAIN_FRAME);
	}

	public function reset_save()
	{
		$key = $this->form->get('key');
		$user_type = $this->form->get('user_type');
		if (empty($key) || empty($user_type)) return $this->notfoundPage();

		if ($this->_validate()===false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->_reset($user_type);
		}

		if ($user_type == 'user') {
			$dao = new UsersDao($this->db);
			$id_col = 'user_id';
//		} else {
//			$dao = new PublishersDao($this->db);
//			$id_col = 'publisher_id';
		}
		$item = $dao->getItemByTempKeyRegular($key);
		if (count($item)==0) return $this->notfoundPage();

		$new_temp_key = md5(Util::uniqId());

		$dao->reset();
		$dao->addValueStr('temp_key', $new_temp_key);
		$dao->addValueStr('password', Util::password($this->form->get('password')));
		$dao->addValue('lastupdate', Dao::DATE_NOW);
		$dao->addWhereStr('temp_key', $key);
		$dao->addWhere($id_col, (int)$item[$id_col]);
		$dao->doUpdate();

		$this->form->set('htitle', 'パスワードを再設定する');
		$this->setTitle($this->form->get('htitle'));

		return $this->forward('account/password/account_password_reset_save', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 入力値チェック
	 */
	private function _validate()
	{
		$ret = $this->form->validate($this->form->getValidates(0));

		if ($this->form->get('password') != $this->form->get('password_confirm')) {
			$this->form->setValidateErrors('password_confirm', 'パスワードが一致していません');
			$ret = false;
		}

		return $ret;
	}
}
?>
