<?php
/**
 * マイ本棚
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BookShelfsDao extends BaseDao
{
	const TABLE_NAME = 'book_shelfs';

const COL_BOOK_SHELF_ID = "book_shelf_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_DISPLAY_FLAG = "display_flag";
const COL_USER_ID = "user_id";
const COL_BOOK_ID = "book_id";
const COL_LASTUPDATE = "lastupdate";

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList()
	{
		$this->addWhere(self::COL_DELETE_FLAG, 0);
		return $this->select();
	}

	public function getItem($id, $user_id=0)
	{
		$this->addWhere(self::COL_PAGE_ID, $id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		return $this->selectRow();
	}

	public function delete($publication_id, $user_id)
	{
		$this->addWhere(self::COL_PUBLICATION_ID, $publication_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}
}
?>