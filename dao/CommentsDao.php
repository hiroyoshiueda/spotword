<?php
/**
 * コメントデータ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class CommentsDao extends BaseDao
{
	const TABLE_NAME = 'comments';

const COL_COMMENT_ID = "comment_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_DISPLAY_FLAG = "display_flag";
const COL_BOOK_ID = "book_id";
const COL_USER_ID = "user_id";
const COL_PARENT_ID = "parent_id";
const COL_BODY = "body";
const COL_POST_USER_ID = "post_user_id";
const COL_POST_USER_NAME = "post_user_name";
const COL_POST_USER_URL = "post_user_url";
const COL_EVALUATE = "evaluate";
const COL_SPAM_FLAG = "spam_flag";
const COL_POST_IP = "post_ip";
const COL_POST_AGENT = "post_agent";
const COL_CREATEDATE = "createdate";
const COL_LASTUPDATE = "lastupdate";
const COL_DELETEDATE = "deletedate";

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList($book_id)
	{
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder(self::COL_CREATEDATE, 'DESC');
		return $this->select();
	}

	public function getItem($id)
	{
		$this->addWhere(self::COL_COMMENT_ID, $id);
		return $this->selectRow();
	}

	public function delete($id, $user_id=0)
	{
		$this->addWhere(self::COL_COMMENT_ID, $id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}

	public function setJoinUser($book_id)
	{
		$this->addSelect('c.*');
		$this->addSelect('u.'.UsersDao::COL_LOGIN);
		$this->addSelect('u.'.UsersDao::COL_PENNAME);
		$this->addSelectAs('u.'.UsersDao::COL_PROFILE_S_PATH, 'profile_path');
		$this->setTable(self::TABLE_NAME, 'c');
		$this->addTableJoin(UsersDao::TABLE_NAME, 'u', 'c.post_user_id=u.user_id');
		$this->addWhere('c.'.self::COL_BOOK_ID, $book_id);
		$this->addWhere('c.'.self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere('c.'.self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addWhere('u.'.UsersDao::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder('c.'.self::COL_CREATEDATE, 'DESC');
		return;
	}

	public function getCountByBookIds($book_id_arr)
	{
		$this->addSelect(self::COL_BOOK_ID);
		$this->addSelectCount(self::COL_BOOK_ID, 'total');
		if (count($book_id_arr)>1) {
			$this->addWhereIn(self::COL_BOOK_ID, $book_id_arr);
		} else {
			$this->addWhere(self::COL_BOOK_ID, $book_id_arr[0]);
		}
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addGroupBy(self::COL_BOOK_ID);
		return $this->select();
	}

	public function getListByUserId($user_id)
	{
		$this->addSelect('c.*');
		$this->addSelect('u.'.UsersDao::COL_LOGIN);
		$this->addSelect('u.'.UsersDao::COL_PENNAME);
		$this->addSelectAs('u.'.UsersDao::COL_PROFILE_S_PATH, 'profile_path');
		$this->setTable(self::TABLE_NAME, 'c');
		$this->addTableJoin(UsersDao::TABLE_NAME, 'u', 'c.post_user_id=u.user_id');
		$this->addWhere('c.'.self::COL_USER_ID, $user_id);
		$this->addWhere('c.'.self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere('c.'.self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addWhere('u.'.UsersDao::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder('c.'.self::COL_CREATEDATE, 'DESC');
		return $this->select();
	}
}
?>