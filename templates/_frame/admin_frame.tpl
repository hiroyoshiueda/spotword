<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<div id="main">
	<div id="header">
		{*<a href="index.html" class="logo"><img src="/img/frontier-logo.png" width="290" height="18" alt="" /></a>
		<ul id="top-navigation">
			<li class="active"><span><span>クライアント管理</span></span></li>
			<li><span><span><a href="#">メッセージ管理</a></span></span></li>
			<li><span><span><a href="#">メール配信予約状況</a></span></span></li>
		</ul>*}
	</div>
	<div id="middle">
		<div id="left-column">
{if $page_template!="admin/admin_login.tpl"}
			<h3>メニュー</h3>
			<ul class="nav">
				<li><a href="/admin/client/">クライアント管理</a></li>
				<li class="last"><a href="/admin/msg/">メッセージ管理</a></li>
				{*<li class="last"><a href="#">メール配信予約状況</a></li>*}
			</ul>
			<a href="/admin/logout" class="link">ログアウト</a>
{/if}
			{*<a href="#" class="link">Link here</a>
			<a href="#" class="link">Link here</a>*}
		</div>
{include file="$page_template"}
		<div id="right-column">
{if $info!=""}
			<strong class="h">INFO</strong>
			<div class="box">{$info}</div>
{/if}
	  </div>
	</div>
	<div id="footer"></div>
</div>

</body>
</html>
