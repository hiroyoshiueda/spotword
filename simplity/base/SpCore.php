<?php
/**
 * SpCore
 */
abstract class SpCore
{
	/**
	 * @var SpLogger
	 */
	protected $logger = null;

	/**
	 * @var DbManager
	 */
	protected $db = null;

	/**
	 * @var SpForm
	 */
	protected $form = null;

	/**
	 * @var SpResponse
	 */
	protected $resp = null;

	/**
	 *
	 * @param SpLogger $logger
	 * @param DbManager $db
	 * @param SpForm $form
	 * @param SpResponse $resp
	 */
	function __construct(&$logger, &$db, &$form, &$resp)
	{
		$this->logger =& $logger;
		$this->db =& $db;
		$this->form =& $form;
		$this->resp =& $resp;
	}

	/**
	 * @return SpForm
	 */
	protected function &getForm()
	{
		return $this->form;
	}

	/**
	 * @return SpResponse
	 */
	protected function &getResponse()
	{
		return $this->resp;
	}

	/**
	 * @return DbManager
	 */
	protected function &getDb()
	{
		return $this->db;
	}
}
?>