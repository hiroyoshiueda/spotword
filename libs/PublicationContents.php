<?php
/**
 * PublicationContents
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class PublicationContents
{
	/**
	 * ファイルへ保存
	 * @param SpForm $form
	 * @param int $publication_id
	 * @param int $user_id
	 * @param int $num
	 */
	public static function putFile(&$form, $publication_id, $user_id, $num)
	{
		$data_dir = str_replace(array('[user_id]', '[publication_id]'), array($user_id, $publication_id), APP_CONST_PUBLICATION_CONTENTS_DIR);
		@mkdir($data_dir, 0700, true);
		$filename = 'page-'.$num;

		return file_put_contents($data_dir.'/'.$filename, $form->get('contents-'.$num), LOCK_EX);
	}

	/**
	 * ファイルから取得
	 * @param int $publication_id
	 * @param int $user_id
	 * @param int $num
	 */
	public static function getFile($publication_id, $user_id, $num)
	{
		$data_dir = str_replace(array('[user_id]', '[publication_id]'), array($user_id, $publication_id), APP_CONST_PUBLICATION_CONTENTS_DIR);
		$filename = 'page-'.$num;

		if (file_exists($data_dir.'/'.$filename)) {
			return file_get_contents($data_dir.'/'.$filename);
		} else {
			return '';
		}
	}

	/**
	 * ファイルの削除
	 * @param int $publication_id
	 * @param int $user_id
	 * @param int $num
	 */
	public static function deleteFile($publication_id, $user_id, $num)
	{
		$data_dir = str_replace(array('[user_id]', '[publication_id]'), array($user_id, $publication_id), APP_CONST_PUBLICATION_CONTENTS_DIR);
		$filename = 'page-'.$num;

		if (file_exists($data_dir.'/'.$filename)) {
			return @unlink($data_dir.'/'.$filename);
		} else {
			return true;
		}
	}

	/**
	 * 保存ディレクトリの取得
	 * @param int $publication_id
	 * @param int $user_id
	 */
	public static function getDataDir($publication_id, $user_id)
	{
		$data_dir = str_replace(array('[user_id]', '[publication_id]'), array($user_id, $publication_id), APP_CONST_PUBLICATION_CONTENTS_DIR);
		return $data_dir;
	}
}
?>
