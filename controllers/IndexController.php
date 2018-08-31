<?php
Sp::import('BooksDao', 'dao');
Sp::import('BookRanksDao', 'dao');
Sp::import('UsersDao', 'dao');
Sp::import('UserRanksDao', 'dao');
Sp::import('CommentsDao', 'dao');
/**
 * INDEX(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class IndexController extends BaseController
{
	/**
	 * ホーム表示
	 * @see controllers/BaseController#index()
	 */
	public function index()
	{
		// 人気の本
		$bookRanksDao = new BookRanksDao($this->db);
		$bookRanksDao->setPopularBooks();
		$bookRanksDao->addLimit(8);
		$this->form->set('popular_book_list', $bookRanksDao->select());

		$popular_book_id_arr = Util::arraySelectKey('book_id', $this->form->get('popular_book_list'));

		// 新着の本
		$bookRanksDao->reset();
		$bookRanksDao->setNewBooks();
		$bookRanksDao->addLimit(8);
		$this->form->set('new_book_list', $bookRanksDao->select());

		$new_book_id_arr = Util::arraySelectKey('book_id', $this->form->get('new_book_list'));

		$book_id_arr = $popular_book_id_arr + $new_book_id_arr;

		// コメント数を取得
		if (count($book_id_arr) > 0) {
			$commentsDao = new CommentsDao($this->db);
			$comment_list = $commentsDao->getCountByBookIds($book_id_arr);
			$this->form->set('comment_map', Util::arrayKeyData('book_id', $comment_list));
		}

		// 人気作家
		$userRanksDao = new UserRanksDao($this->db);
		$userRanksDao->addLimit(4);
		$this->form->set('popular_user_list', $userRanksDao->getListJoinUser());

		// カテゴリ別
		$this->setCategoryList();

		$this->loadUserData();

		return $this->forward('index', APP_CONST_MAIN_FRAME);
	}

	/**
	 * ログイン処理
	 */
	public function login()
	{
		if ($this->form->isPostMethod()) {
			$login = $this->form->get('login');
			$password = $this->form->get('password');
			$loc = $this->form->get('loc');
			$hash = $this->form->get('_hash');
			if ($loc == '' || $loc == '/login') $loc = APP_CONST_USER_LOGIN_FIRST_PAGE;
			if ($hash != '') $loc .= '#'.$hash;

			if (empty($login) === false && empty($password) === false) {
				$dao = new UsersDao($this->db);
				$userInfo = $dao->getItemByLogin($login);
				if (count($userInfo)>0 && $userInfo['password'] == Util::password($password)) {
					$this->resp->sessionChangeId();
					$this->setUserInfo($userInfo);
					return $this->resp->sendRedirect($loc);
				}
			}

			$this->form->set('errors', array('login'=>array('スポットワードIDかパスワードが違います。')));
		}

		return $this->loginPage();
	}

	/**
	 * ログアウト処理
	 */
	public function logout()
	{
		$this->deleteUserInfo();
		$this->resp->sessionEnd();
		return $this->forward('logout', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 運営者情報
	 */
	public function about()
	{
		$this->form->set('htitle', '運営会社');
		$this->setTitle($this->form->get('htitle'));

		$this->setDescription('本サイトを運営する株式会社えそらのご紹介。');

		return $this->forward('about', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 利用規約
	 */
	public function rule()
	{
		$this->form->set('htitle', 'スポットワード利用規約');
		$this->setTitle($this->form->get('htitle'));

		$this->setDescription('スポットワードの利用に際して適用される利用規約です。お読みいただいて楽しい電子書籍コミュニティを楽しんでください。');

		return $this->forward('rule', APP_CONST_MAIN_FRAME);
	}

	/**
	 * プライバシーポリシー
	 */
	public function privacy()
	{
		$this->form->set('htitle', 'プライバシーポリシー');
		$this->setTitle($this->form->get('htitle'));

		return $this->forward('privacy', APP_CONST_MAIN_FRAME);
	}

	/**
	 * サイトマップ
	 */
	public function sitemap()
	{
		$list = array();

		$ndt = date('c');

		$this->_setSitemapList(&$list, '', $ndt);
		$this->_setSitemapList(&$list, 'list/', $ndt);
		$this->_setSitemapList(&$list, 'list/newarrivals', $ndt);
		$this->_setSitemapList(&$list, 'service/', $ndt, 'weekly', '0.5');
		$this->_setSitemapList(&$list, 'about', $ndt, 'weekly', '0.5');

		$booksDao = new BooksDao($this->db);
		$booksDao->addSelect(BooksDao::COL_BOOK_ID);
		$booksDao->setNewList();
		$book = $booksDao->select();
		if (count($book)>0) {
			foreach ($book as $d) {
				$this->_setSitemapList(&$list, 'book/'.$d['book_id'].'/', $ndt, 'daily', '1.0');
				$this->_setSitemapList(&$list, 'reader/'.$d['book_id'].'/', $ndt, 'weekly', '0.5');
			}
		}

		$userRanksDao = new UserRanksDao($this->db);
		$user = $userRanksDao->getListJoinUser();
		if (count($user)>0) {
			foreach ($user as $d) {
				$this->_setSitemapList(&$list, $d['login'].'/', $ndt, 'daily', '0.8');
			}
		}

		$this->_setSitemapList(&$list, 'list/category/', $ndt, 'daily');

		$booksDao = new BooksDao($this->db);
		$categorys = $booksDao->getCategoryList();
		if (count($categorys)>0) {
			foreach ($categorys as $d) {
				$this->_setSitemapList(&$list, 'list/category/'.$d['category_id'].'/', $ndt, 'daily');
			}
		}

		$this->form->set('list', $list);

		$this->resp->setContentType(SpResponse::CTYPE_XML);

		return $this->forward('sitemap', APP_CONST_EMPTY_FRAME);
	}

	private function _setSitemapList(&$list, $loc, $lastmod=null, $changefreq='hourly', $priority='1.0')
	{
		if ($lastmod===null) $lastmod = date('c');

		$list[] = array(
			'loc' => constant('app_site_url') . $loc,
			'lastmod' => $lastmod,
			'changefreq' => $changefreq,
			'priority' => $priority
		);
	}
}
?>
