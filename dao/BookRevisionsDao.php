<?php
/**
 * 改訂履歴
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BookRevisionsDao extends BaseDao
{
	const TABLE_NAME = 'book_revisions';

const COL_BOOK_REVISION_ID = "book_revision_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_DISPLAY_FLAG = "display_flag";
const COL_USER_ID = "user_id";
const COL_BOOK_ID = "book_id";
const COL_VERSION = "version";
const COL_REVISION_DATE = "revision_date";
const COL_REVISION_BODY = "revision_body";
const COL_CREATEDATE = "createdate";
const COL_LASTUPDATE = "lastupdate";
const COL_DELETEDATE = "deletedate";

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList($book_id, $user_id=0)
	{
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		$this->addWhere(self::COL_DELETE_FLAG, 0);
		return $this->select();
	}

	public function getItem($book_id, $version, $user_id=0)
	{
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		$this->addWhere(self::COL_VERSION, $version);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		$this->addWhere(self::COL_DELETE_FLAG, 0);
		return $this->selectRow();
	}

	public function delete($book_id, $user_id)
	{
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}
}
?>