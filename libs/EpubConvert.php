<?php
Sp::import('BooksDao', 'dao');
Sp::import('BookPagesDao', 'dao');
Sp::import('UsersDao', 'dao');
//Sp::import('PEAR/Archive', 'libs');
Sp::import('zip.lib.php', 'libs');
/**
 * EPUBファイルの作成
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class EpubConvert
{
	public $pubId = 0;
	public $pubData = array();
	public $pubPage = array();
	public $pubUser = array();
	public $images = array();
	public $uid = '';
	private $form;

	public function __construct(&$db, $book_id, $site_url, &$images)
	{
		$this->pubId = $book_id;

		$booksDao = new BooksDao($db);
		$this->pubData = $booksDao->getItem($book_id);

		$pagesDao = new BookPagesDao($db);
		$this->pubPage = $pagesDao->getItemList($book_id);

		$usersDao = new UsersDao($db);
		$this->pubUser = $usersDao->getItem($this->pubData['user_id']);

		$this->images = $images;

		$this->uid = $site_url . 'book/' . $this->pubId . '/';

		$this->form = new SpForm();
		$this->form->setTemplateDir(APP_DIR . '/templates');
		$this->form->setCompileDir(APP_DIR . '/templates_c');
	}

	/**
	 * epub
	 * /META-INF/container.xml ->
	 * /mimetype -> application/epub+zip
	 */
	public function create($tmp_epub_dir)
	{
		$temp_dir = $tmp_epub_dir . '/' . $this->pubId;
		$meta_dir = $temp_dir . '/META-INF';
		$oebps_dir = $temp_dir . '/OEBPS';

		UtilFile::removeDir($temp_dir);
		clearstatcache();

		@mkdir($tmp_epub_dir);
		@mkdir($temp_dir);
		@mkdir($meta_dir);
		@mkdir($oebps_dir);
		@mkdir($oebps_dir . '/Text');
		@mkdir($oebps_dir . '/Styles');
		@mkdir($oebps_dir . '/Image');

		file_put_contents($temp_dir . '/mimetype', 'application/epub+zip');
		file_put_contents($meta_dir . '/container.xml', $this->_getContainerXml());
		file_put_contents($oebps_dir . '/toc.ncx', $this->_getTocNcx());
		file_put_contents($oebps_dir . '/content.opf', $this->_getContentOpf());
		file_put_contents($oebps_dir . '/Styles/Style.css', $this->_getStyleCss());

		$num = 1;
		foreach ($this->pubPage as $d) {
			$filename = sprintf('page%04d.xhtml', $num);
			$d['page_contents'] = preg_replace('/ src="\/file\/[0-9]+\/publication\/[0-9]+\/([^"]+)"/mu', ' src="../Image/${1}"', $d['page_contents']);
			file_put_contents($oebps_dir . '/Text/' . $filename, $this->form->getTemplateContents($d, '_epub/OEBPS/Text/page'));
			$num++;
		}

		if ($this->pubData['cover_path']!='') {
			$cover_dir = str_replace('[user_id]', $this->pubUser['user_id'], APP_CONST_COVER_IMAGE_DIR);
			$ext = Util::getExtension($this->pubData['cover_path']);
			if ($ext == 'jpg') $ext = 'jpeg';
			copy($cover_dir.'/'.$this->pubData['cover_path'], $oebps_dir.'/Image/cover-image.'.$ext);
			$arr = array('cover_src' => '../Image/cover-image.'.$ext);
			file_put_contents($oebps_dir . '/Text/cover.xhtml', $this->form->getTemplateContents($arr, '_epub/OEBPS/Text/cover'));
		}

		if (count($this->images)>0) {
			foreach ($this->images as $d) {
				if ($d['image_path']!='') {
					$image_dir = str_replace(array('[user_id]', '[publication_id]'), array($this->pubUser['user_id'], $d['publication_id']), APP_CONST_PUBLICATION_IMAGE_DIR);
					if (file_exists($image_dir.'/'.$d['image_path'])) copy($image_dir.'/'.$d['image_path'], $oebps_dir.'/Image/'.$d['image_path']);
				}
			}
		}

		clearstatcache();

////		$ret = File_Archive::extract(
////			File_Archive::read($temp_dir.'/'),
////			File_Archive::toArchive($tmp_epub_dir.'/book-'.$this->pubId.'.epub', File_Archive::toFiles(), 'zip')
////		);
//
//		$ret = File_Archive::extract(
//			File_Archive::read($temp_dir.'/'),
//			File_Archive::toArchive($tmp_epub_dir.'/book-'.$this->pubId.'.zip', File_Archive::toFiles())
//		);
//
//		if (PEAR::isError($ret)) {
//			throw new SpException($ret->getMessage());
//		}
//
//		if (file_exists($tmp_epub_dir.'/book-'.$this->pubId.'.zip')) {
//			if (file_exists($tmp_epub_dir.'/book-'.$this->pubId.'.epub')) @unlink($tmp_epub_dir.'/book-'.$this->pubId.'.epub');
//			rename($tmp_epub_dir.'/book-'.$this->pubId.'.zip', $tmp_epub_dir.'/book-'.$this->pubId.'.epub');
//		}

		// phpMyAdmin libraries 使用
		chdir($temp_dir);
		$zipfile = new zipfile();
		$this->_toZip(&$zipfile, $temp_dir, str_replace('\\', '/', $temp_dir));
		$zip_buffer = $zipfile->file();
		$handle = fopen($tmp_epub_dir . "/book-".$this->pubId.".epub", "wb");
		fwrite($handle, $zip_buffer);
		fclose($handle);

		return;
	}

	private function _toZip(&$zipfile, $dir, $cwd)
	{
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..') {
						$this->_toZip($zipfile, $dir.'/'.$file, $cwd);
					}
				}
				closedir($dh);
			}
		} else if (is_file($dir)) {
			$dir = str_replace('\\', '/', $dir);
			$handle = fopen($dir, "rb");
			$contents = fread($handle, filesize($dir));
			fclose($handle);
//			$getcwd = str_replace('\\', '/', getcwd());
//			$filename = str_replace($getcwd, '', $dir);
			$filename = str_replace($cwd, '', $dir);
			$zipfile->addFile($contents, $filename);
		}
	}

	private function _getContainerXml()
	{
		$var_arr = array();
		return $this->form->getTemplateContents($var_arr, '_epub/META-INF/container');
	}

	private function _getTocNcx()
	{
		$var_arr = array();
		$var_arr['uid'] = $this->uid;
		$var_arr['title'] = $this->pubData['title'];

		$nav_point = array();
		if ($this->pubData['cover_path']!='') {
			$nav_point[] = array(
				'text' => '表紙',
				'src' => 'Text/cover.xhtml'
			);
		}
//		$nav_point[] = array(
//			'text' => '目次',
//			'src' => 'Text/cover.xhtml'
//		);

		$num = 1;
		foreach ($this->pubPage as $d) {
			$filename = sprintf('page%04d.xhtml', $num);
			$nav_point[] = array(
				'text' => $d['page_title'],
				'src' => 'Text/' . $filename
			);
			$num++;
		}
		$var_arr['navPoint'] = $nav_point;

		return $this->form->getTemplateContents($var_arr, '_epub/OEBPS/toc');
	}

	private function _getContentOpf()
	{
		$var_arr = array();
		$var_arr['uid'] = $this->uid;
		$var_arr['title'] = $this->pubData['title'];
		$var_arr['author'] = $this->pubUser['penname']; //$this->pubData['author'];
		$var_arr['publisher'] = $this->pubData['publisher'];
		$var_arr['description'] = str_replace(array("\r","\n","\t"), '', $this->pubData['description']);
		$var_arr['date'] = substr($this->pubData['createdate'], 0, 10);

		$item = array();
		$itemref = array();

		$item[] = array(
			'id' => 'ncx',
			'href' => 'toc.ncx',
			'mediatype' => 'application/x-dtbncx+xml'
		);
		if ($this->pubData['cover_path']!='') {
			$item[] = array(
				'id' => 'cov',
				'href' => 'Text/cover.xhtml',
				'mediatype' => 'application/xhtml+xml'
			);
			$itemref[] = array(
				'idref' => 'cov',
				'linear' => 'no'
			);
		}
//		$item[] = array(
//			'text' => '目次',
//			'src' => 'Text/cover.xhtml'
//		);
		$num = 1;
		foreach ($this->pubPage as $d) {
			$filename = sprintf('page%04d.xhtml', $num);
			$item[] = array(
				'id' => $filename,
				'href' => 'Text/' . $filename,
				'mediatype' => 'application/xhtml+xml'
			);
			$itemref[] = array(
				'idref' => $filename
			);
			$num++;
		}
		$item[] = array(
			'id' => 'css',
			'href' => 'Styles/Style.css',
			'mediatype' => 'text/css'
		);
		if ($this->pubData['cover_path']!='') {
			$ext = Util::getExtension($this->pubData['cover_path']);
			if ($ext == 'jpg') $ext = 'jpeg';
			$item[] = array(
				'id' => 'image-cover-image',
				'href' => 'Image/cover-image.'.$ext,
				'mediatype' => 'image/'.$ext
			);
		}

		if (count($this->images)>0) {
			foreach ($this->images as $d) {
				if (preg_match("/(.+)\.([a-zA-Z]+)$/", $d['image_path'], $m)) {
					$item[] = array(
						'id' => 'image-'.$m[1],
						'href' => 'Image/'.$d['image_path'],
						'mediatype' => 'image/'.$m[2]
					);
				}
			}
		}

//		$filename = sprintf('style%04d.css', 1);
//		$item[] = array(
//			'id' => $filename,
//			'href' => 'Styles/' . $filename,
//			'mediatype' => 'text/css'
//		);

		$var_arr['item'] = $item;
		$var_arr['itemref'] = $itemref;

		return $this->form->getTemplateContents($var_arr, '_epub/OEBPS/content');
	}

	private function _getStyleCss()
	{
		$var_arr = array();
		return $this->form->getTemplateContents($var_arr, '_epub/OEBPS/Styles/Style');
	}

	public static function download($epub_dir, $id, $book_title)
	{
		$epub_file = 'book-'.$id.'.epub';
		$epub_path = $epub_dir.'/'.$epub_file;
		//$epub_name = $epub_file;
		$epub_name = $book_title.'-'.$id.'.epub';

		clearstatcache();

		if (file_exists($epub_path)) {
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')!==false) {
				$filename = urlencode($epub_name);
			} else {
				$filename = $epub_name;
			}
			header('Content-Type: application/epub+zip; name="'.$filename.'"; charset=utf-8');
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox/')===false) {
				header('Content-Disposition: attachment; filename="'.$filename.'"');
			}
			header('Content-Length: ' . filesize($epub_path));
			header('Connection: close');
			ob_clean();
			flush();
			readfile($epub_path);
			exit;
		}
		return false;
	}

	public static function downloadFile($epub_dir, $id, $epub_file)
	{
		$epub_path = $epub_dir.'/'.'book-'.$id.'.epub';
		$dir_path = $epub_dir.'/'.$id;
		$file_path = $dir_path.'/'.$epub_file;

		clearstatcache();

		if (file_exists($epub_path) && file_exists($dir_path) && is_dir($dir_path)) {
			if (file_exists($file_path) && is_file($file_path)) {
				$filename = basename($epub_file);
				if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')!==false) {
					$filename = urlencode($filename);
				}
				$ext = Util::getExtension($file_path);
				$ctype = 'application/octet-stream';
				$charset = '';
				if ($ext == 'xml' || $ext == 'opf' || $ext == 'ncx') {
					$ctype = 'text/xml';
					$charset = '; charset=utf-8';
				} else if ($ext == 'xhtml') {
					$ctype = 'application/xhtml+xml';
					$charset = '; charset=utf-8';
				} else if ($ext == 'png' || $ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'bmp') {
					if ($ext == 'jpg') $ext = 'jpeg';
					$ctype = 'image/'.$ext;
				} else if ($ext == 'css' || $ext == 'html' || $ext == 'htm' || $ext == 'js') {
					if ($ext == 'htm') $ext = 'html';
					$charset = '; charset=utf-8';
					$ctype = 'text/'.$ext;
				} else if ($ext == 'txt') {
					$ctype = 'text/plain';
					$charset = '; charset=utf-8';
				}
				header('Content-Type: '.$ctype.'; name="'.$filename.$charset);
				header('Content-Length: ' . filesize($file_path));
				header('Connection: close');
				ob_clean();
				flush();
				readfile($file_path);
			} else {
				header("HTTP/1.0 404 Not Found");
			}
			exit;
		}
		return false;
	}

	/**
	 * ePubファイルの読み込み
	 * @param string $epub_file
	 * @param string $to_dir
	 * @param int $user_id
	 * @param PublicationsDao $publicationsDao
	 * @param PublicationPagesDao $publicationPagesDao
	 * @param PublicationImagesDao $publicationImagesDao
	 */
	public static function read($epub_file, $to_dir, $user_id, &$publicationsDao, &$publicationPagesDao, &$publicationImagesDao)
	{
		@mkdir($to_dir, 0777, true);

		$ret = File_Archive::extract(
			File_Archive::read($epub_file.'/'),
			File_Archive::appender($to_dir)
		);

		if (PEAR::isError($ret)) {
			throw new SpException($ret->getMessage());
		}

		if (!file_exists($to_dir.'/mimetype') || trim(file_get_contents($to_dir.'/mimetype')) != 'application/epub+zip') {
			throw new SpException('ePub形式のファイルではありません。');
		}

		if (file_exists($to_dir.'/META-INF/encryption.xml')) {
			throw new SpException('このePubファイルには対応していません。');
		}

		$container_data = @file_get_contents($to_dir.'/META-INF/container.xml');
		if ($container_data === false) throw new SpException('/META-INF/container.xmlの読み込みに失敗しました。');
		if (!preg_match('/ full\-path="([^"\/]*)\/?([^\/"]+\.opf)"/i', $container_data, $m)) throw new SpException('/META-INF/container.xmlの読み込みに失敗しました。');
		$content_dir = $m[1]; // OEBPS or null
		$content_opf_path = empty($content_dir) ? $m[2] : $content_dir.'/'.$m[2];

		$content_opf_data = @file_get_contents($to_dir.'/'.$content_opf_path);
		if ($content_opf_data === false) throw new SpException('/'.$content_opf_path.'の読み込みに失敗しました。');
		$contentOpf = simplexml_load_string(str_replace(array('dc:', 'opf:'), array('dc_', ''), $content_opf_data));

		// PublicationsDaoデータ
		$title = (string)$contentOpf->metadata->dc_title;
		$description = (string)$contentOpf->metadata->dc_description;

		$publicationsDao->addValue(PublicationsDao::COL_CATEGORY_ID, 999);
		$publicationsDao->addValueStr(PublicationsDao::COL_TITLE, trim($title));
		$publicationsDao->addValueStr(PublicationsDao::COL_SUBTITLE, '');
		$publicationsDao->addValueStr(PublicationsDao::COL_DESCRIPTION, trim($description));
		$publicationsDao->addValue(PublicationsDao::COL_USER_ID, $user_id);
		$publicationsDao->addValue(PublicationsDao::COL_STATUS, PublicationsDao::STATUS_CLOSED);
		$publicationsDao->addValue(PublicationsDao::COL_LATEST_VERSION, 1);
		$publicationsDao->addValue(PublicationsDao::COL_CREATEDATE, Dao::DATE_NOW);
		$publicationsDao->addValue(PublicationsDao::COL_LASTUPDATE, Dao::DATE_NOW);

		// カバーIDの取得
		$cover_id = '';
		for ($i=0; $i<count($contentOpf->metadata->meta); $i++) {
			if ((string)$contentOpf->metadata->meta[$i]['name'] == 'cover') $cover_id = (string)$contentOpf->metadata->meta[$i]['content'];
		}

		$id = 0;

		if (count($contentOpf->manifest->item)>0) {
			// カバー画像
			foreach ($contentOpf->manifest->item as $item) {
				$id_str = (string)$item['id'];
				$href = (string)$item['href'];
				$media_type = (string)$item['media-type'];
				if ($id_str == $cover_id) {
					$covers = array();
					$src_path = $to_dir.'/'.$content_dir.'/'.$href;
					if (file_exists($src_path)) {
						$dest_dir = str_replace('[user_id]', $user_id, APP_CONST_COVER_IMAGE_DIR);
						@mkdir($dest_dir, 0705, true);
						$cover_name = basename($href);
						$ext = Util::getExtension($cover_name);
						$covers['cover_file'] = $cover_name;
						$covers['cover_path'] = uniqid($user_id.'_', true).'.'.$ext;
						$covers['cover_s_file'] = $cover_name;
						$covers['cover_s_path'] = preg_replace('/\.[a-z]+$/i', '_small.jpg', $covers['cover_path']);
						$small_path = $dest_dir.'/'.$covers['cover_s_path'];
						// 縮小版の生成
						$errmsg = '';
						if (Util::resizeImage($src_path, $small_path, APP_CONST_COVER_IAMGE_S_WIDTH, APP_CONST_COVER_IAMGE_S_HEIGHT, 'jpg', &$errmsg) === false) {
							throw new SpException($errmsg);
						}
						$covers['cover_s_size'] = filesize($small_path);
						// オリジナルの生成
						if (copy($src_path, $dest_dir.'/'.$covers['cover_path']) === false) {
							throw new SpException('カバー画像のコピーに失敗しました。');
						}
						$covers['cover_size'] = filesize($dest_dir.'/'.$covers['cover_path']);
						$publicationsDao->addValueStr(PublicationsDao::COL_COVER_FILE, $covers['cover_file']);
						$publicationsDao->addValueStr(PublicationsDao::COL_COVER_PATH, $covers['cover_path']);
						$publicationsDao->addValue(PublicationsDao::COL_COVER_SIZE, $covers['cover_size']);
						$publicationsDao->addValueStr(PublicationsDao::COL_COVER_S_FILE, $covers['cover_s_file']);
						$publicationsDao->addValueStr(PublicationsDao::COL_COVER_S_PATH, $covers['cover_s_path']);
						$publicationsDao->addValue(PublicationsDao::COL_COVER_S_SIZE, $covers['cover_s_size']);
						$publicationsDao->doInsert();
						$id = $publicationsDao->getLastInsertId();
						break;
					}
				}
			}
			// カバーが無かった場合の保護
			if ($id == 0) {
				$publicationsDao->doInsert();
				$id = $publicationsDao->getLastInsertId();
			}
			$publication_image_dir = str_replace(array('[user_id]', '[publication_id]'), array($user_id, $id), APP_CONST_PUBLICATION_IMAGE_DIR);
			@mkdir($publication_image_dir, 0705, true);
			$publication_image_path = str_replace(array('[user_id]', '[publication_id]'), array($user_id, $id), APP_CONST_PUBLICATION_IMAGE_PATH);
			$images = array();
			// 画像のみ
			foreach ($contentOpf->manifest->item as $item) {
				$id_str = (string)$item['id'];
				$href = (string)$item['href'];
				$media_type = (string)$item['media-type'];
				if (preg_match('/^image\/.+/i', $media_type)) {
					$src_path = $to_dir.'/'.$content_dir.'/'.$href;
					if (file_exists($src_path)) {
						$image_name = basename($href);
						//$ext = Util::getExtension($image_name);
						//$image_path = uniqid($user_id.'_', true).'.'.$ext;
						$image_path = $image_name;

						if (copy($src_path, $publication_image_dir.'/'.$image_path) === false) {
							throw new SpException('画像のコピーに失敗しました。');
						}
						$image_size = filesize($publication_image_dir.'/'.$image_path);

						$publicationImagesDao->addValue(PublicationImagesDao::COL_PUBLICATION_ID, $id);
						$publicationImagesDao->addValue(PublicationImagesDao::COL_USER_ID, $user_id);
						$publicationImagesDao->addValueStr(PublicationImagesDao::COL_IMAGE_TITLE, $image_name);
						$publicationImagesDao->addValueStr(PublicationImagesDao::COL_IMAGE_FILE, $image_name);
						$publicationImagesDao->addValueStr(PublicationImagesDao::COL_IMAGE_PATH, $image_path);
						$publicationImagesDao->addValue(PublicationImagesDao::COL_IMAGE_SIZE, $image_size);
						$publicationImagesDao->doInsert();
						$publicationImagesDao->reset();

						$images[$href] = $publication_image_path.'/'.$image_path;
					}
				}
			}
			$GLOBALS['_images'] = $images;
			$GLOBALS['_none_image_path'] = $publication_image_path;
			$GLOBALS['_none_images'] = array();
			$page_order = 0;
			$char_length = 0;
			// 本文
			foreach ($contentOpf->manifest->item as $item) {
				$id_str = (string)$item['id'];
				$href = (string)$item['href'];
				$media_type = (string)$item['media-type'];
				if ($media_type == 'application/xhtml+xml') {
					$src_path = $to_dir.'/'.$content_dir.'/'.$href;
					// カバーらしきものは除く
					if (file_exists($src_path) && strpos($href, 'cover.') === false) {
						$word_size = 0;
						$page_title = basename($href);
						$m = array('','','');
						$html_contents = file_get_contents($src_path);
						if ($html_contents !== false) {
							if (preg_match('/<title>([^<]+)<\/title>.*<body[^>]*>(.+)<\/body>/is', $html_contents, $m)) {
								$html_contents = null;
								$page_title = $m[1];
								$word_size = mb_strlen(preg_replace('/[　\s\r\n\t]+/u', '', strip_tags($m[2])));
								// 画像パスの変換
								if (count($GLOBALS['_images'])>0) {
									$m[2] = preg_replace_callback('/(<img [^>]+>)/i', "epub_convert_image_src", $m[2]);
								}
							} else {
								continue;
							}
						} else {
							continue;
						}
						$publicationPagesDao->addValue(PublicationPagesDao::COL_PUBLICATION_ID, $id);
						$publicationPagesDao->addValue(PublicationPagesDao::COL_USER_ID, $user_id);
						$publicationPagesDao->addValue(PublicationPagesDao::COL_PAGE_ORDER, $page_order);
						$publicationPagesDao->addValue(PublicationPagesDao::COL_PAGE_WORD_SIZE, $word_size);
						$publicationPagesDao->addValueStr(PublicationPagesDao::COL_PAGE_TITLE, $page_title);
						$publicationPagesDao->addValueStr(PublicationPagesDao::COL_PAGE_CONTENTS, $m[2]);
						$m = null;
						$publicationPagesDao->addValue(PublicationPagesDao::COL_CREATEDATE, Dao::DATE_NOW);
						$publicationPagesDao->addValue(PublicationPagesDao::COL_LASTUPDATE, Dao::DATE_NOW);
						$publicationPagesDao->doInsert();
						$publicationPagesDao->reset();
						$page_order++;
						$char_length += $word_size;
					}
				}
			}
			if (count($GLOBALS['_none_images'])>0) {
				foreach ($GLOBALS['_none_images'] as $old_path => $image_path) {
					$src_path = $to_dir.'/'.$content_dir.'/'.$old_path;
					if (file_exists($src_path)) {
						if (copy($src_path, $publication_image_dir.'/'.$image_path) === false) {
							throw new SpException('画像のコピーに失敗しました。');
						}
						$image_size = filesize($publication_image_dir.'/'.$image_path);

						$publicationImagesDao->addValue(PublicationImagesDao::COL_PUBLICATION_ID, $id);
						$publicationImagesDao->addValue(PublicationImagesDao::COL_USER_ID, $user_id);
						$publicationImagesDao->addValueStr(PublicationImagesDao::COL_IMAGE_TITLE, $image_path);
						$publicationImagesDao->addValueStr(PublicationImagesDao::COL_IMAGE_FILE, $image_path);
						$publicationImagesDao->addValueStr(PublicationImagesDao::COL_IMAGE_PATH, $image_path);
						$publicationImagesDao->addValue(PublicationImagesDao::COL_IMAGE_SIZE, $image_size);
						$publicationImagesDao->doInsert();
						$publicationImagesDao->reset();
					}
				}
			}
			if ($char_length>0) {
				$publicationsDao->reset();
				$publicationsDao->addValue(PublicationsDao::COL_CHAR_LENGTH, $char_length);
				$publicationsDao->addWhere(PublicationsDao::COL_PUBLICATION_ID, $id);
				$publicationsDao->addWhere(PublicationsDao::COL_USER_ID, $user_id);
				$publicationsDao->doUpdate();
			}
		}
		return $id;
	}
}
function epub_convert_image_src($matches)
{
	$img_tag = $matches[1];
	if (isset($GLOBALS['_images']) && count($GLOBALS['_images'])==0) return $img_tag;
	$images = $GLOBALS['_images'];
	if (preg_match('/ src="([^"]+)"/i', $img_tag, $m)) {
		$img_src = preg_replace('/^\.\.\//', '', $m[1]);
		if (isset($images[$img_src])) {
			$new_src = $images[$img_src];
		} else {
			$image_path = basename($img_src);
			$GLOBALS['_none_images'][$img_src] = $image_path;
			$new_src = $GLOBALS['_none_image_path'].'/'.$image_path;
		}
		return preg_replace('/ src=\"[^"]+\"/i', ' src="'.$new_src.'"', $img_tag);
	}
	return $img_tag;
}
?>
