<?php
Sp::import('UsersDao', 'dao');
Sp::import('BooksDao', 'dao');
Sp::import('PublicationImagesDao', 'dao');
Sp::import('EpubConvert', 'libs');
/**
 * リーダー(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class ReaderController extends BaseController
{
	/**
	 * 一覧
	 */
	public function index()
	{
		$id = $this->form->getInt('id', 0);
		if (empty($id)) return $this->notfoundPage();

		$booksDao = new BooksDao($this->db);
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

		// ユーザー
		$usersDao = new UsersDao($this->db);
		$user = $usersDao->getItem($book['user_id']);
		$this->form->set('user', $user);

		$this->form->set('htitle', $book['title'].' by '.$user['penname']);
		$this->setTitle($this->form->get('htitle'), '電子書籍リーダー');

		$this->setDescription($user['penname'].'の電子書籍「'.$book['title'].'」をページめくり対応のリーダーで読めます。');

		// リーダーの読み込み
		$this->setupReader();

		return $this->forward('reader/reader_index_new', APP_CONST_READER_FRAME);
	}
//	public function index()
//	{
//		$id = $this->form->getInt('id', 0);
//		if (empty($id)) return $this->notfoundPage();
//
//		$booksDao = new BooksDao($this->db);
//		$book = $booksDao->getItem($id);
//		if (count($book)==0) return $this->notfoundPage();
//		$this->form->set('book', $book);
//
//		// ユーザー
//		$usersDao = new UsersDao($this->db);
//		$user = $usersDao->getItem($book['user_id']);
//		$this->form->set('user', $user);
//
//		$this->form->set('htitle', $book['title'].' by '.$user['penname']);
//		$this->setTitle($this->form->get('htitle'));
//
//		$this->setDescription($user['penname'].'の電子書籍「'.$book['title'].'」をページめくりリーダーで読む。');
//
//		// リーダーの読み込み
//		$this->setupReader();
//
//		return $this->forward('reader/reader_index', APP_CONST_READER_FRAME);
//	}

	/**
	 * epub内ファイルの取得
	 */
	public function get_epub_file()
	{
		$id = $this->form->getInt('id', 0);
		$file = $this->form->get('file');
		if (empty($id) || empty($file)) return $this->notfoundPage();

		$booksDao = new BooksDao($this->db);
		$book = $booksDao->getItem($id);
		if (count($book)==0) return $this->notfoundPage();

		$file = preg_replace('/^[\.\/]+/', '', $file);

		if (EpubConvert::downloadFile(APP_CONST_BOOK_EPUB_DIR, $id, $file) === false) {
			$publicationImagesDao = new PublicationImagesDao($this->db);
			$images = $publicationImagesDao->getList($id, $book['user_id']);
			$epubConvert = new EpubConvert($this->db, $id, constant('app_site_url'), &$images);
			$epubConvert->create(APP_CONST_BOOK_EPUB_DIR);
			$this->logger->debug('EPUB: create to book-'.$id.'.epub');
			EpubConvert::downloadFile(APP_CONST_BOOK_EPUB_DIR, $id, $file);
		}
		return false;
	}
}
?>
