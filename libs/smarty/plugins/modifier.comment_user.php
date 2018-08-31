<?php
function smarty_modifier_comment_user($data, $profile_type='profile_path')
{
	$img = ($data[$profile_type]!='') ? $data['login'].'/'.$data[$profile_type] : 'default/default_0_small.png';
	$html = '<img src="/profile_images/'.$img.'" width="48" height="48" alt="'.htmlspecialchars($data['penname']).'" style="vertical-align:middle" /> ';
	$html .= '<a href="/'.htmlspecialchars($data['login']).'/" target="_blank">';
	$html .= htmlspecialchars($data['penname']);
	$html .= '</a>';
	return $html;
}
?>