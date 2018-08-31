<?php
/**
 * ベースコントローラー
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BaseController extends SpController
{
	/**
	 * ご指定のURLが間違っています。
	 */
	const ERROR_PAGE_MESSAGE1 = "ご指定のURLが間違っています。";

	/**
	 * ご指定のデータは閲覧できません。
	 */
	const ERROR_PAGE_MESSAGE2 = "ご指定のデータは閲覧できません。";

	/**
	 * ご指定のページは閲覧できません。
	 */
	const ERROR_PAGE_MESSAGE3 = "ご指定のページは閲覧できません。";

	/**
	 * 依頼者としてログインする必要があります。
	 */
	const ERROR_PAGE_MESSAGE4 = "依頼者としてログインする必要があります。";

	/**
	 * このデータは既に送信済みです。重複して送信することはできません。
	 */
	const ERROR_PAGE_MESSAGE5 = "このデータは既に送信済みです。重複して送信することはできません。";

	const AJAX_SUCCESS = 1;
	const AJAX_ERROR = 0;
	const AJAX_STATUS_SUCCESS = 1;
	const AJAX_STATUS_ERROR = 0;

	/**
	 * メイン実行前の共通処理
	 */
	public function preExecute()
	{
		// テンプレート初期設定
		$this->form->setTemplateDir(APP_DIR.'/templates');
		$this->form->setCompileDir(APP_DIR.'/templates_c');
		$this->form->setSmartyPlugins(APP_DIR . constant('app_smarty_plugins_dir'));

		// AppConst値をSp変数として登録
		$appconst = get_class_vars('AppConst');
		$this->form->setSp('AppConst', $appconst);

		$this->form->setSp('userInfo', $this->getUserInfo());
		$this->form->setSp('keywords', APP_CONST_META_KEYWORDS);
		$this->form->setSp('description', APP_CONST_META_DESCRIPTION);

		$lastModified = gmdate('D, d M Y H:i:s T', time() - 4000);
		$this->resp->setHeader('Last-Modified', $lastModified);
		$etag = '"'.md5($lastModified).'"';
		$this->resp->setHeader('ETag', $etag);

		return;
	}

	/**
	 * メイン実行後の共通処理
	 */
	public function postExecute()
	{
		$subtitle1 = $this->form->get('subtitle_1');
		$subtitle2 = $this->form->get('subtitle_2');

		$title = $subtitle1;
		if ($subtitle2 != '') $title .= ' | '.$subtitle2;
//		$title .= $subtitle2;
//		if ($subtitle2 != '') $title .= ' | ';
		if ($title == '') {
			$title = $this->form->get('maintitle', APP_CONST_SITE_TITLE_F);
		} else {
			$title .= $this->form->get('maintitle', APP_CONST_SITE_TITLE2);
		}
		$this->form->setTitle($title);

		return;
	}

	/**
	 * デフォルトエントリポイント
	 */
	public function index()
	{
		return $this->forward('index');
	}

	/**
	 * ページが存在しない場合
	 */
	public function notfound()
	{
		$this->resp->setStatus(404);
		return $this->forward('notfound', APP_CONST_NOTFOUND_FRAME);
	}

	/**
	 * 画面設定
	 */
	protected function forward($forward, $frame=null)
	{
		$forward = array($forward);
		if ($frame!==null) $forward[] = $frame;
		else $forward[] = APP_CONST_MAIN_FRAME;
		return $this->form->forward($forward);
	}

	/**
	 * タイトル設定
	 */
	protected function setTitle($subtitle1, $subtitle2='', $title=null)
	{
		$this->form->set('subtitle_1', $subtitle1);
		$this->form->set('subtitle_2', $subtitle2);
		if ($title!==null) $this->form->set('maintitle', $title);
	}

	protected function setKeywords($keywords, $type='before')
	{
		$this->form->setSp('keywords', $keywords.$this->form->getSp('keywords'));
	}

	protected function setDescription($description, $type='before')
	{
		$this->form->setSp('description', $description.$this->form->getSp('description'));
	}

	/**
	 * エラー画面の呼び出し
	 */
	protected function errorPage($msg='', $frame=null)
	{
		if ($msg!='') $this->form->set('message', $msg);
		return $this->forward('error', $frame);
	}

	protected function notfoundPage()
	{
		return $this->resp->setStatus(404);
		//return $this->forward('notfound', APP_CONST_NOTFOUND_FRAME);
	}

	/**
	 * ログイン画面の呼び出し
	 */
	protected function loginPage()
	{
//		$this->deleteUserInfo();

		$this->form->setDefault('loc', $this->form->getPageUrl());
		$this->form->setParameterForm('loc');
		$this->form->setParameterForm('_hash');

//		$this->setTitle('ログイン');

		return $this->forward('login', APP_CONST_MAIN_FRAME);
	}

	/**
	 * 認証チェック
	 */
	protected function checkUserAuth()
	{
		$c = $this->getUserInfo();
//		// 認証は24時間有効
//		return ($c!==null && (time() < $c['ts'] + APP_CONST_USER_AUTH_TIME));
		return ($c!==null);
	}

	/**
	 * 認証後情報の登録
	 */
	protected function setUserInfo(&$user_info)
	{
		if (is_array($user_info) === false || count($user_info) == 0) return;
		$ts = time();
		$info = array(
			'date' => date('Y-m-d H:i:s', $ts),
			'ts' => $ts,
			'remote' => $_SERVER['REMOTE_ADDR'],
			'uagent' => $_SERVER['HTTP_USER_AGENT']
		);
		$user_info['id'] = (int)$user_info['user_id'];
		$info = $user_info + $info;
		unset($info['password']);
		$this->form->setSession(APP_CONST_USER_AUTH_NAME, $info);
		return;
	}

	/**
	 * 認証情報の上書き
	 * @param array $new_info
	 */
	protected function updateUserInfo(&$new_info)
	{
		$user_info = $this->getUserInfo();
		foreach ($user_info as $key => $val) {
			if (isset($new_info[$key])) {
				$user_info[$key] = $new_info[$key];
			}
		}
		$this->form->setSp('userInfo', $user_info);
		$this->form->setSession(APP_CONST_USER_AUTH_NAME, $user_info);
		return;
	}

	/**
	 * 認証情報の取得
	 */
	protected function getUserInfo()
	{
		return $this->form->get(APP_CONST_USER_AUTH_NAME);
	}

	/**
	 * 認証情報の削除
	 */
	protected function deleteUserInfo()
	{
		//session_unset();
		$this->form->setSp('userInfo', null);
		return $this->form->clearSession(APP_CONST_USER_AUTH_NAME);
	}

	protected function checkUserType($user_type)
	{
		$userInfo = $this->form->get(APP_CONST_USER_AUTH_NAME);
		if (empty($userInfo)) return false;
		return ($userInfo['user_type'] == $user_type);
	}

	/**
	 * ユーザー情報をグローバルに読み込む
	 */
	protected function loadUserData()
	{
		if (isset($GLOBALS[APP_CONST_LOAD_USER_NAME]) === false) {
			Sp::import('UsersDao', 'dao', true);
			$usersDao = new UsersDao($this->db);
			$usersDao->addWhere(UsersDao::COL_DELETE_FLAG, 0);
			$list = $usersDao->select();
			$GLOBALS[APP_CONST_LOAD_USER_NAME] = Util::arrayKeyData('user_id', $list);
		}
	}

	protected function getUserData($user_id, $key='')
	{
		if (isset($GLOBALS[APP_CONST_LOAD_USER_NAME]) === false) $this->loadUserData();
		if ($key == '') {
			return $GLOBALS[APP_CONST_LOAD_USER_NAME][$user_id];
		} else {
			return $GLOBALS[APP_CONST_LOAD_USER_NAME][$user_id][$key];
		}
	}

	/**
	 * Ajax経由時のエラー処理
	 * @param string $errormsg
	 * @return boolean
	 */
	protected function ajaxStatusError($errormsg)
	{
		$data = array('status'=>'error', 'errormsg'=>$errormsg);
		$encode = Util::jsonEncode($data);
		$this->form->set('data', $encode);
		return $this->forward('json', APP_CONST_EMPTY_FRAME);
	}

	/**
	 * 添付ファイルの一時ファイル保存
	 * @param string $key
	 * @return boolean
	 */
	protected function copyFileTemp($key, $tmp_dir, $tmp_ext='', $prefix='', $name_type=0)
	{
		$ret = true;
		$k = $key.'_file';

		if (isset($_FILES[$k]) && $_FILES[$k]['name']!='') {
			$name = SpFilter::sanitize($_FILES[$k]['name']);
			$ext = Util::getExtension($name);
			$size = 0;
			if ($name_type == 0) {
				$tmpfile = uniqid($prefix, true).'.'.$ext.$tmp_ext;
			} else if ($name_type == 1) {
				$tmpfile = $prefix.'.'.$ext.$tmp_ext;
			}
			if (move_uploaded_file($_FILES[$k]['tmp_name'], $tmp_dir.'/'.$tmpfile)) {
				$size = filesize($tmp_dir.'/'.$tmpfile);
			} else {
				$ret = false;
				$this->logger->error('コピーに失敗。'.$_FILES[$k]['tmp_name'].' > '.$tmp_dir.'/'.$tmpfile);
				$this->form->setValidateErrors($k, 'ファイルのコピーに失敗');
			}
			$this->form->set($key.'_file', $name);
			$this->form->set($key.'_path', $tmpfile);
			$this->form->set($key.'_size', $size);
		}

		return $ret;
	}

	/**
	 * 本閲覧数のユニークカウント用
	 * @param int $book_id
	 * @return boolean true：カウントアップ必要、false：必要なし
	 */
	protected function setBookUniqCount($book_id, $cookie_name)
	{
		$book_key = '/'.$book_id.'/';
		$cookie_value = '';
		if (isset($_COOKIE[$cookie_name])) {
			$cookie_value = trim($_COOKIE[$cookie_name]);
			if (strpos($cookie_value, $book_key) !== false) return false;
		}
		$cookie_value .= $book_key;
		$today_ts = mktime(0,0,0,date('n'),date('j'),date('Y'));
		// 本日の23:59:59まで有効
		$expire = $today_ts + 86399;
		return setcookie($cookie_name, $cookie_value, $expire, '/book/');
	}

	protected function getBookUniqStatus($book_id, $cookie_name)
	{
		$book_key = '/'.$book_id.'/';
		$cookie_value = '';
		if (isset($_COOKIE[$cookie_name])) {
			$cookie_value = trim($_COOKIE[$cookie_name]);
			if (strpos($cookie_value, $book_key) !== false) return false;
		}
		return true;
	}

	protected function isNotIp()
	{
		return sw_is_notip();
	}

	protected function isNotUserAgent()
	{
		$agent = trim($_SERVER['HTTP_USER_AGENT']);
		if (empty($agent)) return true;
		foreach (AppConst::$notUserAgent as $key) {
			if (strpos($agent, $key) !== false) return true;
		}
		return false;
	}

	/**
	 * 2重投稿禁止用トークンの生成
	 */
	protected function createSecurityCode()
	{
		$this->form->setSession(APP_CONST_SECURITY_CODE_NAME, md5(uniqid('', true)));
		$this->form->setParameter(APP_CONST_SECURITY_TOKEN_NAME, $this->form->get(APP_CONST_SECURITY_CODE_NAME));
	}

	/**
	 * 2重投稿禁止用トークンのチェック
	 */
	protected function checkSecurityCode()
	{
		$token = $this->form->get(APP_CONST_SECURITY_TOKEN_NAME);
		$code = $this->form->get(APP_CONST_SECURITY_CODE_NAME);
		$this->form->clearSession(APP_CONST_SECURITY_CODE_NAME);
		if ($token != '' && $code != '' && $token == $code) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * テスト
	 */
//	protected function setupReader()
//	{
//		$this->form->setStyleSheet('/js/spotreader/booklet/jquery.booklet.1.1.0a.css');
//		$this->form->setStyleSheet('/js/spotreader/spotreader.css');
//		// ソース
//		$this->form->setScript('/js/spotreader/spotreader.config.js');
//		$this->form->setScript('/js/spotreader/booklet/jquery.easing.1.3.js');
//		$this->form->setScript('/js/spotreader/booklet/jquery.booklet.1.1.0a.js');
//		$this->form->setScript('/js/spotreader/jquery.spotreader.js');
//	}

	/**
	 * 本番
	 */
	protected function setupReader()
	{
		$this->form->setStyleSheet('/js/spotreader/booklet/jquery.booklet.1.1.0a.css');
		$this->form->setStyleSheet('/js/spotreader/spotreader.css');
		// 本番
		$this->form->setScript('/js/spotreader/spotreader.js?v=1.0.5');
	}

	/**
	 * カテゴリー表示
	 */
	protected function setCategoryList()
	{
		Sp::import('BooksDao', 'dao', true);
		Sp::import('CategoryRanksDao', 'dao', true);

		$categoryRanksDao = new CategoryRanksDao($this->db);
		$book = $categoryRanksDao->getListJoinBook();

		$booksDao = new BooksDao($this->db);
		$list = $booksDao->getCategoryList();

		Util::leftJoin(&$book, $list, 'category_id');

		if (count($book)>0) {
			foreach ($book as $i => $d) {
				if ($d['book_data']!='') {
					$book_arr = array();
					$book_data = explode("\n", $d['book_data']);
					for ($n=0; $n<3; $n++) {
						if (isset($book_data[$n]) && $book_data[$n]!='') {
							$arr = explode("\t", $book_data[$n], 2);
							$book_arr[] = array('book_id'=>$arr[0], 'title'=>$arr[1]);
						}
					}
					$d['book_datas'] = $book_arr;
					$book[$i] = $d;
				}
			}
		}
		$this->form->set('category_list', $book);
	}
}
?>
