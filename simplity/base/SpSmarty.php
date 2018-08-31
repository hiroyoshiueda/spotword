<?php
require_once 'smarty/Smarty.class.php';
/**
 * SpSmarty
 * @see Smarty
 */
class SpSmarty extends Smarty
{
	function SpSmarty()
	{
		parent::__construct();
		$this->template_dir = SP_TEMPLATE_DIR;
		$this->compile_dir = SP_COMPILE_DIR;
		$this->plugins_dir[] = SP_PLUGINS_DIR;
		// |smarty:nodefaults
		$this->default_modifiers = array('string_escape:"html"');
//		$this->force_compile = true;
	}
	function addPlugins($dir)
	{
		$this->plugins_dir[] = $dir;
	}
}
?>