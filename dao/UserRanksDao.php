<?php
/**
 * 作家別集計データ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserRanksDao extends BaseDao
{
	const TABLE_NAME = 'user_ranks';

const COL_USER_ID = "user_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_DISPLAY_FLAG = "display_flag";
const COL_RANK_POINT = "rank_point";
const COL_BOOK_TOTAL = "book_total";

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList()
	{
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder(self::COL_RANK_POINT);
		return $this->select();
	}

	public function getItem($id)
	{
		$this->addWhere(self::COL_USER_ID, $id);
		return $this->selectRow();
	}

	public function delete($id)
	{
		$this->addWhere(self::COL_USER_ID, $id);
		return $this->doDelete();
	}

	public function deleteAll()
	{
		return $this->doDelete();
	}

	public function getListJoinUser()
	{
		$this->addSelect('ur.*');
		$this->addSelect('u.'.UsersDao::COL_LOGIN);
		$this->addSelect('u.'.UsersDao::COL_PENNAME);
		$this->addSelect('u.'.UsersDao::COL_PROFILE_MSG);
		$this->addSelect('u.'.UsersDao::COL_PROFILE_FILE);
		$this->addSelect('u.'.UsersDao::COL_PROFILE_PATH);

		$this->setTable(self::TABLE_NAME, 'ur');
		$this->addTableJoin(UsersDao::TABLE_NAME, 'u', 'ur.user_id=u.user_id');

		$this->addWhere('ur.'.self::COL_BOOK_TOTAL, 0, '>');
		$this->addWhere('u.'.UsersDao::COL_STATUS, UsersDao::STATUS_REGULAR);
		$this->addWhere('u.'.UsersDao::COL_DISPLAY_FLAG, UsersDao::DISPLAY_FLAG_ON);

		$this->addOrder('ur.'.self::COL_RANK_POINT, 'DESC');

		return $this->select();
	}
}
?>