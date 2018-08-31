<?php
/**
 * 登録情報の変更(Form)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserEditForm extends BaseForm
{
	/** @var array */
	protected $validates = array(
		array(
			array('upload_file', '画像を選択してください。', 'required')
		),
		array(
			array('old_password', '古いパスワードを入力してください。', 'required'),
			array('new_password', '新しいパスワードを入力してください。', 'required'),
			array('new_password', '新しいパスワードは6文字以上20文字以内で入力してください。', 'length_range', array(6, 20)),
			array('new_password', '新しいパスワードは半角英数記号( . @ # % $ = _ * & + - )で入力してください。', 'password'),
		),
		array(
			array('new_email', '新しいメールアドレスを入力してください。', 'required'),
			array('new_email', '正しいメールアドレスの書式で入力してください。', 'email'),
			array('new_email', '新しいメールアドレスは200文字以下で入力してください。', 'maxlength', array(200)),
		),
		array(
			array('profile_msg', '自己紹介は400文字以下で入力してください。', 'maxlengthZen', array(400))
		),
		array(
			array('edit_penname', '変更するペンネームを入力してください。', 'required'),
			array('edit_penname', 'ペンネームは3文字以上20文字以内で入力してください。', 'length_range_zen', array(3, 20))
		),
		array(
			array('edit_zip', '変更する郵便番号を入力してください。', 'required'),
			array('edit_zip', '正しい郵便番号の書式で入力してください。', 'match', array('^[0-9]{3}-[0-9]{4}$'))
		)
	);
	protected $uniforms = array(
		array(
			'id' => 'int',
			'profile_file' => 'KV',
			'profile_path' => 'aKV',
			'profile_size' => 'int',
			'old_password' => 'aKV',
			'new_password' => 'aKV',
			'new_password_confirm' => 'aKV',
			'new_email' => 'aKV',
			'new_email_confirm' => 'aKV'
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
