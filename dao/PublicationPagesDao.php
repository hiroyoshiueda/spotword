<?php
/**
 * 出版ページデータ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class PublicationPagesDao extends BaseDao
{
	const TABLE_NAME = 'publication_pages';

const COL_PAGE_ID = "page_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_PUBLICATION_ID = "publication_id";
const COL_USER_ID = "user_id";
const COL_PAGE_ORDER = "page_order";
const COL_STATUS = "status";
const COL_TYPE = "type";
const COL_PAGE_WORD_SIZE = "page_word_size";
const COL_PAGE_TITLE = "page_title";
const COL_PAGE_CONTENTS = "page_contents";
const COL_PAGE_PATH = "page_path";
const COL_IMAGE_FILE = "image_file";
const COL_IMAGE_PATH = "image_path";
const COL_IMAGE_SIZE = "image_size";
const COL_CREATEDATE = "createdate";
const COL_LASTUPDATE = "lastupdate";
const COL_DELETEDATE = "deletedate";

	const STATUS_FINISH = 0;
	const STATUS_DRAFT = 1;

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList()
	{
		$this->addWhere(self::COL_DELETE_FLAG, 0);
		return $this->select();
	}

	public function getItem($page_id, $user_id=0)
	{
		$this->addWhere(self::COL_PAGE_ID, $page_id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		return $this->selectRow();
	}

	public function deleteItem($page_id, $user_id)
	{
		$this->addWhere(self::COL_PAGE_ID, $page_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}

	public function delete($publication_id, $user_id)
	{
		$this->addWhere(self::COL_PUBLICATION_ID, $publication_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}

	public function getItemList($publication_id, $user_id=0, $status=-1)
	{
		$this->addWhere(self::COL_PUBLICATION_ID, $publication_id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		if ($status>-1) $this->addWhere(self::COL_STATUS, $status);
		$this->addOrder(self::COL_PAGE_ORDER);
		return $this->select();
	}

	public function getMaxPageOrder($publication_id, $user_id)
	{
		$this->addSelectMax(self::COL_PAGE_ORDER, 'max');
		$this->addWhere(self::COL_PUBLICATION_ID, $publication_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->selectId();
	}
}
?>