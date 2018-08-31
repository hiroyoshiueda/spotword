<?php
/**
 * INDEX(Form)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class IndexForm extends BaseForm
{
	/** @var array */
	protected $validates = array(
		array(
		)
	);
	protected $uniforms = array(
		array(
			'login' => 'aKV',
			'password' => 'aKV'
		)
	);
	protected $names = array(
		array(
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->uniform($this->uniforms[0]);
	}
}
?>
