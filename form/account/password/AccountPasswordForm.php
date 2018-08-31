<?php
/**
 * パスワード再設定(Form)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class AccountPasswordForm extends BaseForm
{
	/** @var array */
	protected $validates = array(
		array(
			array('password', 'パスワードを入力してください。', 'required'),
			array('password', 'パスワードは6文字以上で入力してください。', 'minlength', array(6)),
			array('password', 'パスワードは半角英数記号( . @ # % $ = _ * & - )を入力してください。', 'password')
		)
	);

	protected $uniforms = array(
		array(
			'key' => 'md5',
			'login' => 'aKV',
			'email' => 'aKV',
			'password' => 'aKV',
			'password_confirm' => 'aKV'
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->uniform($this->uniforms[0]);
	}
}
?>
