<?php
/**
 * 出版データ作成(Form)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserRegistForm extends BaseForm
{
	/** @var array */
	protected $validates = array(
		array(
			array('send_email', 'メールアドレスを入力してください。', 'required'),
			array('send_email', '正しいメールアドレスの書式で入力してください。', 'email'),
			array('send_email', 'メールアドレスは200文字以下で入力してください。', 'maxlength', array(200)),
		),
		array(
			array('login', 'スポットワードIDを入力してください。', 'required'),
			array('login', 'スポットワードIDは4文字以上20文字以内で入力してください。', 'length_range', array(4, 20)),
			array('login', 'スポットワードIDは半角英数字、半角ハイフン( - )を入力してください。', 'match', array(APP_CONST_USER_LOGINID_REG)),
			array('password', 'パスワードを入力してください。', 'required'),
			array('password', 'パスワードは6文字以上20文字以内で入力してください。', 'length_range', array(6, 20)),
			array('password', 'パスワードは半角英数記号( . @ # % $ = _ * & + - )を入力してください。', 'password'),
			array('penname', 'ペンネームを入力してください。', 'required'),
			array('penname', 'ペンネームは3文字以上20文字以内で入力してください。', 'length_range_zen', array(3, 20)),
			array('birthday', '生年月日が正しい日付ではありません。', 'checkdate'),
			array('zip', '郵便番号を入力してください。', 'required'),
			array('zip', '正しい郵便番号の書式で入力してください。', 'match', array('^[0-9]{3}-[0-9]{4}$')),
			array('agree', '利用規約に同意の上、チェックボックスにチェックを入れてください。', 'required')
		),
		array(
			array('login', 'スポットワードIDを入力してください。', 'required'),
			array('login', 'スポットワードIDは4文字以上20文字以内で入力してください。', 'length_range', array(4, 20)),
			array('login', 'スポットワードIDは半角英数字、半角ハイフン( - )を入力してください。', 'match', array(APP_CONST_USER_LOGINID_REG))
		),
		array(
			array('penname', 'ペンネームを入力してください。', 'required'),
			array('penname', 'ペンネームは3文字以上20文字以内で入力してください。', 'length_range_zen', array(3, 20))
		),
		array(
			array('birthday', '生年月日が正しい日付ではありません。', 'checkdate'),
			array('agree', '利用規約に同意の上、チェックボックスにチェックを入れてください。', 'required')
		)
	);
	protected $uniforms = array(
		array(
			'user_id' => 'int',
			'temp_key' => 'md5',
			'send_email' => 'aKV',
			'penname' => 'KV',
			'login' => 'aKV',
			'password' => 'aKV',
			'password_confirm' => 'aKV',
			'birthday_y' => 'int',
			'birthday_m' => 'int',
			'birthday_d' => 'int',
			'gender' => 'int',
			'zip' => 'aKV',
			'melmaga_system' => 'int',
			'melmaga_basic' => 'int',
			'agree' => 'int',
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->uniform($this->uniforms[0]);
	}
}
?>
