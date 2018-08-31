{literal}
<script type="text/javascript">
//<![CDATA[
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="regist">
<h1>{$form.htitle}</h1>
<p id="step-navi-1">STEP1.メール送信</p>
{if $form.is_send_error}
<div class="errormsg" style="font-size:110%;margin-top:20px;">
<img src="/img/icon-exclamation.png" width="16" height="16" alt="ERROR" class="icon-middle" /> 何らかの理由で送信できませんでした。</div>
<div style="margin-bottom:20px;padding-left:20px;">
<p>送信できなかった理由には以下のケースが考えられます。</p>
<ul>
	<li class="with_dot">既に登録済みであるメールアドレスの場合</li>
	<li class="with_dot">過去に利用規約に違反し、登録できないメールアドレスとなっている場合</li>
	<li class="with_dot">その他、何らかの理由により登録不可となっているメールアドレスの場合</li>
</ul>
</div>
<p style="text-align:center;font-size:110%;"><a href="/user/regist/first">もう一度、メールアドレスを確認して送信してみる</a></p>
{else}
<div class="successmsg" style="font-weight:bold;font-size:110%;margin-top:20px;">
<img src="/img/icon-success.png" width="16" height="16" alt="成功" class="icon-middle" /> 本登録のご案内を送信しました。
</div>

<p style="margin-bottom:20px;text-align:center;">
<span style="font-weight:bold;color:#f00;">24時間以内</span>に届いたメールに記載されたURLをクリックし、次のステップへ進んでください。
</p>
<p style="text-align:center;">送信したメールアドレス</p>
<p style="margin-bottom:30px;font-weight:bold;font-size:200%;text-align:center;">{$form.send_email}</p>

<div class="infomsg">
<p style="font-weight:bold;font-size:110%;margin-bottom:5px;">
<img src="/img/icon-alert.png" width="16" height="16" alt="注意" class="icon-middle" /> メールが届かない？</p>
<ul style="font-size:88%;margin-left:10px;">
	<li class="with_dot">メールアドレスが正しいかご確認の上、間違っていた場合は再登録してください。</li>
	<li class="with_dot">お使いのメールソフトで、迷惑メールフォルダに入っていないかご確認ください。</li>
	<li class="with_dot">既に登録済のメールアドレスには送信されません。</li>
</ul>
</div>
{/if}
<!-- #regist --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/regist_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>
