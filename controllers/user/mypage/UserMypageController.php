<?php
Sp::import('UsersDao', 'dao');
Sp::import('PublicationsDao', 'dao');
/**
 * マイページ(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserMypageController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		$userInfo = $this->getUserInfo();

		$uname = $this->form->get('uname');
		if (empty($uname)) {
			if ($this->checkUserAuth() === false) {
				return $this->loginPage();
			} else {
				$uname = $userInfo['login'];
			}
		}

		$usersDao = new UsersDao($this->db);
		$usersDao->addWhereStr(UsersDao::COL_LOGIN, $uname);
		$usersDao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
		$usersDao->addWhere(UsersDao::COL_DISPLAY_FLAG, 0);
		$usersDao->addWhere(UsersDao::COL_DELETE_FLAG, 0);
		$user = $usersDao->selectRow();
		if (count($user) == 0) return $this->notfoundPage();
		$this->form->set('user', $user);

		$publicationsDao = new PublicationsDao($this->db);
		$publicationsDao->addWhere(PublicationsDao::COL_USER_ID, $user['user_id']);
		$publicationsDao->addWhere(PublicationsDao::COL_STATUS, PublicationsDao::STATUS_PUBLIC);
		$publicationsDao->addWhere(PublicationsDao::COL_DELETE_FLAG, 0);
		$publicationsDao->addOrder(PublicationsDao::COL_CREATEDATE, 'DESC');
		$this->form->set('book_list', $publicationsDao->select());

		$this->form->setParameterForm('uname');

		$this->form->set('htitle', 'マイページ');
		$this->setTitle($this->form->get('htitle'));

		return $this->forward('user/mypage/user_mypage_index', APP_CONST_USER_FRAME);
	}
}
?>
