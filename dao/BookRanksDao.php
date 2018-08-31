<?php
/**
 * 本ランク
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BookRanksDao extends BaseDao
{
	const TABLE_NAME = 'book_ranks';

const COL_BOOK_ID = "book_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_USER_ID = "user_id";
const COL_STATUS = "status";
const COL_LATEST_VERSION = "latest_version";
const COL_RANK_POINT = "rank_point";
const COL_PV_TOTAL = "pv_total";
const COL_PV_TODAY = "pv_today";
const COL_PV_1 = "pv_1";
const COL_PV_2 = "pv_2";
const COL_PV_3 = "pv_3";
const COL_PV_4 = "pv_4";
const COL_PV_5 = "pv_5";
const COL_PV_6 = "pv_6";
const COL_PV_7 = "pv_7";
const COL_EPUB_TOTAL = "epub_total";
const COL_EPUB_TODAY = "epub_today";
const COL_EPUB_1 = "epub_1";
const COL_EPUB_2 = "epub_2";
const COL_EPUB_3 = "epub_3";
const COL_EPUB_4 = "epub_4";
const COL_EPUB_5 = "epub_5";
const COL_EPUB_6 = "epub_6";
const COL_EPUB_7 = "epub_7";
const COL_COMMENT_TOTAL = "comment_total";
const COL_EVALUATE_TOTAL = "evaluate_total";
const COL_EVALUATE_GOOD = "evaluate_good";
const COL_EVALUATE_BAD = "evaluate_bad";

	const STATUS_PUBLIC = 0;
	const STATUS_CLOSED = 1;

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList()
	{
		$this->addWhere(self::COL_DELETE_FLAG, 0);
		return $this->select();
	}

	public function getItem($book_id, $user_id=0)
	{
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		if ($user_id>0) $this->addWhere(self::COL_USER_ID, $user_id);
		return $this->selectRow();
	}

	public function delete($book_id, $user_id)
	{
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}

	public function countPv($book_id)
	{
		$this->addValue(self::COL_PV_TOTAL, self::COL_PV_TOTAL.'+1');
		$this->addValue(self::COL_PV_TODAY, self::COL_PV_TODAY.'+1');
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		return $this->doUpdate();
	}

	public function countEpub($book_id)
	{
		$this->addValue(self::COL_EPUB_TOTAL, self::COL_EPUB_TOTAL.'+1');
		$this->addValue(self::COL_EPUB_TODAY, self::COL_EPUB_TODAY.'+1');
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		return $this->doUpdate();
	}

	public function countComment($book_id)
	{
		$this->addValue(self::COL_COMMENT_TOTAL, self::COL_COMMENT_TOTAL.'+1');
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		return $this->doUpdate();
	}

	public function countEvaluateGood($book_id)
	{
		$this->addValue(self::COL_EVALUATE_TOTAL, self::COL_EVALUATE_TOTAL.'+1');
		$this->addValue(self::COL_EVALUATE_GOOD, self::COL_EVALUATE_GOOD.'+1');
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		return $this->doUpdate();
	}

	public function countEvaluateBad($book_id)
	{
		$this->addValue(self::COL_EVALUATE_TOTAL, self::COL_EVALUATE_TOTAL.'-1');
		$this->addValue(self::COL_EVALUATE_BAD, self::COL_EVALUATE_BAD.'+1');
		$this->addWhere(self::COL_BOOK_ID, $book_id);
		return $this->doUpdate();
	}

	/**
	 * 人気の本
	 */
	public function setPopularBooks()
	{
		$this->addSelect('b.*');
		$this->addSelect('br.'.self::COL_PV_TOTAL);
		$this->addSelect('br.'.self::COL_PV_TODAY);
		$this->addSelect('br.'.self::COL_EPUB_TOTAL);
		$this->addSelect('br.'.self::COL_EPUB_TODAY);
		$this->addSelect('br.'.self::COL_COMMENT_TOTAL);
		$this->addSelect('br.'.self::COL_EVALUATE_TOTAL);
		$this->setTable(self::TABLE_NAME, 'br');
		$this->addTableJoin(BooksDao::TABLE_NAME, 'b', 'br.book_id=b.book_id');
		$this->addWhere('b.'.BooksDao::COL_STATUS, BooksDao::STATUS_PUBLIC);
		$this->addWhere('b.'.BooksDao::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder('br.'.self::COL_RANK_POINT, 'DESC');
		return;
	}

	/**
	 * 新着の本
	 */
	public function setNewBooks()
	{
		$this->addSelect('b.*');
		$this->addSelect('br.'.self::COL_PV_TOTAL);
		$this->addSelect('br.'.self::COL_PV_TODAY);
		$this->addSelect('br.'.self::COL_EPUB_TOTAL);
		$this->addSelect('br.'.self::COL_EPUB_TODAY);
		$this->addSelect('br.'.self::COL_COMMENT_TOTAL);
		$this->addSelect('br.'.self::COL_EVALUATE_TOTAL);
		$this->setTable(self::TABLE_NAME, 'br');
		$this->addTableJoin(BooksDao::TABLE_NAME, 'b', 'br.book_id=b.book_id');
		$this->addWhere('b.'.BooksDao::COL_STATUS, BooksDao::STATUS_PUBLIC);
		$this->addWhere('b.'.BooksDao::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addOrder('b.'.BooksDao::COL_PUBLISH_DATE, 'DESC');
		$this->addOrder('b.'.BooksDao::COL_LASTUPDATE, 'DESC');
		return;
	}

	public function getListByIds($ids)
	{
		$cnt = count($ids);
		if ($cnt == 0) {
			return array();
		} else if ($cnt == 1) {
			$this->addWhere(self::COL_BOOK_ID, $ids[0]);
		} else {
			$this->addWhereIn(self::COL_BOOK_ID, $ids);
		}
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		return $this->select();
	}
//
//	public function getCategoryList()
//	{
//		$this->addSelect('b.'.BooksDao::COL_CATEGORY_ID);
//		$this->addSelectCount('b.'.BooksDao::COL_BOOK_ID, 'cnt');
//		$this->setTable(self::TABLE_NAME, 'br');
//		$this->addTableJoin(BooksDao::TABLE_NAME, 'b', 'br.book_id=b.book_id');
//		$this->addWhere('b.'.BooksDao::COL_STATUS, self::STATUS_PUBLIC);
//		$this->addWhere('b.'.BooksDao::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
//		$this->addGroupBy(self::COL_CATEGORY_ID);
//		$this->addOrder(self::COL_CATEGORY_ID);
//		return $this->select();
//	}
}
?>