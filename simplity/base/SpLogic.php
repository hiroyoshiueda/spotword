<?php
/**
 * SpLogic
 * @see SpCore
 */
class SpLogic extends SpCore
{
	protected function run()
	{
		return true;
	}
	public static function import($file)
	{
		require_once SP_LOGIC_DIR.$file;
	}
}
?>