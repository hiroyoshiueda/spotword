<?php
Sp::import('UsersDao', 'dao');
Sp::import('BooksDao', 'dao');
Sp::import('BookRanksDao', 'dao');
/**
 * マイページ(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class MypageController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		$uname = $this->form->get('uname');
		if (empty($uname) || !preg_match('/'.APP_CONST_USER_LOGINID_REG.'/', $uname)) {
			return $this->notfoundPage();
		}

		$usersDao = new UsersDao($this->db);
		$usersDao->addWhereStr(UsersDao::COL_LOGIN, $uname);
		$usersDao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
		$usersDao->addWhere(UsersDao::COL_DISPLAY_FLAG, UsersDao::DISPLAY_FLAG_ON);
		$usersDao->addWhere(UsersDao::COL_DELETE_FLAG, UsersDao::DELETE_FLAG_ON);
		$user = $usersDao->selectRow();

		if ($user['user_id']>0) {
			$bookRanksDao = new BookRanksDao($this->db);
			$bookRanksDao->addWhere('br.'.BookRanksDao::COL_USER_ID, $user['user_id']);
			$bookRanksDao->setNewBooks();
			$this->form->set('book_list', $bookRanksDao->select());
			$this->form->set('user', $user);
			$this->form->set('htitle', $user['penname'].'の電子書籍作品');
		} else {
			//$this->resp->setStatus(404);
			$this->form->set('htitle', '');
		}

		$this->form->setParameterForm('uname');

		$this->setTitle($this->form->get('htitle'));

		$this->setKeywords($user['penname'].',');
		$this->setDescription($user['penname'].'の電子書籍作品です。');

		return $this->forward('mypage/mypage_index', APP_CONST_MAIN_FRAME);
	}
}
?>
