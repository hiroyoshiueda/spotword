<?php
/**
 * 本のビュー(Form)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class BookForm extends BaseForm
{
	/** @var array */
	protected $validates = array(
		array(
			array('book_comment', 'コメントを入力してください。', 'required'),
			array('book_comment', 'コメントは200文字以下で入力してください。', 'maxlengthZen', array(200)),
		)
	);
	protected $uniforms = array(
		array(
			'id' => 'int',
			'book_comment' => 'KV'
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->uniform($this->uniforms[0]);
	}
}
?>
