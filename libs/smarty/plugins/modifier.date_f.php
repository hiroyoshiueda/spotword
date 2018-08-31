<?php
function smarty_modifier_date_f($date)
{
	if (empty($date)) return '';
	$d = preg_split('/[\/\- :]+/', $date);
	return sprintf('%04d/%d/%d',$d[0],$d[1],$d[2]);
}
?>