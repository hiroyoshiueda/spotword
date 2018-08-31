<?php
function smarty_modifier_errormsg($errors)
{
	if (empty($errors)) return '';
	return '<p class="errormsg-bg">'.join('<br />', $errors).'</p>';
}
?>