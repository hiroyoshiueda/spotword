<?php
Sp::import('UsersDao', 'dao');
Sp::import('PublicationsDao', 'dao');
Sp::import('PublicationPagesDao', 'dao');
Sp::import('PublicationPageTempsDao', 'dao');
/**
 * 本(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserPreviewController extends BaseController
{
	/**
	 * 詳細
	 */
	public function index()
	{
		$id = $this->form->getInt('id');
		$publication_key = $this->form->get('publication_key');
		if ($this->checkUserAuth() === false || empty($id) || empty($publication_key)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$publicationsDao = new PublicationsDao($this->db);
		$book = $publicationsDao->getItem($id, $userInfo['id']);
		if (count($book)==0) return $this->notfoundPage();
		$book['page_title'] = $this->form->get('page_title');
		// 本文
		$book['page_contents'] = preg_replace_callback('/(<img [^>]+>)/i', "sw_convert_contents_image", $this->form->get('page_contents'));
		$this->form->set('book', $book);

		// 目次用
		$pagesDao = new PublicationPagesDao($this->db);
		$pagesDao->addSelect(PublicationPagesDao::COL_PAGE_TITLE);
		$pagesDao->addSelect(PublicationPagesDao::COL_PAGE_ORDER);
		$pagesDao->addWhere(PublicationPagesDao::COL_PUBLICATION_ID, $id);
		$pagesDao->addWhere(PublicationPagesDao::COL_STATUS, PublicationPagesDao::STATUS_FINISH);
		$pagesDao->addOrder(PublicationPagesDao::COL_PAGE_ORDER);
		$page = $pagesDao->getList();
		$this->form->set('page', $page);

		// ユーザー
		$usersDao = new UsersDao($this->db);
		$user = $usersDao->getItem($book['user_id']);
		$this->form->set('user', $user);

		$this->form->setDefault('book_tab', 'body');

		$this->form->setSp('is_page_preview', true);

		$this->form->setParameterForm('id');
		$this->form->setParameterForm('publication_key');

		$this->form->set('popup_title', $book['title']);

		$this->form->set('htitle', $book['title'].' by '.$user['penname']);
		$this->setTitle($this->form->get('htitle'));

		$this->resp->noCache();

		return $this->forward('book/book_detail', APP_CONST_POPUP_FRAME);
	}
}
?>
