{literal}
<script type="text/javascript">
//<![CDATA[
function doSubmit()
{
	if (confirm("この内容で送信してよろしいですか？")) {
		$('#submit-btn').attr("disabled", "disabled");
		return true;
	}
	return false;
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="contact">
<h1 class="page-title">お問い合わせ</h1>
<div class="infomsg">
<p><strong>携帯電話メールアドレスでお問合せいただくお客様へ</strong><br />
迷惑メール拒否設定などをされている場合、返信メールがお手元に届かない場合がございますので<br />
「{$smarty.const.APP_CONST_SITE_DOMAIN}」を必ずドメイン指定受信ができるよう設定をお願いいたします</p>
</div>
{if $form.errors}
<div class="errormsg" style="margin-top:20px;"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}
{$form.sys_errors.msg|@errormsg}
<form name="mainform" method="post" action="formsend" onsubmit="return doSubmit();">
{include file="_parts/hidden.tpl"}
<div class="column_wrapper{$form.errors.subject|@errorclass}">
	<label for="subject">
	 件名
	</label>
	<input id="subject" name="subject" style="width:400px;" class="ime-on" value="{$form.subject}" size="30" type="text" />
	{$form.errors.subject|@errormsg}
</div>
<div class="column_wrapper{$form.errors.body|@errorclass}">
	<label for="body">
	 問い合わせ内容
	</label>
	<textarea id="body" name="body" style="width:400px;height:150px;" class="ime-on" rows="5" cols="10">{$form.body}</textarea>
	{$form.errors.body|@errormsg}
</div>
<div class="column_wrapper{$form.errors.username|@errorclass}">
	<label for="username">
	 お名前
	</label>
	<input id="username" name="username" style="width:400px;" class="ime-on" value="{$form.username}" size="30" type="text" />
	{$form.errors.username|@errormsg}
</div>
<div class="column_wrapper{$form.errors.useremail|@errorclass}">
	<label for="useremail">
	 メールアドレス
	</label>
	<input id="useremail" name="useremail" style="width:400px;" class="ime-off" value="{$form.useremail}" size="30" type="text" />
	{$form.errors.useremail|@errormsg}
	<p class="notice">※このメールアドレスに回答を返信します。お間違いのないように入力してください。</p>
</div>
<div id="btn-area"><input id="submit-btn" class="btn-02" type="submit" value="送信する" /></div>
</form>
</div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/side_request_form.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>
