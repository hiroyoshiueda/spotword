{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$("#password").focus();
});
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="contact">
<h1 class="page-title">{$form.htitle}</h1>
<div class="infomsg">
パスワードを変更します。新しく設定したいパスワードを入力してください。
</div>
{if $form.errors}
<div class="errormsg" style="margin-top:20px;"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
{$form.sys_errors.msg|@errormsg}
<form id="mainform" method="post" action="reset_save">
{include file="_parts/hidden.tpl"}
<div class="column_wrapper{$form.errors.password|@errorclass}">
	<label for="password">
	 パスワード
	</label>
	<input id="password" name="password" style="width:400px;" class="ime-off" value="{$form.password}" size="30" type="password" />
	{$form.errors.password|@errormsg}
	<p class="notice">※6文字以上の半角英数記号（ <strong>. @ # % $ = _ * & -</strong> ）のみ。</p>
</div>
<div class="column_wrapper{$form.errors.password_confirm|@errorclass}">
	<label for="password_confirm">
	 パスワード（確認）
	</label>
	<input id="password_confirm" name="password_confirm" style="width:400px;" class="ime-off" value="{$form.password_confirm}" size="30" type="password" />
	{$form.errors.password_confirm|@errormsg}
	<p class="notice">※確認のため、同じパスワードをもう一度入力してください。</p>
</div>
<div id="btn-area"><input id="btn-mainform" type="submit" value="パスワードを再設定する" /></div>
</form>
</div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/side_request_form.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>
