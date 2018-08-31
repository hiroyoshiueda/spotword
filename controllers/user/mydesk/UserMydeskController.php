<?php
Sp::import('PublicationsDao', 'dao');
Sp::import('PublicationPagesDao', 'dao');
Sp::import('PublicationPageTempsDao', 'dao');
Sp::import('PublicationImagesDao', 'dao');
Sp::import('BooksDao', 'dao');
Sp::import('BookPagesDao', 'dao');
Sp::import('BookRanksDao', 'dao');
Sp::import('BookRevisionsDao', 'dao');
Sp::import('UsersDao', 'dao');
/**
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserMydeskController extends BaseController
{
	/**
	 * 作品一覧
	 */
	public function index()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();
		if ($this->_checkUseMydesk() === false) return $this->_confirmMydesk();

		$userInfo = $this->getUserInfo();

		$dao = new PublicationsDao($this->db);
		$list = $dao->getListByUser($userInfo['id']);

		$public = array();
		$making = array();
		if (count($list) > 0) {
			foreach ($list as $d) {
				if ($d['status'] == 0) {
					$public[] = $d;
				} else {
					$making[] = $d;
				}
			}
		}

		$this->form->set('public_list', $public);
		$this->form->set('making_list', $making);

		$this->form->set('htitle', $userInfo['penname'].'の本');
		$this->setTitle($this->form->get('htitle'), 'マイページ');

		return $this->forward('user/mydesk/user_mydesk_index', APP_CONST_USER_FRAME);
	}

	/**
	 * 基本情報 - 新規フォーム
	 */
	public function create()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();
		if ($this->_checkUseMydesk() === false) return $this->_confirmMydesk();

		$userInfo = $this->getUserInfo();

		$this->form->setSp('categoryIdOptions', Util::arrayToTextValue(AppConst::$book_category));

		$this->form->setParameterForm('id');

		$this->form->set('htitle', '本を書く（新規作成）');
		$this->setTitle($this->form->get('htitle'), 'マイページ');

		return $this->forward('user/mydesk/user_mydesk_create', APP_CONST_USER_FRAME);
	}

	/**
	 * 基本情報 - 編集フォーム
	 */
	public function edit()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();

		$id = $this->form->getInt('id', 0);
		if (empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$publicationsDao = new PublicationsDao($this->db);
		$publication = $publicationsDao->getItem($id, $userInfo['id']);
		// 有効なページ数
		$publication['valid_page_count'] = $this->_validPageCount($id, $userInfo['id']);
		$this->form->setDefaultAll($publication);
		$this->form->set('publication', $publication);

		$this->form->setSp('categoryIdOptions', Util::arrayToTextValue(AppConst::$book_category));

		$this->form->setParameterForm('id');

		$this->form->set('htitle', $publication['title']);
		$this->setTitle($this->form->get('htitle'), 'マイページ');

		return $this->forward('user/mydesk/user_mydesk_create', APP_CONST_USER_FRAME);
	}

	/**
	 * 基本情報 - 保存処理
	 */
	public function created()
	{
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		if ($this->_validateCreated() === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->create();
		}

		$userInfo = $this->getUserInfo();

		$id = $this->form->getInt('id', 0);
		$status = 1;
		$latest_version = 1;

		$backfunc = ($id>0) ? 'edit' : 'create';

		try {

			$this->db->beginTransaction();

			$publicationsDao = new PublicationsDao($this->db);
			$publicationsDao->addValue(PublicationsDao::COL_CATEGORY_ID, $this->form->get('category_id'));
			$publicationsDao->addValueStr(PublicationsDao::COL_TITLE, $this->form->get('title'));
			$publicationsDao->addValueStr(PublicationsDao::COL_SUBTITLE, $this->form->get('subtitle'));
			$publicationsDao->addValueStr(PublicationsDao::COL_DESCRIPTION, $this->form->get('description'));

			if ($id>0) {
				$publicationsDao->addValue(PublicationsDao::COL_PUBLISH_MODIFY_FLAG, 1);
				$publicationsDao->addValue(PublicationsDao::COL_LASTUPDATE, Dao::DATE_NOW);
				$publicationsDao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
				$publicationsDao->addWhere(PublicationsDao::COL_USER_ID, $userInfo['id']);
				$publicationsDao->doUpdate();
			} else {
				$publicationsDao->addValue(PublicationsDao::COL_USER_ID, $userInfo['id']);
				$publicationsDao->addValue(PublicationsDao::COL_STATUS, $status);
				$publicationsDao->addValue(PublicationsDao::COL_LATEST_VERSION, $latest_version);
				$publicationsDao->addValue(PublicationsDao::COL_CREATEDATE, Dao::DATE_NOW);
				$publicationsDao->doInsert();
				$id = $publicationsDao->getLastInsertId();
			}

			$this->db->commit();

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
			return $this->$backfunc();
		}

		if ($backfunc == 'create') {
			return $this->resp->sendRedirect('/user/mydesk/page?id='.$id);
		} else {
			return $this->resp->sendRedirect('/user/mydesk/edit?id='.$id.'&save=true');
		}
	}

	/**
	 * 内容 - ページ一覧＆ページ並び替え
	 */
	public function page()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();

		$id = $this->form->getInt('id');
		if (empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$publicationsDao = new PublicationsDao($this->db);
		$publication = $publicationsDao->getItem($id, $userInfo['id']);
		if (count($publication) == 0) return $this->notfoundPage();
		// 有効なページ数
		$publication['valid_page_count'] = $this->_validPageCount($id, $userInfo['id']);
		$this->form->set('publication', $publication);

		$pagesDao = new PublicationPagesDao($this->db);
		$pagesDao->addSelect(PublicationPagesDao::COL_PAGE_ID);
		$pagesDao->addSelect(PublicationPagesDao::COL_STATUS);
		$pagesDao->addSelect(PublicationPagesDao::COL_PAGE_TITLE);
		$pagesDao->addOrder(PublicationPagesDao::COL_PAGE_ORDER);
		$page = $pagesDao->getItemList($id, $userInfo['id']);
		$this->form->set('page', $page);

		$this->form->setParameterForm('id');

		$this->form->set('htitle', $publication['title']);
		$this->setTitle($this->form->get('htitle'), 'マイページ');

		$this->form->setScript(APP_CONST_JS_PATH . 'jquery-ui.custom.min.js');

		return $this->forward('user/mydesk/user_mydesk_page', APP_CONST_USER_FRAME);
	}

	/**
	 * ページ作成フォーム
	 */
	public function write()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();

		$id = $this->form->getInt('id');
		if (empty($id)) return $this->notfoundPage();

		$page_id = $this->form->getInt('page_id');

		$userInfo = $this->getUserInfo();

		$publicationsDao = new PublicationsDao($this->db);
		$publication = $publicationsDao->getItem($id, $userInfo['id']);
		if (count($publication) == 0) return $this->notfoundPage();
		$this->form->set('publication', $publication);

		if ($page_id>0) {
			$pagesDao = new PublicationPagesDao($this->db);
			$page = $pagesDao->getItem($page_id, $userInfo['id']);
//			$page['page_contents'] = preg_replace('/(^<div id="page_contents_body">|<\/div>$)/iu', '', $page['page_contents']);
			$this->form->setDefaultAll($page);
		}

		$this->form->setDefault('publication_key', md5(uniqid($userInfo['login'], true)));

		$this->form->setParameterForm('id');
		$this->form->setParameterForm('page_id');
		$this->form->setParameterForm('publication_key');

		$this->form->setSp('statusOptions', Util::arrayToTextValue(AppConst::$pageStatus));

		$this->form->set('htitle', $publication['title']);
		$this->setTitle($this->form->get('htitle'), 'マイページ');

