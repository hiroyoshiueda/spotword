<?php
/**
 * 画像データ
 *
 * Copyright(c) 2011 Esora Inc. All Rights Reserved.
 * http://www.e-sora.co.jp/
 *
 * @author Esora Inc.
 */
class PublicationImagesDao extends BaseDao
{
	const TABLE_NAME = 'publication_images';

const COL_PUBLICATION_IMAGE_ID = "publication_image_id";
const COL_DELETE_FLAG = "delete_flag";
const COL_DISPLAY_FLAG = "display_flag";
const COL_PUBLICATION_ID = "publication_id";
const COL_USER_ID = "user_id";
const COL_IMAGE_TITLE = "image_title";
const COL_IMAGE_FILE = "image_file";
const COL_IMAGE_PATH = "image_path";
const COL_IMAGE_SIZE = "image_size";
const COL_CREATEDATE = "createdate";
const COL_LASTUPDATE = "lastupdate";
const COL_DELETEDATE = "deletedate";

	function __construct(&$db, $options=array())
	{
		parent::__construct($db, self::TABLE_NAME, $options);
	}

	public function getList($publication_id, $user_id)
	{
		$this->addWhere(self::COL_PUBLICATION_ID, $publication_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		return $this->select();
	}

	public function getItem($id, $user_id)
	{
		$this->addWhere(self::COL_PUBLICATION_IMAGE_ID, $id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->selectRow();
	}

	public function delete($id, $user_id)
	{
		$this->addWhere(self::COL_PUBLICATION_IMAGE_ID, $id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}

	public function deleteAll($publication_id, $user_id)
	{
		$this->addWhere(self::COL_PUBLICATION_ID, $publication_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		return $this->doDelete();
	}

	public function getUserSize($publication_id, $user_id)
	{
		$this->addSelectSum(self::COL_IMAGE_SIZE, 'image_size');
		$this->addWhere(self::COL_PUBLICATION_ID, $publication_id);
		$this->addWhere(self::COL_USER_ID, $user_id);
		$this->addWhere(self::COL_DELETE_FLAG, self::DELETE_FLAG_ON);
		$this->addWhere(self::COL_DISPLAY_FLAG, self::DISPLAY_FLAG_ON);
		return $this->selectId();
	}
}
?>