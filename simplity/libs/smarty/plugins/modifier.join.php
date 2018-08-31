<?php
function smarty_modifier_join($var, $j='')
{
	if (is_array($var)===false) return '';
	return join($j, $var);
}
?>