<?php
/**
 * SpAction
 * @see SpCore
 */
class SpAction extends SpCore
{
	public function execute()
	{
		return;
	}
	public function preExecute()
	{
		return;
	}
	public function postExecute()
	{
		return;
	}
	public static function import($file)
	{
		include_once SP_ACTION_DIR.$file;
	}
}
?>