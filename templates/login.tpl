<div id="login-area">

<table id="login-frame">
	<tbody>
		<tr>
			<td>
<form name="mainform" method="post" action="/login">
{include file="_parts/hidden.tpl"}
<h2>スポットワードIDでログイン</h2>
<div id="login-frame-normal">
<div class="column_wrapper" style="padding-bottom:0px;">
	<label for="login">
	 スポットワードID
	</label>
	<input id="login-id" name="login" value="{$form.login}" style="width:230px;" size="30" type="text" />
</div>
<div class="column_wrapper" style="padding-bottom:0px;">
	<label for="password">
	 パスワード
	</label>
	<input id="password" name="password" value="{$form.password}" style="width:230px;" size="30" type="password" />
</div>
<div style="padding:0px 0px 0px 5px;">{$form.errors.login|@errormsg}</div>
<div style="padding:10px;"><input class="btn-02" value="ログイン" title="ログインする" type="submit" /></div>
<p style="font-size:88%;"><a href="/account/password/">パスワードを忘れてしまった方</a></p>
<!-- #login-frame-normal --></div>
</form>
			</td>
			<td>
<h2>既にお持ちのIDでログイン</h2>
<div id="login-frame-other">
<div id="login-frame-other-list">
<ul>
	<li><a href="/twitter/" id="twitter-login" title="Twitter IDでログインする">Twitter IDでログイン</a></li>
	<li><a href="/openid/mixi" id="mixi-login" title="mixi IDでログインする">mixi IDでログイン</a></li>
</ul>
<div class="clear"></div>
<!-- #login-frame-other-list --></div>
<p style="color:#666;">上記サービスを利用中であれば簡単にログインできます。</p>
<!-- #login-frame-other --></div>
			</td>
		</tr>
	</tbody>
</table>

<div id="login-main">

<h2>スポットワードに参加しよう！</h2>
<p>スポットワードで自分の好きな文章を電子書籍として公開したり、他のメンバーからコメントをもらったり、
スポットワードで文章を書く、読むことの楽しさを再発見してください！
</p>
<div id="login-regist-button"><a href="/user/regist/first" title="スポットワードIDを登録する">
<img src="/img/login_regist_button.png" alt="スポットワードIDを登録する" /></a></div>

<!-- #login-main --></div>

<!-- #login --></div>

{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$("#login-id").focus();
	$('#twitter-login, #mixi-login').tipsy({gravity:'n'});
});
//]]>
</script>
{/literal}
