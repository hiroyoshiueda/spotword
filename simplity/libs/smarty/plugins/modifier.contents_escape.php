<?php
function smarty_modifier_contents_escape($str)
{
	$str = strip_tags($str);
	$str = str_replace(array("\r","\n","\t"), '', $str);
	$str = str_replace('&nbsp;', ' ', $str);
//	$str = preg_replace("/[ ]+/", ' ', $str);
	$str = mb_ereg_replace("[ |　]+", ' ', $str);
    return $str;
}
?>