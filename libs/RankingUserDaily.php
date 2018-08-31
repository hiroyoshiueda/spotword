<?php
Sp::import('BooksDao', 'dao');
Sp::import('BookRanksDao', 'dao');
Sp::import('UserRanksDao', 'dao');
/**
 * 毎日実行する作家別ランキング集計バッチ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class RankingUserDaily
{
	/**
	 * @var DbManager
	 */
	private $db;

	/**
	 * @var SpLogger
	 */
	private $logger;

	public function __construct(&$db, &$logger)
	{
		$this->db =& $db;
		$this->logger =& $logger;
	}

	public function execute()
	{
		$this->logger->info('RankingUserDaily start.');

		$bookRanksDao = new BookRanksDao($this->db);
		$bookRanksDao->addSelect('br.'.BookRanksDao::COL_RANK_POINT);
		$bookRanksDao->setPopularBooks();
		$list = $bookRanksDao->select();

		$point = array();
		$total = array();
		if (count($list)>0) {
			// ランクポイントの総数順にユーザーを並び替え
			foreach ($list as $i => $d) {
				$user_id = $d['user_id'];
				$point[$user_id] = (isset($point[$user_id])) ? $point[$user_id] + $d['rank_point'] : $d['rank_point'];
				$total[$user_id] = (isset($total[$user_id])) ? $total[$user_id] + 1 : 1;
			}
//			arsort(&$point, SORT_NUMERIC);
			$UserRanksDao = new UserRanksDao($this->db);
			$UserRanksDao->deleteAll();
			$UserRanksDao->reset();
			foreach ($point as $user_id => $rank_point) {
				//$rank_point_avg = ($rank_point>0) ? ceil($rank_point / $total[$user_id]) : 0;
				$rank_point_avg = $rank_point;

				$UserRanksDao->addValue(UserRanksDao::COL_USER_ID, $user_id);
				$UserRanksDao->addValue(UserRanksDao::COL_RANK_POINT, $rank_point_avg);
				$UserRanksDao->addValue(UserRanksDao::COL_BOOK_TOTAL, $total[$user_id]);
				$UserRanksDao->doInsert();
				$UserRanksDao->reset();
			}
		}

		$this->logger->info('RankingUserDaily end.');

		return true;
	}
}
?>
