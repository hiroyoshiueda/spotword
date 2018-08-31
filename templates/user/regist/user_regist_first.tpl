{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$("#send_email").focus();
	$("#send-btn").removeAttr("disabled");
});
function doSend()
{
	$("#send-btn").attr("disabled", "disabled");
	return true;
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="regist">
<form id="mainform" method="post" action="send" onsubmit="return doSend();">
{include file="_parts/hidden.tpl"}
<h1>{$form.htitle}</h1>
<p id="step-navi-1">STEP1.メール送信</p>
<p style="margin:20px 0px;">スポットワードID登録用のメールをお送りいたします。</p>
<div style="margin:40px 0px;text-align:center;">
<p>メールアドレス</p>
<div><input id="send_email" name="send_email" value="{$form.send_email}" type="text" size="20">{$form.errors.send_email|@errormsg}</div>
</div>

<div class="infomsg" style="margin-bottom:30px;">
<ul style="font-size:88%;">
	<li class="with_dot">ドメイン受信設定などをご利用の方は、「{$smarty.const.APP_CONST_SITE_DOMAIN}」を受信できるよう設定してください。</li>
	<li class="with_dot">メールアドレスを公開したり、第三者に提供することはありません。</li>
	<li class="with_dot">スポットワードからのメールマガジン／各種お知らせをお送りさせていただきます。（不要な場合は登録後に解除できます。）</li>
	<li class="with_dot">スポットワードにおける個人情報の取り扱いは、<a href="/privacy">プライバシーポリシー</a>をご確認ください。</li>
</ul>
</div>
<div id="btn-area">
<input id="send-btn" class="btn-01" type="submit" value="確認メールを送信する" title="確認メールを送信する" />
</div>
</form>

<!-- #regist --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/regist_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>
