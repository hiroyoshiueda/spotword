<?php
/**
 * 出版ページデータ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BookPagesDao extends BaseDao
{
	const TABLE_NAME = 'book_pages';

const COL_PAGE_ID = "page_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_BOOK_ID = "book_id";
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

	public function delete($book_id, $user_id=0, $page_order=-1)
	{
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		if ($page_order>-1) $this->addWhere(self::COL_PAGE_ORDER, $page_order);
		return $this->doDelete();
	}

	public function getItemList($book_id, $user_id=0)
	{
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		$this->addOrder(self::COL_PAGE_ORDER);
		return $this->select();
	}

	public function getMaxPageOrder($book_id, $user_id)
	{
		$this->addSelectMax(self::COL_PAGE_ORDER, 'max');
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->selectId();
	}

	public function copyFormPublicationPage(&$page)
	{
		$this->addValue(self::COL_USER_ID, $page[PublicationPagesDao::COL_USER_ID]);
		$this->addValue(self::COL_STATUS, $page[PublicationPagesDao::COL_STATUS]);
		$this->addValue(self::COL_TYPE, $page[PublicationPagesDao::COL_TYPE]);
		$this->addValue(self::COL_PAGE_WORD_SIZE, $page[PublicationPagesDao::COL_PAGE_WORD_SIZE]);
		$this->addValueStr(self::COL_PAGE_TITLE, $page[PublicationPagesDao::COL_PAGE_TITLE]);
		$this->addValueStr(self::COL_PAGE_CONTENTS, $page[PublicationPagesDao::COL_PAGE_CONTENTS]);
		$this->addValueStr(self::COL_PAGE_PATH, $page[PublicationPagesDao::COL_PAGE_PATH]);
		$this->addValueStr(self::COL_IMAGE_FILE, $page[PublicationPagesDao::COL_IMAGE_FILE]);
		$this->addValueStr(self::COL_IMAGE_PATH, $page[PublicationPagesDao::COL_IMAGE_PATH]);
		$this->addValue(self::COL_IMAGE_SIZE, $page[PublicationPagesDao::COL_IMAGE_SIZE]);
	}

	public function getBookItem($book_id, $page_order=-1)
	{
		$this->addSelect('bp.'.self::COL_PAGE_TITLE);
		$this->addSelect('bp.'.self::COL_PAGE_CONTENTS);
		$this->addSelect('bp.'.self::COL_PAGE_ORDER);
		$this->addSelect('b.*');
		$this->setTable(self::TABLE_NAME, 'bp');
		$this->addTableJoin(BooksDao::TABLE_NAME, 'b', 'bp.book_id=b.book_id');
		$this->addWhere('bp.'.self::COL_BOOK_ID, $book_id);
		if ($page_order>-1) $this->addWhere('bp.'.self::COL_PAGE_ORDER, $page_order);
		$this->addWhere('b.'.BooksDao::COL_STATUS, BooksDao::STATUS_PUBLIC);
		$this->addWhere('b.'.BooksDao::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		if ($page_order>-1) $this->addOrder('bp.'.self::COL_PAGE_ORDER);
		return $this->selectRow();
	}
}
?>