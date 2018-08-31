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
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/base.js" type="text/javascript"></script>
<script src="/js/jquery.tipsy.js" type="text/javascript"></script>
{include file="_parts/header_scripts.tpl"}
</head>
<body>
<div id="wrapper">
<div id="container">
<div id="navi">
<ul>
	<li><a href="/" id="menu-01{if $form.env_class_name=="Index"}-selected{/if}" title="スポットワードへようこそ">スポットワードへようこそ</a></li>
	<li><a href="/list/" id="menu-02{if $form.env_class_name=="List"}-selected{/if}" title="おすすめ本を探す">おすすめ本を探す</a></li>
	<li><a href="/user/mydesk/create" id="menu-03{if $form.env_class_name=="UserMydesk"}-selected{/if}" title="あなたの作品を書く">あなたの作品を書く</a></li>
</ul>
{if $userInfo}
<div id="navi-login"><span><a href="/service/">スポットワードについて</a><span><span><a href="/user/edit/">{$userInfo.penname}</a> さんようこそ</span><span><a href="/logout">ログアウト</a></span>
{else}
<div id="navi-login"><span><a href="/service/">スポットワードについて</a><span><span><a href="/user/regist/first">スポットワードID（無料）を登録する</a></span><span><a href="/login">ログイン</a></span>
{/if}
<!-- #navi-login --></div>
<!-- #navi --></div>

<div id="user-tab">
<ul>
<li{if $form.env_class_name=="UserEdit"} id="user-tab-selected"{/if}><img src="/img/icon_myedit.png" width="16" height="16" align="top" /> <a href="/user/edit/">登録情報</a></li>
{*<li{if $form.env_class_name=="UserMypage"} id="user-tab-selected"{/if}><img src="/img/icon_mypage.png" width="16" height="16" align="top" /> <a href="/user/mypage/">マイページ</a></li>*}
<li{if $form.env_class_name=="UserMydesk" || $form.env_class_name=="UserComment" || $form.env_class_name=="UserImport"} id="user-tab-selected"{/if}><img src="/img/icon_mydesk.png" width="16" height="16" align="top" /> <a href="/user/mydesk/">マイデスク</a></li>
</ul>
<div class="clear"></div>
<!-- #user-tab --></div>
<div id="main" class="main-frame">
<div id="main-frame-top"></div>
<div id="main-frame-inner">

{include file="$page_template"}

<!-- #main-frame-inner --></div>
<div id="main-frame-bottom"></div>
<!-- #main --></div>

<!-- #container --></div>
<!-- #wrapper --></div>

{include file="_parts/footer.tpl"}

</body>
</html>