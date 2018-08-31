<?php
function smarty_modifier_cover_img_src($path, $user_id)
{
	if ($path == '') return '/img/no_cover.png';
	$img_dir = str_replace('[user_id]', $user_id, APP_CONST_COVER_IMAGE_PATH);
	return $img_dir.'/'.$path;
}
?>