<?php
function smarty_modifier_add_query($str, $query)
{
	if ($query == '') return $str;
	if (strpos($str, '?') === false) {
		return $str . '?' . $query;
	} else {
		return $str . '&' . $query;
	}
}
?>