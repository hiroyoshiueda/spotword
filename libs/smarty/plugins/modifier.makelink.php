<?php
function smarty_modifier_makelink($str)
{
	return preg_replace('/(https?:\/\/[a-z0-9_=%#\-\/\.\?\&\~]+)/im', '<a href="$1" target="_blank">$1</a>', $str);
}
?>