<?php
Sp::import('BookRanksDao', 'dao');
/**
 * 毎日実行するランキング集計バッチ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class RankingDaily
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
		$this->logger->info('RankingDaily start.');

		$bookRanksDao = new BookRanksDao($this->db);
		$bookRanksDao->addWhere(BookRanksDao::COL_STATUS, BookRanksDao::STATUS_PUBLIC);
		$book_list = $bookRanksDao->select();

		$total = count($book_list);

		if ($total>0) {

			foreach ($book_list as $d) {

				// ランキングポイントの集計
				$this->_shiftCount($d);

				try {
					// 集計の更新
					$bookRanksDao->reset();
					$bookRanksDao->addValue(BookRanksDao::COL_RANK_POINT, $d[BookRanksDao::COL_RANK_POINT]);
					$bookRanksDao->addValue(BookRanksDao::COL_PV_TODAY, $d[BookRanksDao::COL_PV_TODAY]);
					$bookRanksDao->addValue(BookRanksDao::COL_PV_1,  $d[BookRanksDao::COL_PV_1]);
					$bookRanksDao->addValue(BookRanksDao::COL_PV_2, $d[BookRanksDao::COL_PV_2]);
					$bookRanksDao->addValue(BookRanksDao::COL_PV_3, $d[BookRanksDao::COL_PV_3]);
					$bookRanksDao->addValue(BookRanksDao::COL_PV_4, $d[BookRanksDao::COL_PV_4]);
					$bookRanksDao->addValue(BookRanksDao::COL_PV_5, $d[BookRanksDao::COL_PV_5]);
					$bookRanksDao->addValue(BookRanksDao::COL_PV_6, $d[BookRanksDao::COL_PV_6]);
					$bookRanksDao->addValue(BookRanksDao::COL_PV_7, $d[BookRanksDao::COL_PV_7]);
					$bookRanksDao->addValue(BookRanksDao::COL_EPUB_TODAY, $d[BookRanksDao::COL_EPUB_TODAY]);
					$bookRanksDao->addValue(BookRanksDao::COL_EPUB_1, $d[BookRanksDao::COL_EPUB_1]);
					$bookRanksDao->addValue(BookRanksDao::COL_EPUB_2, $d[BookRanksDao::COL_EPUB_2]);
					$bookRanksDao->addValue(BookRanksDao::COL_EPUB_3, $d[BookRanksDao::COL_EPUB_3]);
					$bookRanksDao->addValue(BookRanksDao::COL_EPUB_4, $d[BookRanksDao::COL_EPUB_4]);
					$bookRanksDao->addValue(BookRanksDao::COL_EPUB_5, $d[BookRanksDao::COL_EPUB_5]);
					$bookRanksDao->addValue(BookRanksDao::COL_EPUB_6, $d[BookRanksDao::COL_EPUB_6]);
					$bookRanksDao->addValue(BookRanksDao::COL_EPUB_7, $d[BookRanksDao::COL_EPUB_7]);
					$bookRanksDao->addWhere(BookRanksDao::COL_BOOK_ID, $d['book_id']);
					$bookRanksDao->doUpdate();

				} catch (SpException $e) {
					$this->logger->error($d);
					$this->logger->exception($e);
				}
			}
		}

		$this->logger->info('RankingDaily ['.$total.' total] end.');

		return true;
	}

	/**
	 * ランキングポイントの集計
	 * @param array $d
	 */
	private function _shiftCount(&$d)
	{
		// 5日分のPVとEPUBDL数
		$rank_pv = 0;
		$rank_epub = 0;

		for ($i=6; $i>=0; $i--) {
			// PV
			$pv = ($i==0) ? $d['pv_today'] : $d['pv_'.$i];
			$d['pv_'.($i+1)] = $pv;
			if ($i<5) $rank_pv += $pv;
			// EPUB
			$epub = ($i==0) ? $d['epub_today'] : $d['epub_'.$i];
			$d['epub_'.($i+1)] = $epub;
			if ($i<5) $rank_epub += $epub;
		}
		// 今日分はリセット
		$d['pv_today'] = 0;
		$d['epub_today'] = 0;
		// ランキングポイント
		// DL数は3倍
		$d['rank_point'] = $rank_pv + ($rank_epub * 3);
		// コメント数は5倍
		if ($d['comment_total']>0) {
			$d['rank_point'] += $d['comment_total'] * 5;
		}
		if ($d['book_id'] == 87) $d['rank_point'] = 1;
//		if ($d['evaluate_total']!=0) {
//			$d['rank_point'] += $d['evaluate_total'] * 2;
//		}
		return;
	}
}
?>
