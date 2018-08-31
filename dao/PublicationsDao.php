<?php
/**
 * 出版データ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class PublicationsDao extends BaseDao
{
	const TABLE_NAME = 'publications';

const COL_PUBLICATION_ID = "publication_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_USER_ID = "user_id";
const COL_STATUS = "status";
const COL_BOOK_ID = "book_id";
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
const COL_PUBLISH_MODIFY_FLAG = "publish_modify_flag";
const COL_PUBLICATION_KEY = "publication_key";
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
		$this->addWhere(self::COL_DELETE_FLAG, 0);
		$this->addWhere(self::COL_USER_ID, $user_id);
		$this->addOrder(self::COL_LASTUPDATE, 'DESC');
		return $this->select();
	}

	public function getItem($id, $user_id=0)
	{
		$this->addWhere(self::COL_DELETE_FLAG, 0);
		$this->addWhere(self::COL_PUBLICATION_ID, $id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		return $this->selectRow();
	}

	public function delete($id, $user_id)
	{
		$this->addWhere(self::COL_PUBLICATION_ID, $id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}
}
?>