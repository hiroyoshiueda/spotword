<?php
/**
 * 本の読み込み(Form)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserImportForm extends BaseForm
{
	/** @var array */
	protected $validates = array(
		array(
			array('upload_file', 'ファイルを選択してください。', 'required')
		)
	);

	protected $uniforms = array(
		array(
			'id' => 'int'
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->uniform($this->uniforms[0]);
	}
}
?>
