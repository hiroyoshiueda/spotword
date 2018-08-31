<?php
Sp::import('UsersDao', 'dao');
Sp::import('BooksDao', 'dao');
Sp::import('BookRanksDao', 'dao');
/**
 * おすすめの本(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class ListController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		return $this->_getList('popular');
	}

	/**
	 * 人気本
	 */
	public function popular()
	{
		return $this->_getList('popular');
	}

	/**
	 * 新着本
	 */
	public function newarrivals()
	{
		return $this->_getList('newarrivals');
	}

	/**
	 * ジャンル別
	 */
	public function category()
	{
		$id = $this->form->getInt('id', 0);

		if ($id == 0) {

			$this->setCategoryList();

			$this->form->set('pagetype', 'category');

			$this->form->set('htitle', 'ジャンル一覧');
			$this->form->set('htitle2', 'ジャンル一覧');
			$this->setDescription('ジャンル別に電子書籍を紹介しています。');

		} else {

			$categorys = AppConst::$book_category;
			if (isset($categorys[$id]) === false) return $this->notfound();

			$total = 0;
			$offset = $this->form->getInt('offset', 0);

			$bookRanksDao = new BookRanksDao($this->db);
			$bookRanksDao->addWhere('b.'.BooksDao::COL_CATEGORY_ID, $id);
			$bookRanksDao->setPopularBooks();
			$this->form->set('list', $bookRanksDao->selectPage($offset, APP_CONST_PAGE_LIMIT, &$total));
			$this->form->set('total', $total);

			$usersDao = new UsersDao($this->db);
			$user_ids = Util::arraySelectKey('user_id', $this->form->get('list'));
			$user_list = $usersDao->getListByIds($user_ids);
			$this->form->set('user_data', Util::arrayKeyData('user_id', $user_list));

			$this->form->set('pagetype', 'category');

			$this->form->set('htitle', 'ジャンル『'.$categorys[$id].'』');
			$this->form->set('htitle2', $categorys[$id]);
			$this->setDescription('ジャンル「'.$categorys[$id].'」の電子書籍を紹介しています。');
		}

		$this->setTitle($this->form->get('htitle2'));

		return $this->forward('list/list_index', APP_CONST_MAIN_FRAME);
	}

	protected function _getList($pagetype)
	{
		$total = 0;
		$offset = $this->form->getInt('offset', 0);

		$bookRanksDao = new BookRanksDao($this->db);
		if ($pagetype == 'popular') {
			$bookRanksDao->setPopularBooks();
			$this->form->set('htitle', '人気本');
			$this->form->set('htitle2', '人気本を読む');
			$this->setDescription('投稿された電子書籍を評価や閲覧数により集計した結果を人気順に紹介しています。');
		} else {
			$bookRanksDao->setNewBooks();
			$this->form->set('htitle', '新着本');
			$this->form->set('htitle2', '新着本を読む');
			$this->setDescription('投稿された電子書籍を新着順に紹介しています。');
		}
		$this->form->set('list', $bookRanksDao->selectPage($offset, APP_CONST_PAGE_LIMIT, &$total));
		$this->form->set('total', $total);

		$usersDao = new UsersDao($this->db);
		$user_ids = Util::arraySelectKey('user_id', $this->form->get('list'));
		$user_list = $usersDao->getListByIds($user_ids);
		$this->form->set('user_data', Util::arrayKeyData('user_id', $user_list));

		$this->form->set('pagetype', $pagetype);

		$this->setTitle($this->form->get('htitle2'));

		return $this->forward('list/list_index', APP_CONST_MAIN_FRAME);
	}
}
?>