//		$this->form->setScript(APP_CONST_JS_PATH . 'ckeditor/ckeditor.js');
//		$this->form->setScript(APP_CONST_JS_PATH . 'editor.js');
		$this->form->setScript(APP_CONST_JS_PATH . 'jquery.simplemodal.js');
		$this->form->setScript(APP_CONST_JS_PATH . 'sweditor/tinymce/jquery.tinymce.js');
		$this->form->setScript(APP_CONST_JS_PATH . 'sweditor/sweditor.js');

		return $this->forward('user/mydesk/user_mydesk_write', APP_CONST_USER_FRAME);
	}

	/**
	 * ページ保存
	 */
	public function writing()
	{
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$id = $this->form->getInt('id');
		if (empty($id)) return $this->notfoundPage();

		if ($this->_validateWriting() === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->write();
		}

		if ($this->form->getInt('status') == 0) {
			$buf = preg_replace('/[　\s\r\n\t]+/u', '', strip_tags($this->form->get('page_contents')));
			$word_size = mb_strlen($buf);
		} else {
			$word_size = 0;
		}
//		if ($word_size < APP_CONST_PAGE_MAX_WORD_SIZE) {
//			$this->form->setValidateErrors('page_contents', '本文は'.APP_CONST_PAGE_MAX_WORD_SIZE.'文字以上書いてください。');
//			$this->form->set('errors', $this->form->getValidateErrors());
//			return $this->write();
//		}

		$page_id = $this->form->getInt('page_id');

		$userInfo = $this->getUserInfo();

		try {

			$this->db->beginTransaction();

			$publicationsDao = new PublicationsDao($this->db);

			$pagesDao = new PublicationPagesDao($this->db);
			$page_order = $pagesDao->getMaxPageOrder($id, $userInfo['id']);
			$page_order++;

			$pagesDao->reset();
			$pagesDao->addValue(PublicationPagesDao::COL_STATUS, $this->form->getInt('status'));
			$pagesDao->addValue(PublicationPagesDao::COL_PAGE_WORD_SIZE, $word_size);
			$pagesDao->addValueStr(PublicationPagesDao::COL_PAGE_TITLE, $this->form->get('page_title'));
			$pagesDao->addValueStr(PublicationPagesDao::COL_PAGE_CONTENTS, $this->form->get('page_contents'));
			$pagesDao->addValue(PublicationPagesDao::COL_LASTUPDATE, Dao::DATE_NOW);

			if ($page_id>0) {
				$pagesDao->addWhere(PublicationPagesDao::COL_PAGE_ID, $page_id);
				$pagesDao->addWhere(PublicationPagesDao::COL_PUBLICATION_ID, $id);
				$pagesDao->addWhere(PublicationPagesDao::COL_USER_ID, $userInfo['id']);
				$pagesDao->doUpdate();

				$pagesDao->reset();
				$pagesDao->addSelect(PublicationPagesDao::COL_PAGE_WORD_SIZE);
				$tmp_list = $pagesDao->getItemList($id, $userInfo['id'], PublicationPagesDao::STATUS_FINISH);
				$char_length = 0;
				foreach ($tmp_list as $d) {
					$char_length += $d[PublicationPagesDao::COL_PAGE_WORD_SIZE];
				}
				$publicationsDao->addValue(PublicationsDao::COL_CHAR_LENGTH, $char_length);

			} else {
				$pagesDao->addValue(PublicationPagesDao::COL_PUBLICATION_ID, $id);
				$pagesDao->addValue(PublicationPagesDao::COL_USER_ID, $userInfo['id']);
				$pagesDao->addValue(PublicationPagesDao::COL_PAGE_ORDER, $page_order);
				$pagesDao->addValue(PublicationPagesDao::COL_CREATEDATE, Dao::DATE_NOW);
				$pagesDao->doInsert();

				$publicationsDao->addValue(PublicationsDao::COL_CHAR_LENGTH, PublicationsDao::COL_CHAR_LENGTH.'+'.$word_size);
			}

			// 全体の文字数と変更フラグ
			$publicationsDao->addValue(PublicationsDao::COL_PUBLISH_MODIFY_FLAG, 1);
			$publicationsDao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
			$publicationsDao->addWhere(PublicationsDao::COL_USER_ID, $userInfo['id']);
			$publicationsDao->doUpdate();

			$publicationPageTempsDao = new PublicationPageTempsDao($this->db);
			$publicationPageTempsDao->delete($this->form->get('publication_key'), $id, $userInfo['id']);

			$this->db->commit();

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
			return $this->write();
		}

		return $this->resp->sendRedirect('/user/mydesk/page?id='.$id);
	}

	/**
	 * 編集のキャンセル
	 */
	public function cancelwrite()
	{
		$id = $this->form->getInt('id');
		$publication_key = $this->form->get('publication_key');
		if ($this->checkUserAuth() === false || empty($id) || empty($publication_key)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$publicationPageTempsDao = new PublicationPageTempsDao($this->db);
		$publicationPageTempsDao->delete($this->form->get('publication_key'), $id, $userInfo['id']);

		return $this->resp->sendRedirect('/user/mydesk/page?id='.$id);
	}

	/**
	 * 公開設定画面
	 */
	public function publish()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();

		$id = $this->form->getInt('id');
		if (empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$publicationsDao = new PublicationsDao($this->db);
		$publication = $publicationsDao->getItem($id, $userInfo['id']);
		if (count($publication) == 0) return $this->notfoundPage();
		// 有効なページ数
		$publication['valid_page_count'] = $this->_validPageCount($id, $userInfo['id']);
		$this->form->setDefaultAll($publication);
		$this->form->set('publication', $publication);

		$this->form->setParameterForm('id');

		$this->form->setSp('commentFlagOptions', Util::arrayToTextValue(AppConst::$commentFlag));

		$this->form->set('htitle', $publication['title']);
		$this->setTitle($this->form->get('htitle'), 'マイページ');

		return $this->forward('user/mydesk/user_mydesk_publish', APP_CONST_USER_FRAME);
	}

	/**
	 * 公開設定保存処理
	 */
	public function publising()
	{
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$id = $this->form->getInt('id');
		if (empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		try {

			$this->db->beginTransaction();

			// 表紙保存
			if ($this->form->get('cover_path')!='' && $this->form->get('cover_s_path')!='') {
				$img_dir = str_replace('[user_id]', $userInfo['id'], APP_CONST_COVER_IMAGE_DIR);
				$tmp_dir = APP_CONST_COVER_IMAGE_TMP_DIR;
				@mkdir($img_dir, 0705, true);
				$cover_path = $this->form->get('cover_path');
				if (file_exists($tmp_dir.'/'.$cover_path)) {
					if (file_exists($img_dir.'/'.$cover_path)) @unlink($img_dir.'/'.$cover_path);
					if (@rename($tmp_dir.'/'.$cover_path, $img_dir.'/'.$cover_path)===false) {
						throw new SpException('表紙コピーに失敗(1)：'.$cover_path);
					}
				}
				$cover_s_path = $this->form->get('cover_s_path');
				if (file_exists($tmp_dir.'/'.$cover_s_path)) {
					if (file_exists($img_dir.'/'.$cover_s_path)) @unlink($img_dir.'/'.$cover_s_path);
					if (@rename($tmp_dir.'/'.$cover_s_path, $img_dir.'/'.$cover_s_path)===false) {
						throw new SpException('表紙コピーに失敗(2)：'.$cover_s_path);
					}
				}
			}

			$dao = new PublicationsDao($this->db);
			//$dao->addValue(PublicationsDao::COL_STATUS, $this->form->getInt('status', 0));
			//$dao->addValue(PublicationsDao::COL_LATEST_VERSION, $latest_version);
			$dao->addValue(PublicationsDao::COL_EPUB_FLAG, $this->form->getInt('epub_flag'));
			$dao->addValue(PublicationsDao::COL_COMMENT_FLAG, $this->form->getInt('comment_flag'));
			$dao->addValueStr(PublicationsDao::COL_COVER_FILE, $this->form->get('cover_file'));
			$dao->addValueStr(PublicationsDao::COL_COVER_PATH, $this->form->get('cover_path'));
			$dao->addValue(PublicationsDao::COL_COVER_SIZE, $this->form->get('cover_size'));
			$dao->addValueStr(PublicationsDao::COL_COVER_S_FILE, $this->form->get('cover_s_file'));
			$dao->addValueStr(PublicationsDao::COL_COVER_S_PATH, $this->form->get('cover_s_path'));
			$dao->addValue(PublicationsDao::COL_COVER_S_SIZE, $this->form->get('cover_s_size'));
			$dao->addValue(PublicationsDao::COL_PUBLISH_MODIFY_FLAG, 1);
			$dao->addValue(PublicationsDao::COL_LASTUPDATE, Dao::DATE_NOW);
			$dao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
			$dao->addWhere(PublicationsDao::COL_USER_ID, $userInfo['id']);
			$dao->doUpdate();

			$this->db->commit();

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
			return $this->publish();
		}

		return $this->resp->sendRedirect('/user/mydesk/publish?id='.$id.'&save=true');
	}

	/**
	 * 画像管理画面
	 */
	public function image()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();

		$id = $this->form->getInt('id');
		if (empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$publicationsDao = new PublicationsDao($this->db);
		$publication = $publicationsDao->getItem($id, $userInfo['id']);
		if (count($publication) == 0) return $this->notfoundPage();
		// 有効なページ数
		$publication['valid_page_count'] = $this->_validPageCount($id, $userInfo['id']);
		$this->form->setDefaultAll($publication);
		$this->form->set('publication', $publication);

		// 画像データの読み込み
		$this->_setImageData($id, $userInfo['id']);
		// zipとimageの大きい方に合わせる
		$this->form->setParameter('MAX_FILE_SIZE', APP_CONST_PUBLICATION_IAMGE_ZIP_MAX_SIZE);
		$this->form->setParameterForm('id');

		$this->form->set('htitle', $publication['title']);
		$this->setTitle($this->form->get('htitle'), 'マイページ');

		return $this->forward('user/mydesk/user_mydesk_image', APP_CONST_USER_FRAME);
	}

	public function imageupload()
	{
		$id = $this->form->getInt('id');
		if ($this->form->isGetMethod() || $this->checkUserAuth() === false || empty($id)) return $this->notfoundPage();

		if ($this->_uploadImage($id) === false) {
			$this->form->set('errors', $this->form->getValidateErrors());
			return $this->image();
		}

		$ret = '&save=true';
		if ($this->form->get('filetot')!='') $ret .= '&filetot='.$this->form->get('filetot');
		if ($this->form->get('filecnt')!='') $ret .= '&filecnt='.$this->form->get('filecnt');

		return $this->resp->sendRedirect('/user/mydesk/image?id='.$id.$ret);
	}

	/**
	 * 画像の削除
	 */
	public function imagedelete()
	{
		$id = $this->form->getInt('id');
		$image_ids = $this->form->get('image_ids');
		if ($this->form->isGetMethod() || $this->checkUserAuth() === false || empty($id) || empty($image_ids)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$ret = '';

		if (count($image_ids) > 0) {
			$publicationImagesDao = new PublicationImagesDao($this->db);
			foreach ($image_ids as $image_id) {
				$item = $publicationImagesDao->getItem($image_id, $userInfo['id']);
				if (count($item)>0) {
					$image_path = $item['image_path'];
					$image_dir = str_replace(array('[user_id]', '[publication_id]'), array($userInfo['id'], $item['publication_id']), APP_CONST_PUBLICATION_IMAGE_DIR);
					if (file_exists($image_dir.'/'.$image_path)) @unlink($image_dir.'/'.$image_path);
					$publicationImagesDao->reset();
					$publicationImagesDao->delete($image_id, $userInfo['id']);
					$publicationImagesDao->reset();
					$ret = '&delete=true';
				}
			}
		}

		return $this->resp->sendRedirect('/user/mydesk/image?id='.$id.$ret);
	}

	/**
	 * 公開処理
	 */
	public function gopublic()
	{
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$id = $this->form->getInt('id');
		if (empty($id)) return $this->notfoundPage();
		$loc = ($this->form->get('loc')=='') ? '/user/mydesk/' : $this->form->get('loc').'?id='.$id.'&public=true';
		// 1以上なら改訂
		$version = $this->form->getInt('version', 0);
		// そのまま保存
		$update = $this->form->getInt('update', 0);

		$userInfo = $this->getUserInfo();

		$publicationsDao = new PublicationsDao($this->db);
		$publication = $publicationsDao->getItem($id, $userInfo['id']);
		if (count($publication) == 0) return $this->notfoundPage();
		$publicationsDao->reset();

		$publicationPagesDao = new PublicationPagesDao($this->db);
		$page_list = $publicationPagesDao->getItemList($id, $userInfo['id'], PublicationPagesDao::STATUS_FINISH);

		$book_id = $publication[PublicationsDao::COL_BOOK_ID];
		$publish_date = date('Y-m-d');

		if ($version>0) {
			// 改訂
			$publication[PublicationsDao::COL_VERSION] = $publication[PublicationsDao::COL_VERSION] + 1;
		}

		try {

			$this->db->beginTransaction();

			// 公開本へコピー
			$booksDao = new BooksDao($this->db);
			$booksDao->copyFromPublication($publication);
			$booksDao->addValue(BooksDao::COL_STATUS, BooksDao::STATUS_PUBLIC);
			$booksDao->addValue(BooksDao::COL_LASTUPDATE, Dao::DATE_NOW);

			$bookPagesDao = new BookPagesDao($this->db);
			$book_page_num = 0;

			if ($book_id>0) {
				$booksDao->addWhere(BooksDao::COL_BOOK_ID, $book_id);
				$booksDao->doUpdate();
				// 公開中のページ数
				$book_page_num = $bookPagesDao->getSelectId('SELECT COUNT(page_id) as cnt', 'book_id='.$book_id);
				$bookPagesDao->reset();
			} else {
				// book_idとpublication_idは同一にした
				$booksDao->addValue(BooksDao::COL_BOOK_ID, $id);
				$booksDao->addValueStr(BooksDao::COL_PUBLISH_DATE, $publish_date);
				$booksDao->addValue(BooksDao::COL_CREATEDATE, Dao::DATE_NOW);
				$booksDao->doInsert();
				$book_id = $id;

				// 集計データ
				$bookRanksDao = new BookRanksDao($this->db);
				$bookRanksDao->addValue(BookRanksDao::COL_BOOK_ID, $book_id);
				$bookRanksDao->addValue(BookRanksDao::COL_USER_ID, $userInfo['id']);
				$bookRanksDao->addValue(BookRanksDao::COL_STATUS, BooksDao::STATUS_PUBLIC);
				$bookRanksDao->doInsert();

				$publicationsDao->addValue(PublicationsDao::COL_BOOK_ID, $book_id);
				$publicationsDao->addValueStr(PublicationsDao::COL_PUBLISH_DATE, $publish_date);
			}

			// 公開ページ登録
			$page_order = 0;
			if (count($page_list)>0) {
				foreach ($page_list as $page_order => $page) {
					$bookPagesDao->copyFormPublicationPage(&$page);
					if ($page_order < $book_page_num) {
						$bookPagesDao->addWhere(BookPagesDao::COL_PAGE_ORDER, $page_order);
						$bookPagesDao->addWhere(BookPagesDao::COL_BOOK_ID, $book_id);
						$bookPagesDao->addWhere(BookPagesDao::COL_USER_ID, $userInfo['id']);
						$bookPagesDao->doUpdate();
					} else {
						$bookPagesDao->addValue(BookPagesDao::COL_PAGE_ORDER, $page_order);
						$bookPagesDao->addValue(BookPagesDao::COL_BOOK_ID, $book_id);
						$bookPagesDao->doInsert();
					}
					$bookPagesDao->reset();
				}
			}
			// 更新時にページ数が減っている場合
			$page_order++;
			if ($book_page_num>0 && $page_order<$book_page_num) {
				for ($i=$page_order; $i<$book_page_num; $i++) {
					$bookPagesDao->delete($book_id, $userInfo['id'], $i);
					$bookPagesDao->reset();
				}
			}

			$publicationsDao->addValue(PublicationsDao::COL_STATUS, PublicationsDao::STATUS_PUBLIC);
			$publicationsDao->addValue(PublicationsDao::COL_VERSION, $publication[PublicationsDao::COL_VERSION]);
			$publicationsDao->addValue(PublicationsDao::COL_PUBLISH_MODIFY_FLAG, 0);
			$publicationsDao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
			$publicationsDao->addWhere(PublicationsDao::COL_USER_ID, $userInfo['id']);
			$publicationsDao->doUpdate();

			// 改訂履歴
			if ($version>0) {
				$bookRevisionsDao = new BookRevisionsDao($this->db);
				$bookRevisionsDao->addValue(BookRevisionsDao::COL_USER_ID, $userInfo['id']);
				$bookRevisionsDao->addValue(BookRevisionsDao::COL_BOOK_ID, $book_id);
				$bookRevisionsDao->addValue(BookRevisionsDao::COL_VERSION, $publication[PublicationsDao::COL_VERSION]);
				$bookRevisionsDao->addValue(BookRevisionsDao::COL_REVISION_DATE, Dao::DATE_NOW);
				$bookRevisionsDao->addValueStr(BookRevisionsDao::COL_REVISION_BODY, $this->form->get('revision_body'));
				$bookRevisionsDao->addValue(BookRevisionsDao::COL_CREATEDATE, Dao::DATE_NOW);
				$bookRevisionsDao->addValue(BookRevisionsDao::COL_LASTUPDATE, Dao::DATE_NOW);
				$bookRevisionsDao->doInsert();
			}

//			// EPUB作成
//			if ($publication['epub_flag']>0) {
//				Sp::import('EpubConvert', 'libs', true);
//				$publicationImagesDao = new PublicationImagesDao($this->db);
//				$images = $publicationImagesDao->getList($id, $userInfo['id']);
//				$epubConvert = new EpubConvert($this->db, $book_id, constant('app_site_url'), $images);
//				$epubConvert->create(APP_CONST_BOOK_EPUB_DIR);
//				$this->logger->debug('EPUB: create to book-'.$book_id.'.epub');
//			}

			$this->db->commit();

			// そのまま保存時は最後のメッセージが違うだけ
			if ($update>0) {
				$loc = str_replace('&public=true', '&update=true', $loc);
			}

		} catch (SpException $e) {
			$this->logger->exception($e);
			$this->db->rollback();
			$loc = str_replace('&public=true', '&public_err=true', $loc);
		}

		return $this->resp->sendRedirect($loc);
	}

	/**
	 * 非公開処理
	 */
	public function goclosed()
	{
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$id = $this->form->getInt('id');
		if (empty($id)) return $this->notfoundPage();
		$loc = ($this->form->get('loc')=='') ? '/user/mydesk/' : $this->form->get('loc').'?id='.$id.'&closed=true';

		$userInfo = $this->getUserInfo();

		$publicationsDao = new PublicationsDao($this->db);
		$publication = $publicationsDao->getItem($id, $userInfo['id']);
		if (count($publication) == 0) return $this->notfoundPage();
		$publicationsDao->reset();

		try {

			$this->db->beginTransaction();

			$publicationsDao->addValue(PublicationsDao::COL_STATUS, PublicationsDao::STATUS_CLOSED);
			//$publicationsDao->addValue(PublicationsDao::COL_LASTUPDATE, Dao::DATE_NOW);
			$publicationsDao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
			$publicationsDao->addWhere(PublicationsDao::COL_USER_ID, $userInfo['id']);
			$publicationsDao->doUpdate();

			$booksDao = new BooksDao($this->db);
			$booksDao->addValue(BooksDao::COL_STATUS, BooksDao::STATUS_CLOSED);
			$booksDao->addWhere(BooksDao::COL_BOOK_ID, $id);
			$booksDao->addWhere(BooksDao::COL_USER_ID, $userInfo['id']);
			$booksDao->doUpdate();

			$this->db->commit();

		} catch (SpException $e) {
			$this->logger->exception($e);
			$this->db->rollback();
			$loc = str_replace('&closed=true', '&closed_err=true', $loc);
		}

		return $this->resp->sendRedirect($loc);
	}

	/**
	 * マイデスクの開設処理
	 */
	public function setup()
	{
		$loc = $this->form->get('loc');
		if ($this->checkUserAuth() === false || Util::startsWith($loc, '/user/') === false) return $this->notfoundPage();
		if ($this->checkUserAuth() === false) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		if ($userInfo['use_mydesk'] == 0) {

			$usersDao = new UsersDao($this->db);
			$usersDao->addValue(UsersDao::COL_USE_MYDESK, 1);
			$usersDao->addWhere(UsersDao::COL_USER_ID, $userInfo['id']);
			$usersDao->doUpdate();

			$update_info = array('use_mydesk' => 1);
			$this->updateUserInfo($update_info);

			$loc .= (strpos($loc, '?')===false) ? '?setup=true' : '&setup=true';
		}

		return $this->resp->sendRedirect($loc);
	}

//	public function publising()
//	{
//		$userInfo = $this->getUserInfo();
//
////		$contents = '';
////		$page_contents = array();
//		$char_length = 0;
////		$cut_flag = false;
//
//		$page_titles = $this->form->get('page_title');
//		foreach ($page_titles as $num => $page_title) {
//			$buf = preg_replace('/[　\s\r\n\t]+/u', '', strip_tags($this->form->get('contents-' . $num)));
////			$page_contents[$num] = mb_substr($buf, 0, APP_CONST_PUBLICATION_CONTENTS_SIZE);
//////			if ($char_length>APP_CONST_PUBLICATION_CONTENTS_SIZE) {
//////				$contents = mb_substr($contetns, 0, APP_CONST_PUBLICATION_CONTENTS_SIZE);
//////				$cut_flag = true;
//////			} else if ($cut_flag === false) {
//////				$contents .= $buf;
//////			}
//			$char_length += mb_strlen($buf);
////			$contents .= $buf;
//		}
////		if ($char_length > APP_CONST_PUBLICATION_CONTENTS_SIZE) {
////			$contents = mb_substr($contetns, 0, APP_CONST_PUBLICATION_CONTENTS_SIZE);
////		}
//
//		$id = $this->form->getInt('id', 0);
//		$publication_key = $this->form->get('publication_key');
//		$latest_version = 1;
//
////		if ($contents == '<br />') $contents = '';
//
//		try {
//
//			$this->db->beginTransaction();
//
//			$dao = new PublicationsDao($this->db);
//			$dao->addValue(PublicationsDao::COL_STATUS, $this->form->getInt('status', 0));
//			$dao->addValue(PublicationsDao::COL_LATEST_VERSION, $latest_version);
//			$dao->addValue(PublicationsDao::COL_CATEGORY_ID, $this->form->get('category_id'));
//			$dao->addValueStr(PublicationsDao::COL_TITLE, $this->form->get('title'));
//			$dao->addValueStr(PublicationsDao::COL_SUBTITLE, $this->form->get('subtitle'));
//			$dao->addValueStr(PublicationsDao::COL_DESCRIPTION, $this->form->get('description'));
////			$dao->addValueStr(PublicationsDao::COL_AUTHOR, $this->form->get('author'));
////			$dao->addValueStr(PublicationsDao::COL_PUBLISHER, $this->form->get('publisher'));
////			$dao->addValueStr(PublicationsDao::COL_KEYWORDS, $this->form->get('keywords'));
////			$dao->addValueStr(PublicationsDao::COL_CONTENTS, $contents);
//			$dao->addValue(PublicationsDao::COL_EPUB_FLAG, $this->form->getInt('epub_flag'));
//			$dao->addValue(PublicationsDao::COL_COMMENT_FLAG, $this->form->getInt('comment_flag'));
//			$dao->addValue(PublicationsDao::COL_CHAR_LENGTH, $char_length);
//			$dao->addValueStr(PublicationsDao::COL_PUBLICATION_KEY, $publication_key);
//			$dao->addValue(PublicationsDao::COL_LASTUPDATE, Dao::DATE_NOW);
//
//			$page_total = 0;
//
//			if ($id > 0) {
//				$dao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
//				$dao->addWhere(PublicationsDao::COL_USER_ID, $userInfo['id']);
//				$dao->doUpdate();
//				$pagesDao = new PublicationPagesDao($this->db);
//				$pagesDao->addSelectCount(PublicationPagesDao::COL_PAGE_ID, 'total');
//				$pagesDao->addWhere(PublicationPagesDao::COL_PUBLICATION_ID, $id);
//				$pagesDao->addWhere(PublicationPagesDao::COL_USER_ID, $userInfo['id']);
//				$page_total = $pagesDao->selectId();
//			} else {
//				$dao->addValue(PublicationsDao::COL_USER_ID, $userInfo['id']);
//				$dao->addValue(PublicationsDao::COL_COPYRIGHT_FLAG, $this->form->get('copyright_flag'));
//				$dao->addValue(PublicationsDao::COL_COPYRIGHT_DATE, Dao::DATE_NOW);
//				$dao->addValue(PublicationsDao::COL_CREATEDATE, Dao::DATE_NOW);
//				$dao->doInsert();
//				$id = $dao->getLastInsertId();
//
//				// 集計データ
//				$publicationInfosDao = new PublicationInfosDao($this->db);
//				$publicationInfosDao->addValue(PublicationInfosDao::COL_PUBLICATION_ID, $id);
//				$publicationInfosDao->addValue(PublicationInfosDao::COL_USER_ID, $userInfo['id']);
//				$publicationInfosDao->addValue(PublicationInfosDao::COL_STATUS, $this->form->getInt('status', 0));
//				$publicationInfosDao->addValue(PublicationInfosDao::COL_LATEST_VERSION, $latest_version);
//				$publicationInfosDao->doInsert();
//			}
//
//			$pagesDao = new PublicationPagesDao($this->db);
//			foreach ($page_titles as $num => $page_title) {
//				$pagesDao->addValueStr(PublicationPagesDao::COL_PAGE_TITLE, $page_title);
////				$pagesDao->addValueStr(PublicationPagesDao::COL_PAGE_CONTENTS, $page_contents[$num]);
//				$pagesDao->addValueStr(PublicationPagesDao::COL_PAGE_CONTENTS, $this->form->get('contents-' . $num));
//				$pagesDao->addValue(PublicationPagesDao::COL_LASTUPDATE, Dao::DATE_NOW);
//				$pagesDao->addWhere(PublicationPagesDao::COL_PUBLICATION_ID, $id);
//				$pagesDao->addWhere(PublicationPagesDao::COL_USER_ID, $userInfo['id']);
//				$pagesDao->addWhere(PublicationPagesDao::COL_PAGE_ORDER, $num);
//				if ($pagesDao->doUpdate() == 0) {
//					$pagesDao->addValue(PublicationPagesDao::COL_PUBLICATION_ID, $id);
//					$pagesDao->addValue(PublicationPagesDao::COL_USER_ID, $userInfo['id']);
//					$pagesDao->addValue(PublicationPagesDao::COL_PAGE_ORDER, $num);
//					$pagesDao->addValue(PublicationPagesDao::COL_CREATEDATE, Dao::DATE_NOW);
//					$pagesDao->doInsert();
//				}
//				// ファイルへの書き込み
////				PublicationContents::putFile(&$this->form, $id, $userInfo['id'], $num);
//				$pagesDao->reset();
//			}
//			for ($i=$num+1; $i<$page_total; $i++) {
//				$pagesDao->addWhere(PublicationPagesDao::COL_PUBLICATION_ID, $id);
//				$pagesDao->addWhere(PublicationPagesDao::COL_USER_ID, $userInfo['id']);
//				$pagesDao->addWhere(PublicationPagesDao::COL_PAGE_ORDER, $i);
//				$pagesDao->doDelete();
//				$pagesDao->reset();
//			}
//
//			// 表紙保存
//			if ($this->form->get('cover_path')!='' && $this->form->get('cover_s_path')!='') {
//				$img_dir = str_replace('[user_id]', $userInfo['id'], APP_CONST_COVER_IMAGE_DIR);
//				$tmp_dir = APP_CONST_COVER_IMAGE_TMP_DIR;
//				@mkdir($img_dir, 0705, true);
//				$cover_path = $this->form->get('cover_path');
//				if (file_exists($tmp_dir.'/'.$cover_path)) {
//					if (file_exists($img_dir.'/'.$cover_path)) @unlink($img_dir.'/'.$cover_path);
//					if (@rename($tmp_dir.'/'.$cover_path, $img_dir.'/'.$cover_path)===false) {
//						throw new SpException('表紙コピーに失敗(1)：'.$cover_path);
//					}
//				}
//				$cover_s_path = $this->form->get('cover_s_path');
//				if (file_exists($tmp_dir.'/'.$cover_s_path)) {
//					if (file_exists($img_dir.'/'.$cover_s_path)) @unlink($img_dir.'/'.$cover_s_path);
//					if (@rename($tmp_dir.'/'.$cover_s_path, $img_dir.'/'.$cover_s_path)===false) {
//						throw new SpException('表紙コピーに失敗(2)：'.$cover_s_path);
//					}
//				}
//			}
//
//			// 表紙登録
//			$dao->reset();
//			$dao->addValueStr(PublicationsDao::COL_COVER_FILE, $this->form->get('cover_file'));
//			$dao->addValueStr(PublicationsDao::COL_COVER_PATH, $this->form->get('cover_path'));
//			$dao->addValue(PublicationsDao::COL_COVER_SIZE, $this->form->get('cover_size'));
//			$dao->addValueStr(PublicationsDao::COL_COVER_S_FILE, $this->form->get('cover_s_file'));
//			$dao->addValueStr(PublicationsDao::COL_COVER_S_PATH, $this->form->get('cover_s_path'));
//			$dao->addValue(PublicationsDao::COL_COVER_S_SIZE, $this->form->get('cover_s_size'));
//			$dao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
//			$dao->doUpdate();
//
//			// EPUB作成
//			if ($this->form->getInt('epub_flag')>0) {
//				Sp::import('EpubConvert', 'libs', true);
//				$epubConvert = new EpubConvert($this->db, $id, constant('app_site_url'));
//				$epubConvert->create(APP_CONST_TMP_EPUB_DIR);
//			}
//
//			$publicationTempsDao = new PublicationTempsDao($this->db);
//			$publicationTempsDao->delete($publication_key, $userInfo['id']);
//
//			$this->db->commit();
//
//		} catch (SpException $e) {
//			$this->db->rollback();
//			$this->logger->exception($e);
////			if ($id > 0) UtilFile::removeDir(PublicationContents::getDataDir($id, $userInfo['id']));
//			if ($id > 0) UtilFile::removeDir(APP_CONST_TMP_EPUB_DIR.'/'.$id);
//			return $this->create();
//		}
//
//		return $this->resp->sendRedirect('/user/publication/');
//	}

	/**
	 * 表紙アップロード
	 */
	public function cover()
	{
		if ($this->checkUserAuth() === false) return $this->loginPage();
		$userInfo = $this->getUserInfo();

		if ($this->form->isPostMethod()) {
			@mkdir(APP_CONST_COVER_IMAGE_TMP_DIR, 0705, true);
			// 入力チェック
			if ($this->_validateCover() === false) {
				$this->form->set('errors', $this->form->getValidateErrors());
			// tmpコピー
			} else if ($this->copyFileTemp('cover', APP_CONST_COVER_IMAGE_TMP_DIR, '', $userInfo['id'].'_') === false) {
				$this->form->set('errors', $this->form->getValidateErrors());
			} else {
				// リサイズ

				// small版作成
				$this->form->set('cover_s_file', $this->form->get('cover_file'));
				//$this->form->set('cover_s_path', str_replace('.', '_small.', $this->form->get('cover_path')));
				$this->form->set('cover_s_path', preg_replace('/\.[a-z]+$/i', '_small.jpg', $this->form->get('cover_path')));
				$cover_path = APP_CONST_COVER_IMAGE_TMP_DIR.'/'.$this->form->get('cover_path');
				$small_path = APP_CONST_COVER_IMAGE_TMP_DIR.'/'.$this->form->get('cover_s_path');
				$errmsg = '';
				if (Util::resizeImage($cover_path, $small_path, APP_CONST_COVER_IAMGE_S_WIDTH, APP_CONST_COVER_IAMGE_S_HEIGHT, 'jpg', &$errmsg) === false) {
					$this->logger->error($errmsg);
				} else {
					$this->form->set('cover_s_size', filesize($small_path));
				}
			}
		}

		$cover_arr = array(
			'cover_file' => $this->form->get('cover_file'),
			'cover_path' => $this->form->get('cover_path'),
			'cover_size' => $this->form->get('cover_size'),
			'cover_s_file' => $this->form->get('cover_s_file'),
			'cover_s_path' => $this->form->get('cover_s_path'),
			'cover_s_size' => $this->form->get('cover_s_size')
		);
		$this->form->set('cover', $cover_arr);

		$this->form->set('htitle', '本の表紙に使う画像を選ぶ');
		$this->form->set('popup_title', $this->form->get('htitle'));
		$this->setTitle($this->form->get('htitle'));

		return $this->forward('user/mydesk/user_mydesk_cover', APP_CONST_POPUP_FRAME);
	}

	/**
	 * 画像の選択＆アップロード
	 */
	public function upload_image()
	{
		$id = $this->form->getInt('id');
		if ($this->checkUserAuth() === false || empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		if ($this->form->isPostMethod()) {
			if ($this->_uploadImage($id) === false) {
				$this->form->set('errors', $this->form->getValidateErrors());
			} else {
				$ret = '&save=true';
				if ($this->form->get('filetot')!='') $ret .= '&filetot='.$this->form->get('filetot');
				if ($this->form->get('filecnt')!='') $ret .= '&filecnt='.$this->form->get('filecnt');

				return $this->resp->sendRedirect('/user/mydesk/upload_image?id='.$id.$ret);
			}
		}

		// 画像データの読み込み
		$this->_setImageData($id, $userInfo['id']);

		$layout_options = array(0=>'回り込みなし', 1=>'画像を左寄せ', 2=>'画像を右寄せ');
		$this->form->setSp('layoutOptions', Util::arrayToTextValue($layout_options, 0));

		// zipとimageの大きい方に合わせる
		$this->form->setParameter('MAX_FILE_SIZE', APP_CONST_PUBLICATION_IAMGE_ZIP_MAX_SIZE);
		$this->form->setParameterForm('id');

		$this->form->set('htitle', '画像フォルダ');
		$this->form->set('popup_title', $this->form->get('htitle'));
		$this->setTitle($this->form->get('htitle'));

		return $this->forward('user/mydesk/user_mydesk_upload_image', APP_CONST_POPUP_FRAME);
	}

	/**
	 * 画像の削除
	 */
	public function delete_image()
	{
		$id = $this->form->getInt('id');
		$image_id = $this->form->getInt('image_id');
		if ($this->form->isGetMethod() || $this->checkUserAuth() === false || empty($id) || empty($image_id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$publicationImagesDao = new PublicationImagesDao($this->db);
		$item = $publicationImagesDao->getItem($image_id, $userInfo['id']);
		$ret = '';
		if (count($item)>0) {
			$image_path = $item['image_path'];
			$image_dir = str_replace(array('[user_id]', '[publication_id]'), array($userInfo['id'], $item['publication_id']), APP_CONST_PUBLICATION_IMAGE_DIR);
			if (file_exists($image_dir.'/'.$image_path)) @unlink($image_dir.'/'.$image_path);
			$publicationImagesDao->reset();
			$publicationImagesDao->delete($image_id, $userInfo['id']);
			$ret = '&success=true';
		}

		return $this->resp->sendRedirect('/user/mydesk/upload_image?id='.$id.$ret);
	}

	public function delete_book()
	{
		$id = $this->form->getInt('id');
		if ($this->checkUserAuth() === false || empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		try {

			$this->db->beginTransaction();

			$dao = new PublicationsDao($this->db);
			$item = $dao->getItem($id, $userInfo['id']);
			$dao->reset();
			$dao->delete($id, $userInfo['id']);

			$dao = new PublicationPagesDao($this->db);
			$dao->delete($id, $userInfo['id']);

			$dao = new PublicationImagesDao($this->db);
			$dao->deleteAll($id, $userInfo['id']);

			$dao = new BooksDao($this->db);
			$dao->delete($id, $userInfo['id']);

			$dao = new BookPagesDao($this->db);
			$dao->delete($id, $userInfo['id']);

			$dao = new BookRanksDao($this->db);
			$dao->delete($id, $userInfo['id']);

			$dao = new BookRevisionsDao($this->db);
			$dao->delete($id, $userInfo['id']);

			$image_dir = str_replace(array('[user_id]', '[publication_id]'), array($userInfo['id'], $id), APP_CONST_PUBLICATION_IMAGE_DIR);
			UtilFile::removeDir($image_dir);
			$cover_dir = str_replace('[user_id]', $userInfo['id'], APP_CONST_COVER_IMAGE_DIR);
			if ($item['cover_path']!='' && file_exists($cover_dir.'/'.$item['cover_path'])) @unlink($cover_dir.'/'.$item['cover_path']);
			if ($item['cover_s_path']!='' && file_exists($cover_dir.'/'.$item['cover_s_path'])) @unlink($cover_dir.'/'.$item['cover_s_path']);

			$this->db->commit();

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
		}

		return $this->resp->sendRedirect('/user/mydesk/?delete=true');
	}

	public function delete_page()
	{
		$id = $this->form->getInt('id');
		$page_id = $this->form->getInt('page_id');
		if ($this->checkUserAuth() === false || empty($id) || empty($page_id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$dao = new PublicationPagesDao($this->db);
		$dao->deleteItem($page_id, $userInfo['id']);

		$dao = new PublicationsDao($this->db);
		$dao->addValue(PublicationsDao::COL_PUBLISH_MODIFY_FLAG, 1);
		$dao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
		$dao->addWhere(PublicationsDao::COL_USER_ID, $userInfo['id']);
		$dao->doUpdate();

		return $this->resp->sendRedirect('/user/mydesk/page?id='.$id.'&delete=true');
	}

	/**
	 * 自動一時保存
	 */
	public function auto_save_api()
	{
		$id = $this->form->getInt('id');
		$publication_key = $this->form->get('publication_key');
		if ($this->form->isGetMethod() || $this->checkUserAuth() === false || empty($id) || empty($publication_key)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();
		$status = self::AJAX_STATUS_ERROR;
		$errormsg = '';
		$nowdate = date('Y-m-d H:i:s');

		try {
			$publicationPageTempsDao = new PublicationPageTempsDao($this->db);
			$publicationPageTempsDao->addValue(PublicationPageTempsDao::COL_STATUS, $this->form->getInt('status'));
//			$publicationPageTempsDao->addValue(PublicationPageTempsDao::COL_TYPE, 0);
			$publicationPageTempsDao->addValueStr(PublicationPageTempsDao::COL_PAGE_TITLE, $this->form->get('page_title'));
			$publicationPageTempsDao->addValueStr(PublicationPageTempsDao::COL_PAGE_CONTENTS, $this->form->get('page_contents'));
			$publicationPageTempsDao->addValueStr(PublicationPageTempsDao::COL_LASTUPDATE, $nowdate);
			$publicationPageTempsDao->addWhereStr(PublicationPageTempsDao::COL_PAGE_TEMP_KEY, $publication_key);
			$publicationPageTempsDao->addWhereStr(PublicationPageTempsDao::COL_PUBLICATION_ID, $id);
			$publicationPageTempsDao->addWhereStr(PublicationPageTempsDao::COL_USER_ID, $userInfo['id']);
			if ($publicationPageTempsDao->doUpdate() < 1) {
				$publicationPageTempsDao->addValueStr(PublicationPageTempsDao::COL_PAGE_TEMP_KEY, $publication_key);
				$publicationPageTempsDao->addValue(PublicationPageTempsDao::COL_PUBLICATION_ID, $id);
				$publicationPageTempsDao->addValue(PublicationPageTempsDao::COL_USER_ID, $userInfo['id']);
				$publicationPageTempsDao->addValueStr(PublicationPageTempsDao::COL_CREATEDATE, $nowdate);
				$publicationPageTempsDao->doInsert();
			}
			$status = self::AJAX_STATUS_SUCCESS;
		} catch (SpException $e) {
			$errormsg = $e->getStackTrace();
		}

		$data = array('status'=>$status);
		$data['nowdate'] = substr($nowdate, 0, 16);
		if ($errormsg != '') $data['errormsg'] = $errormsg;
		$encode = Util::jsonEncode($data);

		$this->form->set('data', $encode);

		$this->resp->setContentType(SpResponse::CTYPE_JSON);

		return $this->forward('json', APP_CONST_EMPTY_FRAME);
	}

	/**
	 * ページの並び替え(ajax)
	 */
	public function sort_page_api()
	{
		$id = $this->form->getInt('id');
		if ($this->form->isGetMethod() || $this->checkUserAuth() === false || empty($id)) return $this->notfoundPage();

		$userInfo = $this->getUserInfo();

		$ids = explode(',', $this->form->get('ids'));

		$data = array();
		$status = self::AJAX_STATUS_SUCCESS;

		try {

			if (count($ids)>0) {

				$this->db->beginTransaction();

				$pagesDao = new PublicationPagesDao($this->db);
				$page_order = 0;
				foreach ($ids as $page_id) {
					$page_id = (int)$page_id;
					if (empty($page_id)) continue;
					$pagesDao->addValue(PublicationPagesDao::COL_PAGE_ORDER, $page_order);
					$pagesDao->addWhere(PublicationPagesDao::COL_PAGE_ID, $page_id);
					$pagesDao->addWhere(PublicationPagesDao::COL_USER_ID, $userInfo['id']);
					$pagesDao->doUpdate();
					$pagesDao->reset();
					$page_order++;
				}

				$dao = new PublicationsDao($this->db);
				$dao->addValue(PublicationsDao::COL_PUBLISH_MODIFY_FLAG, 1);
				$dao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
				$dao->addWhere(PublicationsDao::COL_USER_ID, $userInfo['id']);
				$dao->addWhere(PublicationsDao::COL_PUBLISH_MODIFY_FLAG, 0);
				$data['modify_flag'] = $dao->doUpdate();

				$this->db->commit();
			}

		} catch (SpException $e) {
			$this->db->rollback();
			$this->logger->exception($e);
			$status = self::AJAX_STATUS_ERROR;
			$data['message'] = $e->getMessage();
		}

		$data['status'] = $status;
		$this->form->set('data', Util::jsonEncode($data));

		$this->resp->setContentType(SpResponse::CTYPE_JSON);
		return $this->forward('json', APP_CONST_EMPTY_FRAME);
	}

//	public function ajax_validate()
//	{
//		if ($this->form->isGetMethod()) return $this->ajaxStatusError('不正なアクセスです。');
//
//		$tab = $this->form->get('tab');
//		$data = array();
//		$status = self::AJAX_SUCCESS;
//
//		switch ($tab) {
//			case 'tab-01':
//				$this->form->validate($this->form->getValidates(0));
//				break;
//			case 'tab-02':
//				$this->form->validate($this->form->getValidates(1));
//				break;
//			case 'tab-03':
//				$this->form->validate($this->form->getValidates(2));
//				break;
//		}
//
//		if ($this->form->isValidateErrors()) {
//			$data['errors'] = $this->form->getValidateErrors();
//			$status = self::AJAX_ERROR;
//		}
//
//		$data['status'] = $status;
//		$encode = Util::jsonEncode($data);
//
//		$this->form->set('data', $encode);
//
//		$this->resp->setContentType(SpResponse::CTYPE_JSON);
//
//		return $this->forward('json', APP_CONST_EMPTY_FRAME);
//	}
//
//	public function ajax_save()
//	{
//		if ($this->form->isGetMethod()) return $this->ajaxStatusError('不正なアクセスです。');
//
//		$userInfo = $this->getUserInfo();
//
//		$mode = $this->form->get('mode');
//		$publication_key = $this->form->get('publication_key');
//
//		if ($mode == 'auto') {
//			// validation
//		}
//
//		$title = $this->form->get('title', APP_CONST_PUB_BLANK_NAME);
//		$subtitle = $this->form->get('subtitle');
//		$description = $this->form->get('description');
//		$author = $this->form->get('author');
//		$publisher = $this->form->get('publisher');
//		$category_id = $this->form->get('category_id');
//		$keywords = $this->form->get('keywords');
//		$contents = $this->form->get('contents');
//		if ($contents == '<br />') $contents = '';
//		//$cover_image = $this->form->get('cover_image');
//		$contents_use = $this->form->getInt('contents_use');
//		$contents_type = $this->form->getInt('contents_type');
//		$profile_use = $this->form->getInt('profile_use');
//		$profile_img_type = $this->form->getInt('profile_img_type');
//		$profile_body_type = $this->form->getInt('profile_body_type');
//		$profile_body = $this->form->get('profile_body');
//
//		if ($mode == 'auto') {
//			$title = mb_substr($title, 0, 50);
//			$subtitle = mb_substr($subtitle, 0, 50);
//			$author = mb_substr($author, 0, 50);
//			$publisher = mb_substr($publisher, 0, 50);
//			$keywords = mb_substr($keywords, 0, 100);
//		}
//
//		$status = self::AJAX_ERROR;
//		$errormsg = '';
//
//		try {
//			$nowdate = date('Y-m-d H:i:s');
//
//			$dao = new PublicationTempDao($this->db);
//			$dao->addValueStr(PublicationTempDao::COL_TITLE, $title);
//			$dao->addValueStr(PublicationTempDao::COL_SUBTITLE, $subtitle);
//			$dao->addValueStr(PublicationTempDao::COL_DESCRIPTION, $description);
//			$dao->addValueStr(PublicationTempDao::COL_AUTHOR, $author);
//			$dao->addValueStr(PublicationTempDao::COL_PUBLISHER, $publisher);
//			$dao->addValueStr(PublicationTempDao::COL_CATEGORY_ID, $category_id);
//			$dao->addValueStr(PublicationTempDao::COL_KEYWORDS, $keywords);
//			$dao->addValueStr(PublicationTempDao::COL_CONTENTS, $contents);
//			$dao->addValue(PublicationTempDao::COL_CONTENTS_USE, $contents_use);
//			$dao->addValue(PublicationTempDao::COL_CONTENTS_TYPE, $contents_type);
//			$dao->addValue(PublicationTempDao::COL_PROFILE_USE, $profile_use);
//			$dao->addValue(PublicationTempDao::COL_PROFILE_IMG_TYPE, $profile_img_type);
//			$dao->addValue(PublicationTempDao::COL_PROFILE_BODY_TYPE, $profile_body_type);
//			$dao->addValueStr(PublicationTempDao::COL_PROFILE_BODY, $profile_body);
//			$dao->addValueStr(PublicationTempDao::COL_LASTUPDATE, $nowdate);
//			$dao->addWhereStr(PublicationTempDao::COL_PUBLICATION_KEY, $publication_key);
//			$dao->addWhere(PublicationTempDao::COL_USER_ID, $userInfo['id']);
//			$status = $dao->doUpdate();
//
//			if ($status < 1) {
//				$dao->addValueStr(PublicationTempDao::COL_PUBLICATION_KEY, $publication_key);
//				$dao->addValue(PublicationTempDao::COL_USER_ID, $userInfo['id']);
//				$dao->addValueStr(PublicationTempDao::COL_CREATEDATE, $nowdate);
//				$status = $dao->doInsert();
//			}
//
//		} catch (Exception $e) {
//			$errormsg = $e->getStackTrace();
//		}
//
//		$data = array('status'=>$status);
//		$data['nowdate'] = $nowdate;
//		if ($errormsg != '') $data['errormsg'] = $errormsg;
//		$encode = Util::jsonEncode($data);
//
//		$this->form->set('data', $encode);
//
//		$this->resp->setContentType(SpResponse::CTYPE_JSON);
//
//		return $this->forward('json', APP_CONST_EMPTY_FRAME);
//	}

	private function _validateCreated()
	{
		$ret = $this->form->validate($this->form->getValidates(0));
		return $ret;
	}

	private function _validateWriting()
	{
		$ret = $this->form->validate($this->form->getValidates(1));
		return $ret;
	}

	/**
	 * 表紙画像アップロード入力チェック
	 */
	private function _validateCover()
	{
		$ret = true;
		$errmsg = '';
		if (isset($_FILES['cover_file']) && $_FILES['cover_file']['name']!='') {
			if (UtilFile::uploadFileCheck(&$_FILES['cover_file'], &$errmsg) === false) {
				$this->logger->error($errmsg);
				$ret = false;
			} else {
				$name = SpFilter::sanitize($_FILES['cover_file']['name']);
				$ext = Util::getExtension($name);
				if (!preg_match(APP_CONST_COVER_IMAGE_EXT_REG, $ext, $m)) {
					$this->logger->error('不正なファイル形式によるアップロード（'.$m[1].'）');
					$errmsg = '選択可能な画像ファイルは'.APP_CONST_COVER_IMAGE_EXT_TXT.'のみです。';
					$ret = false;
				} else {
					$this->form->set('cover_file', $name);
				}
			}
			if ($ret === false) $this->form->setValidateErrors('cover_file', $errmsg);
		}

		if ($this->form->validate($this->form->getValidates(3)) === false) $ret = false;

		return $ret;
	}

	/**
	 * 画像アップロード入力チェック
	 */
	private function _validateImage()
	{
		$ret = true;
		$errmsg = '';

		for ($i=1; $i<=1; $i++) {
			$file_key = 'image'.$i.'_file';
			if (isset($_FILES[$file_key]) && $_FILES[$file_key]['name']!='') {
				$is_ok = true;
				if (UtilFile::uploadFileCheck(&$_FILES[$file_key], &$errmsg) === false) {
					$this->logger->error($errmsg);
					$is_ok = false;
					$ret = false;
				} else {
					$name = SpFilter::sanitize($_FILES[$file_key]['name']);
					$ext = Util::getExtension($name);
					// 画像でチェック
					if (!preg_match(APP_CONST_PUBLICATION_IMAGE_EXT_REG, $ext, $m)) {
						// zipでチェック
						if (!preg_match(APP_CONST_PUBLICATION_IMAGE_ZIP_EXT_REG, $ext, $m)) {
							$this->logger->error('不正なファイル形式によるアップロード（'.$m[1].'）');
							$errmsg = '選択可能なファイルは'.APP_CONST_PUBLICATION_IMAGE_EXT_TXT.'、'.APP_CONST_PUBLICATION_IMAGE_ZIP_EXT_TXT.'のみです。';
							$is_ok = false;
							$ret = false;
						}
					}
				}
				if ($is_ok) {
					$this->form->set($file_key, $name);
				} else {
					$this->form->setValidateErrors($file_key, $errmsg);
				}
			}
		}

		if ($this->form->validate($this->form->getValidates(4)) === false) $ret = false;

		return $ret;
	}

	/**
	 * 画像を一時ディレクトリへ
	 * @param int $user_id
	 */
	private function _copyImageTemp()
	{
		$userInfo = $this->getUserInfo();

		$ret = true;
		for ($i=1; $i<=1; $i++) {
			$key = 'image'.$i;
			if ($this->copyFileTemp($key, APP_CONST_PUBLICATION_IMAGE_TMP_DIR, '', $userInfo['id'].'_') === false) {
				$ret = false;
			}
		}
		return $ret;
	}

	/**
	 * 画像を一時ディレクトリから本番ディレクトリへ移動
	 * @param int $publication_id
	 */
	private function _copyImage($publication_id)
	{
		$userInfo = $this->getUserInfo();

		$ret = true;
		for ($i=1; $i<=1; $i++) {
			$path_key = 'image'.$i.'_path';
			$image_path = $this->form->get($path_key);
			$image_dir = str_replace(array('[user_id]', '[publication_id]'), array($userInfo['id'], $publication_id), APP_CONST_PUBLICATION_IMAGE_DIR);
			@mkdir($image_dir, 0705, true);
			if (file_exists(APP_CONST_PUBLICATION_IMAGE_TMP_DIR.'/'.$image_path)) {
				if (@rename(APP_CONST_PUBLICATION_IMAGE_TMP_DIR.'/'.$image_path, $image_dir.'/'.$image_path) === false) {
					$ret = false;
				}
			}
		}
		return $ret;
	}

	/**
	 * マイデスク開設状態を確認
	 * @return boolean
	 */
	private function _checkUseMydesk()
	{
		$userInfo = $this->getUserInfo();
		return ($userInfo && $userInfo['use_mydesk'] == 1);
	}

	/**
	 * マイデスク開設の確認画面
	 */
	private function _confirmMydesk()
	{
		$this->form->setDefault('loc', $this->form->getPageUrl());
		$this->form->setParameterForm('loc');

		$this->form->set('htitle', 'マイデスク開設の確認');
		$this->setTitle($this->form->get('htitle'), 'マイページ');

		return $this->forward('user/mydesk/user_mydesk_confirm_mydesk', APP_CONST_USER_FRAME);
	}

	/**
	 * 内容登録のある有効なページ数
	 * @param unknown_type $id
	 * @param unknown_type $user_id
	 * @return number
	 */
	private function _validPageCount($id, $user_id)
	{
		$publicationPagesDao = new PublicationPagesDao($this->db);
		$publicationPagesDao->addSelectCount(PublicationPagesDao::COL_PAGE_ID, 'total');
		$publicationPagesDao->addWhere(PublicationPagesDao::COL_PUBLICATION_ID, $id);
		$publicationPagesDao->addWhere(PublicationPagesDao::COL_USER_ID, $user_id);
		$publicationPagesDao->addWhere(PublicationPagesDao::COL_STATUS, PublicationPagesDao::STATUS_FINISH);
		$publicationPagesDao->addWhereStr(PublicationPagesDao::COL_PAGE_CONTENTS, '', '!=');
		return $publicationPagesDao->selectId();
	}

	/**
	 * 画像データの読み込み
	 * @param unknown_type $publication_id
	 * @param unknown_type $user_id
	 */
	private function _setImageData($publication_id, $user_id)
	{
		$publicationImagesDao = new PublicationImagesDao($this->db);
		$publicationImagesDao->addOrder(PublicationImagesDao::COL_IMAGE_TITLE);
//		$publicationImagesDao->addOrder(PublicationImagesDao::COL_CREATEDATE, 'DESC');
		$this->form->set('image_list', $publicationImagesDao->getList($publication_id, $user_id));
		$publicationImagesDao->reset();
		$image_user_size = $publicationImagesDao->getUserSize($publication_id, $user_id);
		$this->form->set('image_user_size', $image_user_size);
		$this->form->set('image_use_rate', number_format((($image_user_size / APP_CONST_PUBLICATION_IAMGE_TOTAL_MAX_SIZE) * 100), 2));
	}

	/**
	 * 画像の登録
	 * @param unknown_type $publication_id
	 */
	private function _uploadImage($publication_id)
	{
		$userInfo = $this->getUserInfo();

		@mkdir(APP_CONST_PUBLICATION_IMAGE_TMP_DIR, 0705, true);
		// 入力チェック
		if ($this->_validateImage() === false) {
			return false;
		// tmpコピー
		} else if ($this->_copyImageTemp() === false) {
			return false;
		} else {

			$image_dir = str_replace(array('[user_id]', '[publication_id]'), array($userInfo['id'], $publication_id), APP_CONST_PUBLICATION_IMAGE_DIR);
			@mkdir($image_dir, 0705, true);

			try {
				$image_path = $this->form->get('image1_path');
				$ext = Util::getExtension($image_path);
				$dao = new PublicationImagesDao($this->db);
				// zipから登録
				if (preg_match(APP_CONST_PUBLICATION_IMAGE_ZIP_EXT_REG, $ext, $m)) {
					$to_dirname = uniqid($userInfo['id'].'_', true);
					$to_dir = APP_CONST_PUBLICATION_IMAGE_ZIP_TMP_DIR.'/'.$to_dirname;
					// 解凍
					$this->_readZipFile(APP_CONST_PUBLICATION_IMAGE_TMP_DIR.'/'.$image_path, $to_dir);
					$zipfiles = $this->form->get('zipfiles');
					$num = count($zipfiles);
					$this->form->set('filetot', $num);
					$cnt = 0;
					foreach ($zipfiles as $filename) {
						if (file_exists($filename)) {
							$ext = Util::getExtension($filename);
							$image_path = uniqid($userInfo['id'].'_', true).'.'.$ext;
							$image_file = basename($filename);
							$image_title = $image_file;
							try {
								if (rename($filename, $image_dir.'/'.$image_path)) {
									$image_size = filesize($image_dir.'/'.$image_path);
									// 登録
									$this->_registerImage(&$dao, $publication_id, $userInfo['id'], $image_title, $image_file, $image_path, $image_size);
									$cnt++;
								} else {
									throw new SpException('ファイルのコピーに失敗しました。');
								}
							} catch (SpException $e) {
								$this->db->rollback();
								$this->logger->exception($e);
							}
						}
						if ($cnt>=APP_CONST_PUBLICATION_IMAGE_ZIP_MAX_NUM) {
							break;
						}
					}
					$this->form->set('filecnt', $cnt);
					UtilFile::removeDir($to_dir);
					UtilFile::removeFile(APP_CONST_PUBLICATION_IMAGE_TMP_DIR.'/'.$image_path);
				} else {
					for ($i=1; $i<=1; $i++) {
						$image_title = $this->form->get('image'.$i.'_title');
						$image_file = mb_convert_kana($this->form->get('image'.$i.'_file'), 'aKV');
						$image_path = $this->form->get('image'.$i.'_path');
						$image_size = $this->form->getInt('image'.$i.'_size');
						if (file_exists(APP_CONST_PUBLICATION_IMAGE_TMP_DIR.'/'.$image_path)) {
							try {
								if (rename(APP_CONST_PUBLICATION_IMAGE_TMP_DIR.'/'.$image_path, $image_dir.'/'.$image_path)) {
									// 登録
									$this->_registerImage(&$dao, $publication_id, $userInfo['id'], $image_title, $image_file, $image_path, $image_size);
								} else {
									$this->logger->error('コピーに失敗。'.APP_CONST_PUBLICATION_IMAGE_TMP_DIR.'/'.$image_path.' > '.$image_dir.'/'.$image_path);
									throw new SpException('ファイルのコピーに失敗しました。');
								}
							} catch (SpException $e) {
								$this->db->rollback();
								$this->logger->exception($e);
							}
						}
					}
				}
			} catch (SpException $e) {
				$this->logger->exception($e);
				$this->form->setValidateErrors('image1_file', $e->getMessage());
				return false;
			}
		}
		return true;
	}

	private function _registerImage(&$dao, $publication_id, $user_id, $image_title, $image_file, $image_path, $image_size)
	{
		if ($image_title == '') $image_title = $image_file;

		$this->db->beginTransaction();

		$dao->addValue(PublicationImagesDao::COL_PUBLICATION_ID, $publication_id);
		$dao->addValue(PublicationImagesDao::COL_USER_ID, $user_id);
		$dao->addValueStr(PublicationImagesDao::COL_IMAGE_TITLE, $image_title);
		$dao->addValueStr(PublicationImagesDao::COL_IMAGE_FILE, $image_file);
		$dao->addValueStr(PublicationImagesDao::COL_IMAGE_PATH, $image_path);
		$dao->addValue(PublicationImagesDao::COL_IMAGE_SIZE, $image_size);
		$dao->addValue(PublicationImagesDao::COL_CREATEDATE, Dao::DATE_NOW);
		$dao->addValue(PublicationImagesDao::COL_LASTUPDATE, Dao::DATE_NOW);
		$dao->doInsert();
		$dao->reset();

		$this->db->commit();
	}

	/**
	 * zipファイルを解凍し画像を読み込む
	 * @param unknown_type $zip_path
	 * @param unknown_type $to_dir
	 */
	private function _readZipFile($zip_path, $to_dir)
	{
		Sp::import('PEAR/Archive', 'libs', true);

		@mkdir($to_dir, 0777, true);

		$ret = File_Archive::extract(
			File_Archive::read($zip_path.'/'),
			File_Archive::appender($to_dir)
		);

		if (PEAR::isError($ret)) {
			throw new SpException("zipファイルの解凍に失敗しました。:".$ret->getMessage());
		}

		$files = array();
		UtilFile::readDirFile(&$files, $to_dir);
		if (count($files)>0) {
			$zipfiles = array();
			foreach ($files as $filename) {
				$ext = Util::getExtension($filename);
				if ($ext!='' && preg_match(APP_CONST_PUBLICATION_IMAGE_EXT_REG, $ext, $m)) {
					$zipfiles[] = $filename;
				}
			}
			$this->form->set('zipfiles', $zipfiles);
		} else {
			throw new SpException('圧縮ファイルにデータが含まれていません。');
		}
	}

//	private function _resizeImage(&$files, $ext)
//	{
//		list($width, $height) = getimagesize($files['tmp_name']);
//		if ($width <= APP_CONST_MOBILE_IMG_WIDTH) return false;
//		$newwidth = APP_CONST_MOBILE_IMG_WIDTH;
//		$percent = $newwidth / $width;
//		$newheight = floor($height * $percent);
//
//		$files['name'] = SpFilter::sanitize($files['name']);
//
//		$imagecreate = $this->_getImagecreateFunc($ext);
//		if ($imagecreate == '') return false;
//
//		$thumb = @imagecreatetruecolor($newwidth, $newheight);
//		$img = @$imagecreate($files['tmp_name']);
//		if ($img !== false) {
//			if (@imagecopyresized($thumb, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height)) {
//				$jpeg_path = APP_CONST_TMP_DIR . '/' . uniqid('jpeg_', true) . '.jpg';
//				if (@imagejpeg($thumb, $jpeg_path, 80)) {
//					if (file_exists($jpeg_path) && ($size = filesize($jpeg_path)) > 0) {
//						$files['name'] = mb_eregi_replace("\.[a-z0-9]+$", '.jpg', $files['name']);
//						$files['type'] = 'image/jpeg';
//						$files['size'] = $size;
//						$files['copy_name'] = $jpeg_path;
//						unset($files['tmp_name']);
//						@imagedestroy($img);
//						@imagedestroy($thumb);
//						return true;
//					}
//				} else {
//					$this->logger->error('imagejpeg(): 失敗');
//				}
//				@imagedestroy($img);
//				@imagedestroy($thumb);
//			} else {
//				$this->logger->error('imagecopyresized(): 失敗');
//			}
//		} else {
//			$this->logger->error($imagecreate.'(): 失敗');
//		}
//		return false;
//	}
//
//	private function _convertToJpeg(&$files, $ext)
//	{
//		if (preg_match("/\.jpe?g$/i", $files['name'])) return true;
//
//		$imagecreate = $this->_getImagecreateFunc($ext);
//		if ($imagecreate == '') return false;
//
//		$img = @$imagecreate($files['tmp_name']);
//		if ($img !== false) {
//			$jpeg_path = APP_CONST_TMP_DIR . '/' . uniqid('jpeg_', true) . '.jpg';
//			if (@imagejpeg($img, $jpeg_path, 80)) {
//				if (file_exists($jpeg_path) && ($size = filesize($jpeg_path)) > 0) {
//					$files['name'] = mb_eregi_replace("\.[a-z0-9]+$", '.jpg', $files['name']);
//					$files['type'] = 'image/jpeg';
//					$files['size'] = $size;
//					$files['copy_name'] = $jpeg_path;
//					unset($files['tmp_name']);
//					@imagedestroy($img);
//					return true;
//				}
//			} else {
//				$this->logger->error('imagejpeg(): 失敗');
//			}
//			@imagedestroy($img);
//		} else {
//			$this->logger->error($imagecreate.'(): 失敗');
//		}
//		return false;
//	}
//
//	private function _getImagecreateFunc($ext)
//	{
//		$imagecreate = '';
//		if ($ext == 'gif') {
//			$imagecreate = 'imagecreatefromgif';
//		} else if ($ext == 'png') {
//			$imagecreate = 'imagecreatefrompng';
//		} else if ($ext == 'jpg' || $ext == 'jpeg') {
//			$imagecreate = 'imagecreatefromjpeg';
//		}
//		return $imagecreate;
//	}

}
?>
