<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
{include file="_parts/header_meta_tags.tpl"}
<meta name="keywords" content="{$keywords}" />
<meta name="description" content="{$description}" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>{$title}</title>
<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="/css/base.css" rel="stylesheet" type="text/css" media="all" />
<link href="/css/layout.css" rel="stylesheet" type="text/css" media="all" />
<link href="/css/common.css" rel="stylesheet" type="text/css" media="all" />
<link href="/css/default.css" rel="stylesheet" type="text/css" media="all" />
<link href="/css/tipsy.css" rel="stylesheet" type="text/css" media="all" />
{include file="_parts/header_styles.tpl"}
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/base.js"></script>
<script src="/js/jquery.tipsy.js" type="text/javascript"></script>
{include file="_parts/header_scripts.tpl"}
</head>
<body>
<div id="wrapper">
<div id="container">
{if $form.popup_title!=""}
<div id="popup-title"><h1>{$form.popup_title}<span style="float:right;font-size:65%;font-weight:normal;padding:5px 0;"><a href="#" onclick="window.close();return false;" style="color:#fff;">閉じる</a></span></h1></div>
{/if}
<div id="main" class="main-frame" style="margin-bottom:20px;">
<div id="main-frame-top"></div>
<div id="main-frame-inner">

{include file="$page_template"}

<!-- #main-frame-inner --></div>
<div id="main-frame-bottom"></div>
<!-- #main --></div>

<!-- #conteiner --></div>
<!-- #wrapper --></div>
</body>
</html>
