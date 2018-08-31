<?php
/**
 * ジャンル別集計データ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class CategoryRanksDao extends BaseDao
{
	const TABLE_NAME = 'category_ranks';

const COL_CATEGORY_ID = "category_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_DISPLAY_FLAG = "display_flag";
const COL_CATEGORY_NAME = "category_name";
const COL_TOTAL = "total";
const COL_BOOK_ID = "book_id";
const COL_BOOK_DATA = "book_data";

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList()
	{
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder(self::COL_CATEGORY_ID);
		return $this->select();
	}

	public function getItem($id)
	{
		$this->addWhere(self::COL_CATEGORY_ID, $id);
		return $this->selectRow();
	}

	public function delete($id)
	{
		$this->addWhere(self::COL_CATEGORY_ID, $id);
		return $this->doDelete();
	}

	public function deleteAll()
	{
		return $this->doDelete();
	}

	public function getListJoinBook()
	{
		$this->addSelect('cr.'.self::COL_CATEGORY_NAME);
		$this->addSelect('cr.'.self::COL_BOOK_DATA);
		$this->addSelect('b.*');

		$this->setTable(self::TABLE_NAME, 'cr');
		$this->addTableJoin(BooksDao::TABLE_NAME, 'b', 'cr.book_id=b.book_id');

		$this->addWhere('cr.'.self::COL_BOOK_ID, 0, '>');
		$this->addWhere('b.'.BooksDao::COL_STATUS, BooksDao::STATUS_PUBLIC);

		$this->addOrder('cr.'.self::COL_CATEGORY_ID);
		return $this->select();
	}
}
?>