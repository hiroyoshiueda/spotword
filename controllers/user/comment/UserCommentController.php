<?php
Sp::import('UsersDao', 'dao');
Sp::import('CommentsDao', 'dao');
Sp::import('BooksDao', 'dao');
Sp::import('BookRanksDao', 'dao');
/**
 * コメント管理(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserCommentController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();

		$userInfo = $this->getUserInfo();

		$commentsDao = new CommentsDao($this->db);
		$this->form->set('list', $commentsDao->getListByUserId($userInfo['id']));

		$booksDao = new BooksDao($this->db);
		$book_ids = Util::arraySelectKey('book_id', $this->form->get('list'));
		$book_list = $booksDao->getListByBookIds($book_ids);
		$this->form->set('book_data', Util::arrayKeyData('book_id', $book_list));

		$this->form->set('htitle', 'コメントの管理');
		$this->setTitle($this->form->get('htitle'));

		return $this->forward('user/comment/user_comment_index', APP_CONST_USER_FRAME);
	}

	public function delete()
	{
		$id = $this->form->getInt('id');
		$book_id = $this->form->getInt('book_id');
		if ($this->checkUserAuth() === false || empty($id) || empty($book_id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		try {

			$this->db->beginTransaction();

			$commentsDao = new CommentsDao($this->db);
			$commentsDao->delete($id, $userInfo['id']);

			// コメント数を減らす
			$bookRanksDao = new BookRanksDao($this->db);
			$bookRanksDao->addValue(BookRanksDao::COL_COMMENT_TOTAL, BookRanksDao::COL_COMMENT_TOTAL.'-1');
			$bookRanksDao->addWhere(BookRanksDao::COL_BOOK_ID, $book_id);
			$bookRanksDao->doUpdate();

			$this->db->commit();

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
		}

		return $this->resp->sendRedirect('/user/comment/?delete=true');
	}
}
?>
