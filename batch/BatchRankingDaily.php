<?php
Sp::import('RankingDaily', 'libs');
Sp::import('RankingCategoryDaily', 'libs');
Sp::import('RankingUserDaily', 'libs');
Sp::import('Cleaner', 'libs');
/**
 * 毎日実行するバッチ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BatchRankingDaily extends BaseBatch
{
	/**
	 * アクセス数、ランキング集計
	 */
	public function run()
	{
		// ランキング集計
		try {
			$rankingDaily = new RankingDaily($this->db, $this->logger);
			$rankingDaily->execute();
		} catch (SpException $e) {
			$this->logger->exception($e);
		}

		// ジャンル別集計
		try {
			$rankingDaily = new RankingCategoryDaily($this->db, $this->logger);
			$rankingDaily->execute();
		} catch (SpException $e) {
			$this->logger->exception($e);
		}

		// 作家別集計
		try {
			$rankingDaily = new RankingUserDaily($this->db, $this->logger);
			$rankingDaily->execute();
		} catch (SpException $e) {
			$this->logger->exception($e);
		}

		// tmp掃除
		Cleaner::tempDir(APP_CONST_TMP_DIR_REMOVE_DAYS);

		// tmpユーザーの掃除
		Cleaner::tempUser($this->db, APP_CONST_TMP_USER_REMOVE_DAYS);

		// tmpページの削除
		Cleaner::tempPublicationPage($this->db, APP_CONST_TMP_PAGE_REMOVE_DAYS);

		return true;
	}
}
?>
