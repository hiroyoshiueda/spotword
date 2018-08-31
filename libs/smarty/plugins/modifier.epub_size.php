<?php
function smarty_modifier_epub_size($book_id)
{
	$filename = APP_CONST_BOOK_EPUB_DIR.'/book-'.$book_id.'.epub';
	$size = 0;
	$unit = ' KB';
	if (file_exists($filename)) {
		$size = filesize($filename);
		if ($size>0) $size /= 1024;
		if ($size>512) {
			$size /= 1024;
			$unit = ' MB';
		}
		return '('.number_format($size, 1).$unit.')';
	} else {
		return '';
	}
}
?>