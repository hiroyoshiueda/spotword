{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$("#login").focus();
});
function doSubmit()
{
	if ($('#agree').attr('checked')==false) {
		alert('利用規約に同意の上、チェックボックスにチェックを入れてください。');
		return false;
	}
	return true;
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="regist">

<form id="main_form" method="post" action="newuser_finish" onsubmit="return doSubmit();">
{include file="_parts/hidden.tpl"}
<input type="hidden" name="input_login" value="{if $form.errors.login || $form.input_login>0}1{/if}" />
<input type="hidden" name="input_penname" value="{if $form.errors.penname || $form.input_penname>0}1{/if}" />

<h1>{$form.htitle}</h1>

{if $form.errors}
<div class="errormsg" style="margin-top:20px;">
<img src="/img/icon-exclamation.png" width="16" height="16" class="icon-middle" alt="ERROR" /> 入力内容を修正してください</div>
{/if}
<div style="text-align:center;">
	{if $form.image_path!=""}
<img src="/tmp/{$form.image_path}" width="128" height="128" />
	{else}
<img src="{$form.user|@profileimg:'bigger'}" width="128" height="128" />
	{/if}
<p>{$form.twitter_id}</p>
</div>
<h2 style="margin-top:25px;">スポットワードの登録情報</h2>
{if $form.errors.login || $form.input_login>0}
<div class="column_wrapper{$form.errors.login|@errorclass}">
	<label for="login">
	 スポットワードID
	</label>
	<input id="login" class="ime-off" name="login" value="{$form.login}" maxlength="20" size="30" type="text" />
	{$form.errors.login|@errormsg}
	<p class="notice">
	 ※4文字以上20文字以内の半角英数字、半角ハイフン（ <strong>-</strong> ）のみ。
	</p>
	<p class="notice">
	 ※マイページのURLとしても公開されます。
	</p>
	<p class="notice">※登録後の変更はできません。</p>
</div>
{/if}
{if $form.errors.penname || $form.input_penname>0}
<div class="column_wrapper{$form.errors.penname|@errorclass}">
	<label for="penname">
	 ペンネーム（ニックネーム）
	</label>
	<input id="penname" class="ime-on" name="penname" value="{$form.penname}" maxlength="20" size="30" type="text" />
	{$form.errors.penname|@errormsg}
	<p class="notice">
	 ※3文字以上20文字以内。
	</p>
	<p class="notice">
	 ※作品はすべてペンネームで公開されます。
	</p>
</div>
{/if}
<div class="column_wrapper{$form.errors.birthday|@errorclass}">
	<label for="birthday">
	 生年月日<span class="lock"><img src="/img/icon-locktext.png" width="46" height="16" alt="（非公開）" align="top" />（設定で公開することもできます）</span>
	</label>
	{tag type="select" name="birthday_y" options=$birthdayYearOptions selected=$form.birthday_y blank="on"}年&nbsp;
	{tag type="select" name="birthday_m" options=$birthdayMonthOptions selected=$form.birthday_m blank="on"}月&nbsp;
	{tag type="select" name="birthday_d" options=$birthdayDayOptions selected=$form.birthday_d blank="on"}日
	{$form.errors.birthday|@errormsg}
	<p class="notice">※登録後の変更はできません。</p>
</div>
<div class="column_wrapper{$form.errors.gender|@errorclass}">
	<label for="gender">
	 性別<span class="lock"><img src="/img/icon-locktext.png" width="46" height="16" alt="（非公開）" align="top" />（設定で公開することもできます）</span>
	</label>
	{tag type="radio" name="gender" value="1" id="gender_1" checked=$form.gender}男性&nbsp;
	{tag type="radio" name="gender" value="2" id="gender_2" checked=$form.gender}女性
	{$form.errors.gender|@errormsg}
	<p class="notice">※登録後の変更はできません。</p>
</div>
{*
<h2 style="margin-top:15px;">メールマガジン</h2>
<div style="margin-left: 10px;">
	<input id="melmaga_0" name="melmaga_system" value="1" checked="checked" type="checkbox" disabled="disabled" />
	<label for="melmaga_0">（不定期）システムメンテナンスや新機能についてなどのお知らせ <span class="red">※必須</span></label>
	<br />
	{tag type="checkbox" name="melmaga_basic" value="1" id="melmaga_basic" checked=$form.melmaga_basic label="（不定期）人気本やおすすめ本の紹介"}
</div>*}
<div id="btn-area">
<p style="margin-bottom:5px;font-size:16px;"><input id="agree" name="agree" value="1"{if $form.agree=="1"} checked="checked"{/if} style="vertical-align: middle;" type="checkbox" /> <a href="/rule" target="_blank">利用規約</a>に同意する</p>
{$form.errors.agree|@errormsg}
<input class="btn-01" type="submit" value="登録する" />
</div>
</form>
<!-- #regist --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/regist_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>
