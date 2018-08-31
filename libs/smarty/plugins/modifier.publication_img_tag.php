<?php
function smarty_modifier_publication_img_tag($path, $user_id, $publication_id, $maxwidth=0)
{
	$img_path = str_replace(array('[user_id]', '[publication_id]'), array($user_id, $publication_id), APP_CONST_PUBLICATION_IMAGE_PATH);
	$img_src = $img_path.'/'.$path;

	if ($maxwidth > 0) {
		$img_dir = str_replace(array('[user_id]', '[publication_id]'), array($user_id, $publication_id), APP_CONST_PUBLICATION_IMAGE_DIR);
		$filepath = $img_dir.'/'.$path;
		if (file_exists($filepath)) {
			$size = getimagesize($filepath);
			$width = $size[0];
			$height = $size[1];
			if ($width > $maxwidth) {
				$minrate = $maxwidth / $width;
				$maxheight = $height * $minrate;
				$maxheight = (int)$maxheight;
			} else {
				$maxwidth = $width;
				$maxheight = $height;
			}
			return '<img src="'.$img_src.'" width="'.$maxwidth.'" height="'.$maxheight.'" alt="　" />';
		}
	}

	return '<img src="'.$img_src.'" alt="　" />';
}
?>