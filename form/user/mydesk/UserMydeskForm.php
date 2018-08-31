<?php
/**
 * 出版データ作成(Form)
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class UserMydeskForm extends BaseForm
{
	/** @var array */
	protected $validates = array(
		array(
			array('title', '本のタイトルを入力してください。', 'required'),
			array('title', '本のタイトルは全角40文字以下で入力してください。', 'maxlengthZen', array(40)),
			array('subtitle', 'サブタイトルは全角40文字以下で入力してください。', 'maxlengthZen', array(40)),
			array('description', '本の概要を入力してください。', 'required'),
			array('description', '本の概要は全角400文字以下で入力してください。', 'maxlengthZen', array(400)),
			array('category_id', 'ジャンルを選択してください。', 'required'),
			array('author', '著者名は全角30文字以下で入力してください。', 'maxlengthZen', array(30)),
			array('publisher', '出版社名は全角30文字以下で入力してください。', 'maxlengthZen', array(30)),
			array('keywords', '検索キーワードは全角100文字以下で入力してください。', 'maxlengthZen', array(100))
		),
		array(
			array('page_title', '章のタイトルを入力してください。', 'required'),
			array('page_title', '章のタイトルは全角40文字以下で入力してください。', 'maxlengthZen', array(40)),
			array('page_contents', '本文を入力してください。', 'required')
		),
		array(
			array('comment_flag', 'コメントについて選択してください。', 'required'),
			array('epub_flag', 'EPUBについて選択してください。', 'required'),
			array('copyright_flag', 'この作品は他人の著作権を侵害していないことを確認して問題なければ「はい。侵害していません。」にチェックを入れてください。', 'required'),
		),
		array(
			array('cover_file', '表紙に使う画像を選択してください。', 'required')
		),
		array(
			array('image1_file', '画像または画像を圧縮したファイルを選択してください。', 'required'),
			array('image1_title', '画像のタイトルは全角30文字以下で入力してください。', 'maxlengthZen', array(30)),
		)
	);
	protected $uniforms = array(
		array(
			'id' => 'int',
			'publication_id' => 'int',
			'publication_key' => 'md5',
			'title' => 'KV',
			'subtitle' => 'KV',
			'description' => 'KV',
			'author' => 'KV',
			'publisher' => 'KV',
			'keywords' => 'KV',
			'category_id' => 'int',
			'cover_file' => 'KV',
			'cover_path' => 'aKV',
			'cover_size' => 'int',
			'cover_s_file' => 'KV',
			'cover_s_path' => 'aKV',
			'cover_s_size' => 'int',
			'comment_flag' => 'int',
			'epub_flag' => 'int',
			'copyright_flag' => 'int',
			'image1_title' => 'aKV'
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->uniform($this->uniforms[0]);
	}
}
?>
