<?php
/**
 * {tag type="checkbox" name="" value="" checked="" label=""}
 * {tag type="select" name="" options="" selected=""}
 * @param $params
 * @param $smarty
 * @return string
 */
function smarty_function_tag($params, &$smarty)
{
	require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

	if (isset($params['name']) === false) {
		$smarty->trigger_error('tag: the "name" attribute not found.', E_USER_WARNING);
	}

	$extra = '';
	$p = array();
	$opt = array();
	$tag = '';
	$sub = '';
	$is_label = false;

	if (strpos($params['type'], ':') !== false) {
		list($tag, $sub) = explode(':', $params['type']);
	} else {
		$tag = $params['type'];
	}

//	if (isset($params['id']) === false) $params['id'] = $params['name'].'_id';

	foreach($params as $key => $value) {
		switch ($key) {
			case 'type':
				if (strpos($value, 'select') !== false) {
					$p['size'] = '1';
					break;
				}
				$p[$key] = $value;
				break;
			case 'name':
			case 'id':
			case 'class':
			case 'style':
			case 'maxlength':
			case 'size':
			case 'onchange':
			case 'onclick':
				$p[$key] = $value;
				break;
			case 'value':
				$p[$key] = smarty_function_escape_special_chars($value);
				break;
			case 'selected':
			case 'checked':
				if ($value === null) $value = '';
				if ($params['value'] == (string)$value) $p[$key] = $key;
				break;
			case 'checks':
				if (is_array($value)) {
					foreach ($value as $str) {
						if ($params['value'] == $str) {
							$p['checked'] = 'checked';
							break;
						}
					}
				}
				break;
			case 'label':
				$is_label = true;
//				$extra = '<label for="'.$params['id'].'">'.smarty_function_escape_special_chars($value).'</label>';
				$extra = '&nbsp;'.smarty_function_escape_special_chars($value);
				break;
			case 'options':
				$opt = $value;
				break;
		}
	}

	if ($sub == 'year') {
		$dt = _smarty_function_tag_split_date($params['selected']);
	} else if ($sub == 'month') {
		$params['from'] = 1;
		$params['to'] = 12;
		$dt = _smarty_function_tag_split_date($params['selected']);
	} else if ($sub == 'day') {
		$params['from'] = 1;
		$params['to'] = 31;
		$dt = _smarty_function_tag_split_date($params['selected']);
	}

	if ($params['blank'] == 'on') {
		if (is_array($opt)) {
			array_unshift($opt, array('value'=>'', 'text'=>''));
		} else {
			$opt = array(array('value'=>'', 'text'=>''));
		}
	} else if ($params['blank'] != '') {
		if (is_array($opt)) {
			array_unshift($opt, array('value'=>'', 'text'=>$params['blank']));
		} else {
			$opt = array(array('value'=>'', 'text'=>$params['blank']));
		}
	}

	if ($params['from'] != '' && $params['to'] != '') {
		$from = (int)$params['from'];
		$to = (int)$params['to'];
		for ($i=$from; $i<=$to; $i++) {
			if ($params['format'] != '') $text = sprintf($params['format'], $i);
			else $text = $i;
			$arr = array('value'=>$i, 'text'=>$text);
			$opt[] = $arr;
		}
	}

	$html = '';
	if ($is_label) $html .= '<label style="cursor:pointer;">';
	if ($tag == 'select') {
		if ($sub == '') {
			$selected = $params['selected'];
		} else {
			$dt = _smarty_function_tag_split_date($params['selected']);
			$selected = $dt[$sub];
		}
		unset($p['selected']);
		$html .= _smarty_function_tag_select($p, $opt, $selected);
	} else {
		$html .= _smarty_function_tag_input($p);
	}
	$html .= $extra;
	if ($is_label) $html .= '</label>';
	return $html;
}

function _smarty_function_tag_input(&$p)
{
	$html = '<input';
	foreach($p as $key => $val) {
		if ($key == '') continue;
		$html .= ' '.$key.'="'.$val.'"';
	}
	$html .= ' />';
	return $html;
}
function _smarty_function_tag_select(&$p, &$opt, $selected)
{
	$html = '<select';
	foreach($p as $key => $val) {
		if ($key == '') continue;
		$html .= ' '.$key.'="'.$val.'"';
	}
	$html .= ">\n";
	if (count($opt)>0) {
		foreach ($opt as $ary) {
			$html .= '<option value="'.htmlspecialchars($ary['value']).'"';
			if ((string)$ary['value'] === (string)$selected) $html .= ' selected="selected"';
			if (isset($ary['class'])) $html .= ' class="'.$ary['class'].'"';
			$html .= '>';
			$html .= htmlspecialchars($ary['text']);
			$html .= "</option>\n";
		}
	}
	$html .= "</select>\n";
	return $html;
}
function _smarty_function_tag_split_date($date)
{
	$arr = array('year'=>'','month'=>'','day'=>'');
	if ($date == '') return $arr;
	if (preg_match("|(\d{2,4})[/\-\.]{1}(\d{1,2})[/\-\.]{1}(\d{1,2})|i", $date, $m)) {
		$arr['year'] = (int)$m[1];
		$arr['month'] = (int)$m[2];
		$arr['day'] = (int)$m[3];
	}
	return $arr;
}
//function _smarty_function_tag_get_name($type)
//{
//	$tag = 'input';
//	switch ($type) {
//		case 'select':
//		case 'select:year':
//		case 'select:month':
//		case 'select:day':
//			$tag = 'select';
//			break;
//	}
//	return $type;
//}
?>