<?php
Sp::import('list/ListController', 'controllers');
/**
 * おすすめの本(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class RssController extends ListController
{
	/**
	 * 人気本
	 */
	public function popular()
	{
		$this->_getList('popular');

		$this->resp->setContentType(SpResponse::CTYPE_RSS);

		return $this->forward('rss/rss_rss20', APP_CONST_EMPTY_FRAME);
	}

	/**
	 * 新着本
	 */
	public function newarrivals()
	{
		$this->_getList('newarrivals');

		$this->resp->setContentType(SpResponse::CTYPE_RSS);

		return $this->forward('rss/rss_rss20', APP_CONST_EMPTY_FRAME);
	}
}
?>
