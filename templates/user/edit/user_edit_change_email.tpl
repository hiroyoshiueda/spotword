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
<h1>メールアドレスの変更</h1>
{if $form.errors}
<div class="errormsg"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
{if $form.success}
<div class="successmsg" style="font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" align="top" /> 新しいメールアドレスに確認メールを送信しました。</div>
<div style="text-align:center;margin-bottom:20px;">送信されたメールに記載のURLをクリックしていただくことで、<br />新しいメールアドレスへの変更が完了します。</div>
<div style="text-align:center;font-size:110%;"><a href="/user/edit/" title="登録情報へ戻る">登録情報へ戻る</a></div>
{else}

<div class="infomsg" style="margin:15px 0;">登録したメールアドレスを変更します。</div>

<h2>新しいメールアドレスの登録</h2>
<div style="margin-bottom:20px;">
<table class="user-profile-tbl" style="width:100%;">
	<tbody>
		<tr>
			<th width="190">新しいメールアドレス</th>
			<td><input name="new_email" value="{$form.new_email}" type="text" size="26" />
				{$form.errors.new_email|@errormsg}
			</td>
		</tr>
		<tr>
			<th width="190">新しいメールアドレス（確認）</th>
			<td><input name="new_email_confirm" value="{$form.new_email_confirm}" type="text" size="26" />
				{$form.errors.new_email_confirm|@errormsg}
			</td>
		</tr>
	</tbody>
</table>
</div>
<div style="text-align:center;margin-bottom:20px;">
<input class="btn-02" name="submit-btn" value="メールアドレスを変更する" type="submit"></div>
{/if}
</form>
<!-- #user-profile --></div>
<!-- #main-contents --></div>

<div id="main-side">

<!-- #main-side --></div>

<div class="clear"></div>
