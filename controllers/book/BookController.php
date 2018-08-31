<?php
Sp::import('UsersDao', 'dao');
Sp::import('BooksDao', 'dao');
Sp::import('BookRanksDao', 'dao');
Sp::import('BookPagesDao', 'dao');
Sp::import('BookShelfsDao', 'dao');
Sp::import('CommentsDao', 'dao');
Sp::import('PublicationImagesDao', 'dao');
Sp::import('EpubConvert', 'libs');
/**
 * 本(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BookController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		return $this->forward('mypage/index', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 詳細
	 */
//	public function detail()
//	{
//		if (!empty($_COOKIE[APP_CONST_BOOK_NEW_COOKIE_NAME])) {
//			return $this->detail_new();
//		}
//		$id = $this->form->getInt('id', 0);
//		if (empty($id)) return $this->notfoundPage();
//		$this->form->setDefault('chapter', 1);
//		$chapter = $this->form->getInt('chapter');
//		$chapter -= 1;
//
//		$userInfo = $this->getUserInfo();
//
//		// 本データ
//		$bookPagesDao = new BookPagesDao($this->db);
//		$book = $bookPagesDao->getBookItem($id, $chapter);
//		if (count($book)==0) return $this->notfoundPage();
//		// 本文
//		$book['page_contents'] = preg_replace_callback('/(<img [^>]+>)/i', "sw_convert_contents_image", $book['page_contents']);
//		$this->form->set('book', $book);
//
//		// 目次用
//		$bookPagesDao->reset();
//		$bookPagesDao->addSelect(BookPagesDao::COL_PAGE_TITLE);
//		$bookPagesDao->addSelect(BookPagesDao::COL_PAGE_ORDER);
//		$bookPagesDao->addWhere(BookPagesDao::COL_BOOK_ID, $id);
//		$bookPagesDao->addOrder(BookPagesDao::COL_PAGE_ORDER);
//		$page = $bookPagesDao->getList();
//		$this->form->set('page', $page);
//
//		// ユーザー
//		$usersDao = new UsersDao($this->db);
//		$user = $usersDao->getItem($book['user_id']);
//		$this->form->set('user', $user);
//
//		// コメント
//		$commentsDao = new CommentsDao($this->db);
//		$commentsDao->setJoinUser($id);
//		$this->form->set('comment', $commentsDao->select());
//
//		$bookRanksDao = new BookRanksDao($this->db);
//		// 閲覧数アップ（未ログイン、本人以外、除去IP以外）
//		if (empty($userInfo) || ($userInfo['id']>0 && $userInfo['id'] != $book['user_id'])) {
//			if ($this->isNotUserAgent() === false && $this->isNotIp() === false && $this->setBookUniqCount($id, APP_CONST_BOOK_UNIQ_COOKIE_NAME)) {
//				$bookRanksDao->countPv($id);
//				$bookRanksDao->reset();
//			}
//		}
//		// 閲覧数
//		$this->form->set('book_rank', $bookRanksDao->getItem($id));
//
//		// 評価状態
//		$this->form->set('book_evaluate_status', $this->getBookUniqStatus($id, APP_CONST_BOOK_EVALUATE_UNIQ_COOKIE_NAME));
//
//		$this->createSecurityCode();
//
//		$this->form->setDefault('book_tab', 'body');
//
//		$this->form->setParameterForm('id');
//		$this->form->setParameterForm('chapter');
//
//		$this->form->set('htitle', $book['title'].' by '.$user['penname']);
//		$this->setTitle($this->form->get('htitle'));
//
//		$this->setDescription($user['penname'].'の電子書籍「'.$book['title'].'」の紹介です。');
//
//		//$this->resp->noCache();
//
//		return $this->forward('book/book_detail', APP_CONST_MAIN_FRAME);
//	}

	/**
	 * 新しいBOOK画面
	 */
	public function detail()
	{
		$id = $this->form->getInt('id', 0);
		if (empty($id)) return $this->notfoundPage();
		$this->form->setDefault('chapter', 1);
		$chapter = $this->form->getInt('chapter');
		$chapter -= 1;

		// ログイン時のみ有効
		$userInfo = $this->getUserInfo();

		$booksDao = new BooksDao($this->db);
		$booksDao->addWhere(BooksDao::COL_STATUS, BooksDao::STATUS_PUBLIC);
		$book = $booksDao->getItem($id);
		if (count($book)==0) return $this->notfoundPage();
		$this->form->set('book', $book);

		$bookPagesDao = new BookPagesDao($this->db);
		$page = $bookPagesDao->getItemList($id, $book['user_id']);
		// 内容
		for ($i=0; $i<count($page); $i++) {
			$page[$i]['page_contents'] = preg_replace_callback('/(<img [^>]+>)/i', "sw_convert_contents_image", $page[$i]['page_contents']);
		}
		$this->form->set('page', $page);

		$this->form->set('cover_path', str_replace('[user_id]', $book['user_id'], APP_CONST_COVER_IMAGE_PATH));

//		// 本データ
//		$bookPagesDao = new BookPagesDao($this->db);
//		$book = $bookPagesDao->getBookItem($id);
//		if (count($book)==0) return $this->notfoundPage();
//		foreach ($book as $i => $d) {
//			// 本文
//			$d['page_contents'] = preg_replace_callback('/(<img [^>]+>)/i', "sw_convert_contents_image", $d['page_contents']);
//			$book[$i] = $d;
//		}
//		$this->form->set('book', $book);

//		// 目次用
//		$bookPagesDao->reset();
//		$bookPagesDao->addSelect(BookPagesDao::COL_PAGE_TITLE);
//		$bookPagesDao->addSelect(BookPagesDao::COL_PAGE_ORDER);
//		$bookPagesDao->addWhere(BookPagesDao::COL_BOOK_ID, $id);
//		$bookPagesDao->addOrder(BookPagesDao::COL_PAGE_ORDER);
//		$page = $bookPagesDao->getList();
//		$this->form->set('page', $page);

		// ユーザー
		$usersDao = new UsersDao($this->db);
		$user = $usersDao->getItem($book['user_id']);
		$this->form->set('user', $user);

		// コメント
		$commentsDao = new CommentsDao($this->db);
		$commentsDao->setJoinUser($id);
		$this->form->set('comment', $commentsDao->select());

		$bookRanksDao = new BookRanksDao($this->db);
		// 閲覧数アップ（未ログイン、本人以外、除去IP以外）
		if (empty($userInfo) || ($userInfo['id']>0 && $userInfo['id'] != $book['user_id'])) {
			if (!in_array($id, array(1,2,4,5)) && $this->isNotUserAgent() === false && $this->isNotIp() === false && $this->setBookUniqCount($id, APP_CONST_BOOK_UNIQ_COOKIE_NAME)) {
				$bookRanksDao->countPv($id);
				$bookRanksDao->reset();
			}
		}
		// 閲覧数
		$this->form->set('book_rank', $bookRanksDao->getItem($id));

		// 評価状態
		$this->form->set('book_evaluate_status', $this->getBookUniqStatus($id, APP_CONST_BOOK_EVALUATE_UNIQ_COOKIE_NAME));

		$this->createSecurityCode();

		$this->form->setDefault('book_tab', 'comment');

		$this->form->setParameterForm('id');
		$this->form->setParameterForm('chapter');

		$this->form->set('htitle', $book['title'].' by '.$user['penname']);
		$this->setTitle($this->form->get('htitle'));

		$this->setDescription($user['penname'].'の電子書籍「'.$book['title'].'」の紹介です。');

		// リーダーの読み込み
		$this->setupReader();

		return $this->forward('book/book_detail_new', APP_CONST_MAIN_FRAME);
	}

	/**
	 * EPUBのダウンロード
	 */
	public function epub()
	{
		$id = $this->form->getInt('id', 0);
		if (empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$booksDao = new BooksDao($this->db);
		$booksDao->addWhere(BooksDao::COL_STATUS, BooksDao::STATUS_PUBLIC);
		$book = $booksDao->getItem($id);
		if (count($book)==0 || $book[BooksDao::COL_EPUB_FLAG] == 0) return $this->notfoundPage();

		// DL数アップ（未ログイン、本人以外、除去IP以外）
		if (empty($userInfo) || ($userInfo['id']>0 && $userInfo['id'] != $book['user_id'])) {
			if ($this->isNotUserAgent() === false && $this->isNotIp() === false && $this->setBookUniqCount($id, APP_CONST_EPUB_UNIQ_COOKIE_NAME)) {
				$bookRanksDao = new BookRanksDao($this->db);
				$bookRanksDao->countEpub($id);
			}
		}

		// epubダウンロード
		if (EpubConvert::download(APP_CONST_BOOK_EPUB_DIR, $id, $book['title']) === false) {
			$publicationImagesDao = new PublicationImagesDao($this->db);
			$images = $publicationImagesDao->getList($id, $book['user_id']);
			$epubConvert = new EpubConvert($this->db, $id, constant('app_site_url'), &$images);
			$epubConvert->create(APP_CONST_BOOK_EPUB_DIR);
			$this->logger->debug('EPUB: create to book-'.$id.'.epub');
			EpubConvert::download(APP_CONST_BOOK_EPUB_DIR, $id, $book['title']);
		}
	}

	/**
	 * マイ本棚登録(ajax)
	 */
	public function add_bookshelf_api()
	{
		$id = $this->form->getInt('id', 0);
		if ($this->form->isGetMethod() || $this->checkUserAuth() === false || empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$data = array();
		$data['status'] = self::AJAX_STATUS_SUCCESS;

		try {
			$bookshelfsDao = new BookshelfsDao($this->db);
			$bookshelfsDao->addValue(BookshelfsDao::COL_USER_ID, $userInfo['id']);
			$bookshelfsDao->addValue(BookshelfsDao::COL_PUBLICATION_ID, $id);
			$bookshelfsDao->addValue(BookshelfsDao::COL_LASTUPDATE, Dao::DATE_NOW);
			$bookshelfsDao->doInsert();
		} catch (SpException $e) {
			$this->logger->exception($e);
			$this->form->setValidateErrors('add-bookshelf-btn', 'システムエラー：'.$e->getMessage());
			$data['errors'] = $this->form->getValidateErrors();
			$data['status'] = self::AJAX_STATUS_ERROR;
		}

		$this->form->set('data', Util::jsonEncode($data));

		$this->resp->setContentType(SpResponse::CTYPE_JSON);

		return $this->forward('json', APP_CONST_EMPTY_FRAME);
	}

	/**
	 * コメント登録(ajax)
	 */
	public function save_comment_api()
	{
		$id = $this->form->getInt('id', 0);
		if ($this->form->isGetMethod() || empty($id) || $this->checkUserAuth() === false) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$data = array();
		$data['status'] = self::AJAX_STATUS_SUCCESS;

		if ($this->checkSecurityCode() === false) {
			$this->form->setValidateErrors('book_comment', '連続して投稿することはできません。');
			$data['errors'] = $this->form->getValidateErrors();
			$data['status'] = self::AJAX_STATUS_ERROR;
		} elseif ($this->form->validate($this->form->getValidates(0)) === false) {
			$data['errors'] = $this->form->getValidateErrors();
			$data['status'] = self::AJAX_STATUS_ERROR;
		} else {
			try {
				$this->db->beginTransaction();

				// user_id取得のため
				$booksDao = new BooksDao($this->db);
				$book = $booksDao->getItem($id);

				$commentsDao = new CommentsDao($this->db);
				$commentsDao->addValue(CommentsDao::COL_BOOK_ID, $id);
				$commentsDao->addValue(CommentsDao::COL_USER_ID, $book['user_id']);
				$commentsDao->addValue(CommentsDao::COL_POST_USER_ID, $userInfo['id']);
				$commentsDao->addValueStr(CommentsDao::COL_POST_USER_NAME, $userInfo['penname']);
				$commentsDao->addValueStr(CommentsDao::COL_BODY, $this->form->get('book_comment'));
				$commentsDao->addValueStr(CommentsDao::COL_POST_IP, $_SERVER['REMOTE_ADDR']);
				$commentsDao->addValueStr(CommentsDao::COL_POST_AGENT, $_SERVER['HTTP_USER_AGENT']);
				$commentsDao->addValue(CommentsDao::COL_CREATEDATE, Dao::DATE_NOW);
				$commentsDao->addValue(CommentsDao::COL_LASTUPDATE, Dao::DATE_NOW);
				$commentsDao->doInsert();
				// 登録したコメントデータをHTML化して戻す
				$comment_id = $commentsDao->getLastInsertId();
				$commentsDao->reset();
				$commentsDao->addWhere('c.'.CommentsDao::COL_COMMENT_ID, $comment_id);
				$commentsDao->setJoinUser($id);
				$comment = $commentsDao->selectRow();
				$var_arr = array('comment' => $comment, 'is_hide' => true);
				$data['html'] = $this->form->getTemplateContents($var_arr, '_parts/book_comment_list');

				// コメント数カウントアップ
				$bookRanksDao = new BookRanksDao($this->db);
				$bookRanksDao->countComment($id);

				$this->db->commit();

			} catch (SpException $e) {
				$this->db->rollback();
				$this->logger->exception($e);
				$this->form->setValidateErrors('book_comment', 'システムエラー：'.$e->getMessage());
				$data['errors'] = $this->form->getValidateErrors();
				$data['status'] = self::AJAX_STATUS_ERROR;
			}
//			$this->createSecurityCode();
//			$data['security_token'] = $this->form->get(APP_CONST_SECURITY_CODE_NAME);
		}

		$this->form->set('data', Util::jsonEncode($data));

		$this->resp->setContentType(SpResponse::CTYPE_JSON);

		return $this->forward('json', APP_CONST_EMPTY_FRAME);
	}

	/**
	 * 評価(ajax)
	 */
	public function save_evaluate_api()
	{
		$id = $this->form->getInt('id', 0);
		// 1：いいね 2：評価しない
		$type = $this->form->getInt('type', 0);
		if ($this->form->isGetMethod() || empty($id) || empty($type)) return $this->notfoundPage();

		$data = array();
		$data['status'] = self::AJAX_STATUS_SUCCESS;

		if ($this->setBookUniqCount($id, APP_CONST_BOOK_EVALUATE_UNIQ_COOKIE_NAME) === false) {
			$this->form->setValidateErrors('book_evaluate', '既に評価済みです。');
			$data['errors'] = $this->form->getValidateErrors();
			$data['status'] = self::AJAX_STATUS_ERROR;
		} else if ($this->checkSecurityCode() === false) {
			$this->form->setValidateErrors('book_evaluate', '連続して評価することはできません。');
			$data['errors'] = $this->form->getValidateErrors();
			$data['status'] = self::AJAX_STATUS_ERROR;
		} else {
			try {

				$bookRanksDao = new BookRanksDao($this->db);
				if ($type==1) {
					$bookRanksDao->countEvaluateGood($id);
				} else if ($type==2) {
					$bookRanksDao->countEvaluateBad($id);
				}
				$bookRanksDao->reset();
				$bookRanksDao->addSelect(BookRanksDao::COL_EVALUATE_TOTAL);
				$bookRanksDao->addSelect(BookRanksDao::COL_EVALUATE_GOOD);
				$bookRanksDao->addSelect(BookRanksDao::COL_EVALUATE_BAD);
				$bookRanksDao->addWhere(BookRanksDao::COL_BOOK_ID, $id);
				$item = $bookRanksDao->selectRow();
				$data[BookRanksDao::COL_EVALUATE_TOTAL] = $item[BookRanksDao::COL_EVALUATE_TOTAL];
				$data[BookRanksDao::COL_EVALUATE_GOOD] = $item[BookRanksDao::COL_EVALUATE_GOOD];
				$data[BookRanksDao::COL_EVALUATE_BAD] = $item[BookRanksDao::COL_EVALUATE_BAD];

			} catch (SpException $e) {
				$this->db->rollback();
				$this->logger->exception($e);
				$this->form->setValidateErrors('book_evaluate', 'システムエラー：'.$e->getMessage());
				$data['errors'] = $this->form->getValidateErrors();
				$data['status'] = self::AJAX_STATUS_ERROR;
			}
//			$this->createSecurityCode();
//			$data['security_token'] = $this->form->get(APP_CONST_SECURITY_CODE_NAME);
		}

		$this->form->set('data', Util::jsonEncode($data));

		$this->resp->setContentType(SpResponse::CTYPE_JSON);

		return $this->forward('json', APP_CONST_EMPTY_FRAME);
	}
}
?>
