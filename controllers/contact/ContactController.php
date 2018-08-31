<?php
Sp::import('UsersDao', 'dao');
Sp::import('ContactsDao', 'dao');
/**
 * 問い合わせ(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class ContactController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		return $this->forward('contact/contact_index', APP_CONST_MAIN_FRAME);
	}

	public function request_api()
	{
		if ($this->form->isGetMethod()) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();
		$userinfo_str = (empty($userInfo)) ? '' : serialize($userInfo);
		$ip = $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$nowtime = date('Y-m-d H:i:s');
		$body = $this->form->get('body');

		$data = array();
		$status = self::AJAX_SUCCESS;

		if ($body != '') {
			try {
				$dao = new ContactsDao($this->db);
				$dao->addValue(ContactsDao::COL_STATUS, ContactsDao::STATUS_QUICK);
				$dao->addValueStr(ContactsDao::COL_SUBJECT, '');
				$dao->addValueStr(ContactsDao::COL_BODY, $body);
				$dao->addValueStr(ContactsDao::COL_USEREMAIL, '');
				$dao->addValueStr(ContactsDao::COL_USERNAME, '');
				$dao->addValueStr(ContactsDao::COL_USERINFO, $userinfo_str);
				$dao->addValueStr(ContactsDao::COL_USERAGENT, $ip."\n".$agent);
				$dao->addValueStr(ContactsDao::COL_CREATEDATE, $nowtime);
				$dao->doInsert();
			} catch (SpException $e) {
				$this->logger->exception($e);
				$status = self::AJAX_ERROR;
				$data['message'] = $e->getMessage();
			}
		} else {
			$status = self::AJAX_ERROR;
			$data['message'] = 'ご意見を入力してください。';
		}

		$data['status'] = $status;
		$this->form->set('data', Util::jsonEncode($data));

		$this->resp->setContentType(SpResponse::CTYPE_JSON);
		return $this->forward('json', APP_CONST_EMPTY_FRAME);
	}

	public function form()
	{
		$this->createSecurityCode();

		return $this->forward('contact/contact_form', APP_CONST_MAIN_FRAME);
	}

	public function formsend()
	{
		if ($this->form->isGetMethod()) return $this->notfoundPage();
		if ($this->checkSecurityCode() === false) return $this->errorPage(self::ERROR_PAGE_MESSAGE5);
		if ($this->_validateSend() === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->form();
		}

		$userInfo = $this->getUserInfo();
		$userinfo_str = (empty($userInfo)) ? '' : serialize($userInfo);
		$ip = $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$nowtime = date('Y-m-d H:i:s');

		try {

			$this->db->beginTransaction();

			$dao = new ContactsDao($this->db);
			$dao->addValue(ContactsDao::COL_STATUS, ContactsDao::STATUS_BASIC);
			$dao->addValueStr(ContactsDao::COL_SUBJECT, $this->form->get('subject'));
			$dao->addValueStr(ContactsDao::COL_BODY, $this->form->get('subject'));
			$dao->addValueStr(ContactsDao::COL_USEREMAIL, $this->form->get('useremail'));
			$dao->addValueStr(ContactsDao::COL_USERNAME, $this->form->get('username'));
			$dao->addValueStr(ContactsDao::COL_USERINFO, $userinfo_str);
			$dao->addValueStr(ContactsDao::COL_USERAGENT, $ip."\n".$agent);
			$dao->addValueStr(ContactsDao::COL_CREATEDATE, $nowtime);
			$dao->doInsert();

			$this->db->commit();

		} catch (SpException $e) {
			$this->logger->exception($e);
			$this->db->rollback();
			$this->form->setValidateErrors('msg', 'エラーが発生しました。しばらくしてからもう一度お試しください。');
			$this->form->set('sys_errors', $this->form->getValidateErrors());
			return $this->form();
		}

		// 確認メール送信
		$mail_arr = $this->form->getAll();
		$mail_arr['REMOTE_ADDR'] = $ip;
		$mail_arr['HTTP_USER_AGENT'] = $agent;
		$mail_arr['nowtime'] = $nowtime;

		$mail_to = APP_CONST_CONTACT_TO_EMAIL;
		$mail_title = '【お問い合わせ】';
		$mail_body = $this->form->getTemplateContents($mail_arr, '_mail/contact_send');
		$mail_from = APP_CONST_INFO_EMAIL;
		$mail_from_name = APP_CONST_SITE_TITLE_J;

		$send_errmsg = '';
		if (Util::sendMail($mail_to, $mail_title, $mail_body, $mail_from, $mail_from_name, 'UTF-8', &$send_errmsg) === false) {
			$this->logger->error("メール送信に失敗しました。[To:${mail_to}, From:${mail_from}]\n".$send_errmsg);
		}

		return $this->forward('contact/contact_formsend', APP_CONST_MAIN_FRAME);
	}

	private function _validateSend()
	{
		$ret = $this->form->validate($this->form->getValidates(0));
		return $ret;
	}
}
?>
