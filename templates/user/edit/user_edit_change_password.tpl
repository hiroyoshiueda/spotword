{literal}
<script type="text/javascript">
//<![CDATA[
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="user-profile">
<form id="coverform" method="post" action="changed">
{include file="_parts/hidden.tpl"}

<div style="margin-bottom:5px;"><img src="/img/icon_back.png" widht="16" height="16" alt="戻る" align="top" /> <a href="/user/edit/" title="登録情報へ戻る">戻る</a></div>
<h1>パスワードの変更</h1>
{if $form.errors}
<div class="errormsg"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
{if $form.success}
<div class="successmsg" style="font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" align="top" /> パスワードが変更されました。次回ログイン時より新しいパスワードを使用してください。</div>
<div style="text-align:center;font-size:110%;"><a href="/user/edit/" title="登録情報へ戻る">登録情報へ戻る</a></div>
{else}

<div class="infomsg" style="margin:15px 0;">登録したパスワードを変更します。</div>

<h2>古いパスワードの確認</h2>
<div style="margin-bottom:20px;">
<table class="user-profile-tbl" style="width:100%;">
	<tbody>
		<tr>
			<th width="160">古いパスワード</th>
			<td><input name="old_password" value="{$form.old_password}" type="password" size="28" />
				{$form.errors.old_password|@errormsg}
			</td>
		</tr>
	</tbody>
</table>
</div>
<h2>新しいパスワードの登録</h2>
<div style="margin-bottom:20px;">
<table class="user-profile-tbl" style="width:100%;">
	<tbody>
		<tr>
			<th width="160">新しいパスワード</th>
			<td><input name="new_password" value="{$form.new_password}" type="password" size="28" />
				<p class="notice">※6文字以上20文字以内の半角英数記号（ <strong>. @ # % $ = _ * & + -</strong> ）のみ。</p>
				{$form.errors.new_password|@errormsg}
			</td>
		</tr>
		<tr>
			<th width="160">新しいパスワード（確認）</th>
			<td><input name="new_password_confirm" value="{$form.new_password_confirm}" type="password" size="28" />
				<p class="notice">※確認のため、同じパスワードをもう一度入力してください。</p>
				{$form.errors.new_password_confirm|@errormsg}
			</td>
		</tr>
	</tbody>
</table>
</div>
<div style="text-align:center;margin-bottom:20px;">
<input class="btn-02" name="submit-btn" value="パスワードを変更する" type="submit"></div>
{/if}
</form>
<!-- #user-profile --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/mydesk_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>
