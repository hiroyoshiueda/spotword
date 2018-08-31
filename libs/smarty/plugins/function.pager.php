<?php
/**
 * {pager total=$total limit=$limit}
 * @param $params
 * @param $smarty
 * @return unknown_type
 */
function smarty_function_pager($params, &$smarty)
{
	if (isset($params['limit'])===false) $smarty->trigger_error('plugin "pager": missing or empty parameter: limit');
	if (isset($params['total'])===false) $smarty->trigger_error('plugin "pager": missing or empty parameter: total');

	$posvar = empty($params['posvar']) ? 'offset' : $params['posvar'];
	$limit = $params['limit'];
	$total = $params['total'];

	$offset = isset($_REQUEST[$posvar]) ? (int)$_REQUEST[$posvar] : 0;

	if ($total == 0) return '';

	$prev_format = empty($params['prev_format']) ? '≪前の%d件' : $params['prev_format'];
	$next_format = empty($params['next_format']) ? '次の%d件≫' : $params['next_format'];
	$main_class = empty($params['main_class']) ? 'pager' : $params['main_class'];
	if (empty($params['url'])) {
		$base_url = $_SERVER['REQUEST_URI'];
		$removeVars = array($posvar);
		foreach($removeVars as $tmp)	{
			$base_url = preg_replace('/(&|\?)'.$tmp.'\=[^&]*/', '', $base_url);
		}
	} else {
		$base_url = $params['url'];
	}
	$pmts = null;
	if (is_array($_POST) && count($_POST)>0) $pmts = $_POST;
	if (is_array($params['params'])) $pmts = $pmts + $params['params'];
	$show_disable = isset($params['show_disable']) ? $params['show_disable'] : 0;
	$max_page = empty($params['max_page']) ? 10 : $params['max_page'];

	$total_page = (int)ceil($total / $limit);
	$current_page = ($offset<=0) ? 1 : (int)ceil($offset / $limit) + 1;
//	if ($total_page == 1) return '';

	$start = ($current_page <= $max_page) ? 1 : $current_page - ($max_page/2);
	$end = $start + $max_page;
	if ($end > $total_page) $end = $total_page;

	if (is_array($pmts)) {
		$arr = array();
		foreach ($pmts as $k => $v) {
			if ($k == $posvar) continue;
			$arr[] = $k . '=' . $v;
		}
		$base_url .= (strpos($base_url, '?')===false) ? '?' : '&';
		$base_url .= implode('&', $arr);
	}
	$sep = (strpos($base_url, '?')===false) ? '?' : '&';

	$html = '<div class="'.$main_class.'">';

	if ($start>1 || $current_page>1) {
		$off = ($limit * ($current_page-1)) - $limit;
		$url = $base_url;
		if ($off>0) $url .= $sep.$posvar.'='.$off;
		$html .= '<a href="'.$url.'">'.sprintf($prev_format, $limit).'</a>';
	} else {
		if ($show_disable>0) $html .= '<a class="disable">'.sprintf($prev_format, $limit).'</a>';
	}

	if ($start < $end) {
		while ($start<=$end) {
			$off = $limit * ($start-1);
			$url = $base_url;
			if ($off>0) $url .= $sep.$posvar.'='.$off;
			$selected = ($start==$current_page) ? ' class="selected"' : '';
			$html .= '<a href="'.$url.'"'.$selected.'>' . $start . '</a>';
			$start++;
		}
	}

	if ($current_page<$total_page) {
		$url = $base_url . $sep.$posvar.'=' . ($limit * $current_page);
		$html .= '<a href="'.$url.'">'.sprintf($next_format, $limit).'</a>';
	} else {
		if ($show_disable>0) $html .= '<a class="disable">'.sprintf($next_format, $limit).'</a>';
	}

	$html .= '</div>';

	return $html;
}
?>