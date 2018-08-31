<?php
/**
 * サービス(Controller)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class ServiceController extends BaseController
{
	/**
	 * SPOTWORDについて
	 */
	public function index()
	{
		$this->form->set('htitle', 'スポットワードについて');
		$this->setTitle($this->form->get('htitle'));

		$this->setDescription('初めての方へスポットワードについて紹介しています。');

		return $this->forward('service/service_index', APP_CONST_MAIN_FRAME);
	}
}
?>
