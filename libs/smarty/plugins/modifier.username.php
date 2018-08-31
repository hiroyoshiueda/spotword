<?php
function smarty_modifier_username($user_data, $icon=true)
{
	$html = '';
	$user = (is_array($user_data)) ? $user_data : $GLOBALS[APP_CONST_LOAD_USER_NAME][$user_data];
	if ($icon) {
		$img_src = sw_get_profile_image($user, 'small');
		$html = '<img src="'.$img_src.'" width="20" height="20" alt="'.htmlspecialchars($user['penname']).'" class="icon-middle" /> ';
	}
	$html .= '<a href="/'.htmlspecialchars($user['login']).'/">';
	$html .= htmlspecialchars($user['penname']);
	$html .= '</a>';
	return $html;
}
?>