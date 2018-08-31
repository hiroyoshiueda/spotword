<?php
/**
 * 本データ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BooksDao extends BaseDao
{
	const TABLE_NAME = 'books';

const COL_BOOK_ID = "book_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_USER_ID = "user_id";
const COL_STATUS = "status";
const COL_VERSION = "version";
const COL_LATEST_VERSION = "latest_version";
const COL_CATEGORY_ID = "category_id";
const COL_TITLE = "title";
const COL_SUBTITLE = "subtitle";
const COL_DESCRIPTION = "description";
const COL_AUTHOR = "author";
const COL_PUBLISHER = "publisher";
const COL_KEYWORDS = "keywords";
const COL_CONTENTS = "contents";
const COL_COVER_FILE = "cover_file";
const COL_COVER_PATH = "cover_path";
const COL_COVER_SIZE = "cover_size";
const COL_COVER_S_FILE = "cover_s_file";
const COL_COVER_S_PATH = "cover_s_path";
const COL_COVER_S_SIZE = "cover_s_size";
const COL_COMMENT_FLAG = "comment_flag";
const COL_EPUB_FLAG = "epub_flag";
const COL_COPYRIGHT_FLAG = "copyright_flag";
const COL_COPYRIGHT_DATE = "copyright_date";
const COL_CHAR_LENGTH = "char_length";
const COL_CHARGE_FLAG = "charge_flag";
const COL_PRICE = "price";
const COL_PROFIT = "profit";
const COL_FEE = "fee";
const COL_PUBLISH_DATE = "publish_date";
const COL_CREATEDATE = "createdate";
const COL_LASTUPDATE = "lastupdate";
const COL_DELETEDATE = "deletedate";

	const STATUS_PUBLIC = 0;
	const STATUS_CLOSED = 1;

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getListByUser($user_id)
	{
		$this->addWhere(self::COL_USER_ID, $user_id);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder(self::COL_LASTUPDATE, 'DESC');
		return $this->select();
	}

	public function getItem($id, $user_id=0)
	{
		$this->addWhere(self::COL_BOOK_ID, $id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		return $this->selectRow();
	}

	public function delete($id, $user_id)
	{
		$this->addWhere(self::COL_BOOK_ID, $id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}

	public function copyFromPublication(&$publication)
	{
		$this->addValue(self::COL_USER_ID, $publication[PublicationsDao::COL_USER_ID]);
		$this->addValue(self::COL_VERSION, $publication[PublicationsDao::COL_VERSION]);
		$this->addValue(self::COL_LATEST_VERSION, $publication[PublicationsDao::COL_LATEST_VERSION]);
		$this->addValue(self::COL_CATEGORY_ID, $publication[PublicationsDao::COL_CATEGORY_ID]);
		$this->addValueStr(self::COL_TITLE, $publication[PublicationsDao::COL_TITLE]);
		$this->addValueStr(self::COL_SUBTITLE, $publication[PublicationsDao::COL_SUBTITLE]);
		$this->addValueStr(self::COL_DESCRIPTION, $publication[PublicationsDao::COL_DESCRIPTION]);
		$this->addValueStr(self::COL_AUTHOR, $publication[PublicationsDao::COL_AUTHOR]);
		$this->addValueStr(self::COL_PUBLISHER, $publication[PublicationsDao::COL_PUBLISHER]);
		$this->addValueStr(self::COL_KEYWORDS, $publication[PublicationsDao::COL_KEYWORDS]);
		//$this->addValueStr(self::COL_CONTENTS, $publication[PublicationsDao::COL_CONTENTS]);
		$this->addValueStr(self::COL_COVER_FILE, $publication[PublicationsDao::COL_COVER_FILE]);
		$this->addValueStr(self::COL_COVER_PATH, $publication[PublicationsDao::COL_COVER_PATH]);
		$this->addValue(self::COL_COVER_SIZE, $publication[PublicationsDao::COL_COVER_SIZE]);
		$this->addValueStr(self::COL_COVER_S_FILE, $publication[PublicationsDao::COL_COVER_S_FILE]);
		$this->addValueStr(self::COL_COVER_S_PATH, $publication[PublicationsDao::COL_COVER_S_PATH]);
		$this->addValue(self::COL_COVER_S_SIZE, $publication[PublicationsDao::COL_COVER_S_SIZE]);
		$this->addValue(self::COL_COMMENT_FLAG, $publication[PublicationsDao::COL_COMMENT_FLAG]);
		$this->addValue(self::COL_EPUB_FLAG, $publication[PublicationsDao::COL_EPUB_FLAG]);
		$this->addValue(self::COL_COPYRIGHT_FLAG, $publication[PublicationsDao::COL_COPYRIGHT_FLAG]);
		$this->addValueStr(self::COL_COPYRIGHT_DATE, $publication[PublicationsDao::COL_COPYRIGHT_DATE]);
		$this->addValue(self::COL_CHAR_LENGTH, $publication[PublicationsDao::COL_CHAR_LENGTH]);
		$this->addValue(self::COL_CHARGE_FLAG, $publication[PublicationsDao::COL_CHARGE_FLAG]);
		$this->addValue(self::COL_PRICE, $publication[PublicationsDao::COL_PRICE]);
		$this->addValue(self::COL_PROFIT, $publication[PublicationsDao::COL_PROFIT]);
		$this->addValue(self::COL_FEE, $publication[PublicationsDao::COL_FEE]);
		return;
	}

	public function setNewList()
	{
		$this->addWhere(self::COL_STATUS, self::STATUS_PUBLIC);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder(self::COL_PUBLISH_DATE, 'DESC');
		$this->addOrder(self::COL_LASTUPDATE, 'DESC');
		return;
	}

	public function getListByBookIds($book_ids)
	{
		$cnt = count($book_ids);
		if ($cnt == 0) {
			return array();
		} else if ($cnt == 1) {
			$this->addWhere(self::COL_BOOK_ID, $book_ids[0]);
		} else {
			$this->addWhereIn(self::COL_BOOK_ID, $book_ids);
		}
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		return $this->select();
	}

	public function getCategoryList()
	{
		$this->addSelect(self::COL_CATEGORY_ID);
		$this->addSelectCount(self::COL_BOOK_ID, 'cnt');
		$this->addWhere(self::COL_STATUS, self::STATUS_PUBLIC);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addGroupBy(self::COL_CATEGORY_ID);
		//$this->addOrder(self::COL_CATEGORY_ID);
		return $this->select();
	}
}
?>