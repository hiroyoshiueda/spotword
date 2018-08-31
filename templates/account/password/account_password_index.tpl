{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$("#email").focus();
});
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="contact">
<h1 class="page-title">{$form.htitle}</h1>
<div class="infomsg">
ログインに使用している<strong>スポットワードID</strong>と<strong>登録メールアドレス</strong>を入力して [パスワードを再設定する] ボタンをクリックしてください。<br />
登録メールアドレス宛にパスワード再設定用URLを送信します。<br /><br />
<u>※Twitterやmixiなど他のサービスのIDでのログインを選ばれている方は、各サービスのサイトで手続きを行ってください。</u>
</div>
{if $form.errors}
<div class="errormsg" style="margin-top:20px;"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
{$form.sys_errors.msg|@errormsg}
<form id="mainform" method="post" action="send">
{include file="_parts/hidden.tpl"}
<div class="column_wrapper{$form.errors.login|@errorclass}">
	<label for="login">
	 スポットワードID
	</label>
	<input id="login" name="login" style="width:400px;" class="ime-off" value="{$form.login}" size="30" type="text" />
	{$form.errors.login|@errormsg}
</div>
<div class="column_wrapper{$form.errors.email|@errorclass}">
	<label for="useremail">
	 メールアドレス
	</label>
	<input id="useremail" name="email" style="width:400px;" class="ime-off" value="{$form.email}" size="30" type="text" />
	{$form.errors.email|@errormsg}
</div>
<div id="btn-area"><input id="btn-mainform" type="submit" value="パスワードを再設定する" /></div>
</form>
</div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/side_request_form.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>
