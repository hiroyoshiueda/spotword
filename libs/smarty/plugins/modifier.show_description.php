<?php
function smarty_modifier_show_description($str)
{
	return preg_replace('/[　\r\n\t]+/u', '', $str);
}
?>