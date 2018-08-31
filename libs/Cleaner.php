<?php
/**
 * もろもろの掃除
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class Cleaner
{
	/**
	 * tmpディレクトリの削除
	 * @param int $days $days日以前のファイルを削除
	 */
	public static function tempDir($days)
	{
		$files = array();
		UtilFile::readDirFile(&$files, APP_WWW_DIR . '/tmp');
		UtilFile::readDirFile(&$files, APP_DIR . '/tmp');
		if (count($files)>0) {
			clearstatcache();
			$remove_ts = time() - ($days * 86400);
			foreach ($files as $f) {
				if (file_exists($f) && is_file($f)) {
					//echo $f.' --> '.date('Y-m-d h:i:s', filemtime($f))."\n";
					if (filemtime($f) < $remove_ts) @unlink($f);
				}
			}
		}
		return;
	}

	/**
	 * tmpユーザーの削除
	 * @param DbManager $db
	 * @param int $days 登録日が$days日以前のユーザーを削除
	 */
	public static function tempUser(&$db, $days)
	{
		Sp::import('UsersDao', 'dao', true);

		$remove_date = date('Y-m-d', time() - ($days * 86400));

		$usersDao = new UsersDao($db);
		$usersDao->addWhere(UsersDao::COL_STATUS, UsersDao::STATUS_TEMP);
		$usersDao->addWhereStr(UsersDao::COL_CREATEDATE, $remove_date, '<');
		return $usersDao->doDelete();
	}


	/**
	 * tmpページデータの削除
	 * @param DbManager $db
	 * @param int $days 更新日が$days日以前のデータを削除
	 */
	public static function tempPublicationPage(&$db, $days)
	{
		Sp::import('PublicationPageTempsDao', 'dao', true);

		$remove_date = date('Y-m-d', time() - ($days * 86400));

		$tempsDao = new PublicationPageTempsDao($db);
		$tempsDao->addWhereStr(PublicationPageTempsDao::COL_LASTUPDATE, $remove_date, '<');
		return $tempsDao->doDelete();
	}
}
?>
