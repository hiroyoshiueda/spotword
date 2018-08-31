<?php
function smarty_modifier_profileimg($userInfo, $size_type='normal')
{
	return sw_get_profile_image($userInfo, $size_type);
}
?>