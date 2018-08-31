<?php
/**
 * ユーザーデータ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UsersDao extends BaseDao
{
	const TABLE_NAME = 'users';

	const COL_USER_ID = "user_id";
	const COL_DELETE_FLAG = "delete_flag";
	const COL_DISPLAY_FLAG = "display_flag";
	const COL_STATUS = "status";
	const COL_EMAIL = "email";
	const COL_LOGIN = "login";
	const COL_PASSWORD = "password";
	const COL_PENNAME = "penname";
	const COL_BIRTHDAY = "birthday";
	const COL_BIRTHDAY_PUBLIC = "birthday_public";
	const COL_GENDER = "gender";
	const COL_GENDER_PUBLIC = "gender_public";
	const COL_ZIP = "zip";
	const COL_AREA = "area";
	const COL_URL = "url";
	const COL_TWITTER_ID = "twitter_id";
	const COL_TWITTER_PUBLIC = "twitter_public";
	const COL_TEMP_KEY = "temp_key";
	const COL_MELMAGA_SYSTEM = "melmaga_system";
	const COL_MELMAGA_BASIC = "melmaga_basic";
	const COL_PROFILE_FILE = "profile_file";
	const COL_PROFILE_PATH = "profile_path";
	const COL_PROFILE_SIZE = "profile_size";
	const COL_PROFILE_S_FILE = "profile_s_file";
	const COL_PROFILE_S_PATH = "profile_s_path";
	const COL_PROFILE_S_SIZE = "profile_s_size";
	const COL_PROFILE_B_FILE = "profile_b_file";
	const COL_PROFILE_B_PATH = "profile_b_path";
	const COL_PROFILE_B_SIZE = "profile_b_size";
	const COL_PROFILE_MSG = "profile_msg";
	const COL_CHANGE_EMAIL = "change_email";
	const COL_USE_MYDESK = "use_mydesk";
	const COL_OPEN_LOGIN = "open_login";
	const COL_OPEN_ID = "open_id";
	const COL_OPEN_IMAGE_URL = "open_image_url";
	const COL_OPEN_DATA = "open_data";
	const COL_CREATEDATE = "createdate";
	const COL_LASTUPDATE = "lastupdate";
	const COL_DELETEDATE = "deletedate";

	const STATUS_REGULAR = 0;
	const STATUS_TEMP = 1;

	const OPEN_LOGIN_NORMAL = 0;
	const OPEN_LOGIN_TWITTER = 1;
	const OPEN_LOGIN_MIXI = 2;

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList($status)
	{
		$this->addWhere(self::COL_STATUS, $status);
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder(self::COL_CREATEDATE, 'DESC');
		return $this->select();
	}

	public function getItem($id, $status=-1)
	{
		$this->addWhere(self::COL_USER_ID, $id);
		if ($status>-1) $this->addWhere(self::COL_STATUS, $status);
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		return $this->selectRow();
	}

	public function delete($id)
	{
		$this->addWhere(self::COL_USER_ID, $id);
		return $this->doDelete();
	}

	public function getItemByLogin($login)
	{
		$this->addWhere(self::COL_STATUS, self::STATUS_REGULAR);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addWhere(self::COL_OPEN_LOGIN, self::OPEN_LOGIN_NORMAL);
		$this->addWhereStr(self::COL_LOGIN, $login);
		return $this->selectRow();
	}

	public function getItemByLoginAndEmail($login, $email)
	{
		$this->addWhere(self::COL_STATUS, self::STATUS_REGULAR);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addWhere(self::COL_OPEN_LOGIN, self::OPEN_LOGIN_NORMAL);
		$this->addWhereStr(self::COL_LOGIN, $login);
		$this->addWhereStr(self::COL_EMAIL, $email);
		return $this->selectRow();
	}

	public function getItemByTempKey($key)
	{
		$this->addWhereStr(self::COL_TEMP_KEY, $key);
		$this->addWhere(self::COL_STATUS, self::STATUS_TEMP);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$ts = time() - APP_CONST_REGIST_FIRST_TIME;
		$this->addWhereStr(self::COL_CREATEDATE, date('Y-m-d H:i:s', $ts), '>=');
		return $this->selectRow();
	}

	public function getItemByTempKeyRegular($key)
	{
		$this->addWhereStr(self::COL_TEMP_KEY, $key);
		$this->addWhere(self::COL_STATUS, self::STATUS_REGULAR);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		return $this->selectRow();
	}

	public function isDuplicationByLogin($login, $id=0)
	{
		$this->addSelectCount('*', 'cnt');
		$this->addWhereStr(self::COL_LOGIN, $login);
		$this->addWhere(self::COL_STATUS, self::STATUS_REGULAR);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		if ($id > 0) $this->addWhere(self::COL_USER_ID, $id, '!=');
		return ($this->selectId()>0);
	}

	public function isDuplicationByPenname($penname, $id=0)
	{
		$this->addSelectCount('*', 'cnt');
		$this->addWhere(self::COL_STATUS, self::STATUS_REGULAR);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addWhereStr(self::COL_PENNAME, $penname);
		if ($id > 0) $this->addWhere(self::COL_USER_ID, $id, '!=');
		return ($this->selectId()>0);
	}

	public function isDuplicationByEmail($email, $id=0)
	{
		$this->addSelectCount('*', 'cnt');
		$this->addWhere(self::COL_STATUS, self::STATUS_REGULAR);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addWhereStr(self::COL_EMAIL, $email);
		if ($id > 0) $this->addWhere(self::COL_USER_ID, $id, '!=');
		return ($this->selectId()>0);
	}

//	public function loadPennameAndEmail(&$penname_arr, &$email_arr, $id=0)
//	{
//		$this->addSelect(self::COL_PENNAME);
//		$this->addSelect(self::COL_EMAIL);
//		$this->addWhere(self::COL_STATUS, self::STATUS_REGULAR);
//		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
//		if ($id > 0) $this->addWhere(self::COL_USER_ID, $id, '!=');
//		$penname_arr = array();
//		$email_arr = array();
//		$rows = array();
//		$dataSet = $this->doSelect();
//		while ($dataSet->next()) {
//			$rows = $dataSet->getRowSet();
//			$penname_arr[] = $rows[self::COL_PENNAME];
//			$email_arr[] = $rows[self::COL_EMAIL];
//		}
//		return;
//	}

	public function getListByIds($ids)
	{
		$cnt = count($ids);
		if ($cnt == 0) {
			return array();
		} else if ($cnt == 1) {
			$this->addWhere(self::COL_USER_ID, $ids[0]);
		} else {
			$this->addWhereIn(self::COL_USER_ID, $ids);
		}
		$this->addWhere(self::COL_STATUS, self::STATUS_REGULAR);
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		return $this->select();
	}
}
?>