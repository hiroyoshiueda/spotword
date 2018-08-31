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

<form id="main_form" method="post" action="confirm" onsubmit="return doSubmit();">
{include file="_parts/hidden.tpl"}

<h1>{$form.htitle}</h1>
<p id="step-navi-2">STEP2.登録情報の入力</p>

{if $form.errors}
<div class="errormsg" style="margin-top:20px;">
<img src="/img/icon-exclamation.png" width="16" height="16" class="icon-middle" alt="ERROR" /> 入力内容に誤りがあります</div>
{/if}
<h2 style="margin-top:25px;">スポットワードのログイン情報</h2>
<div class="column_wrapper{$form.errors.login|@errorclass}">
	<label for="login">
	 スポットワードID（ログインID）
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
<div class="column_wrapper{$form.errors.password|@errorclass}">
	<label for="password">
	 パスワード
	</label>
	<input id="password" name="password" value="{$form.password}" maxlength="20" size="30" type="password" />
	<p class="notice">
	 ※6文字以上20文字以内の半角英数記号（ <strong>. @ # % $ = _ * & + -</strong> ）のみ。
	</p>
	<input id="password_confirm" name="password_confirm" value="{$form.password_confirm}" maxlength="20" size="30" type="password" />
	{$form.errors.password|@errormsg}
	<p class="notice">
	 ※確認のため、同じパスワードをもう一度入力してください。
	</p>
</div>
<h2 style="margin-top:15px;">お客様の情報</h2>
<p style="color:#666;margin:0px 0px 15px 10px;">ログイン情報等をお忘れの場合、次の情報を元に本人確認を行います。</p>
<div class="column_wrapper{$form.errors.email|@errorclass}">
	<label for="email">
	 メールアドレス<span class="lock"><img src="/img/icon-locktext.png" width="46" height="16" alt="（非公開）" align="top" /></span>
	</label>
	<p>{$form.email}</p>
	{$form.errors.email|@errormsg}
</div>
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
<div class="column_wrapper{$form.errors.zip|@errorclass}">
	<label for="zip">
	 郵便番号<span class="lock"><img src="/img/icon-locktext.png" width="46" height="16" alt="（非公開）" align="top" /></span>
	</label>
	<span id="zip_icon" style="font-size: 123%;font-weight: bold;">〒</span>
	<input id="zip" class="ime-off" name="zip" value="{$form.zip}" maxlength="8" size="8" type="text" />
	{$form.errors.zip|@errormsg}
	  {*
	  <span class="notice" style="padding-left: 5px;" id="show_country_select">
	    <a href="#" onclick="$('user_zip').style.color='#ccc'; $('zip_icon').style.color='#ccc'; $('country_select').show(); $('user_zip').disabled = true; $('show_country_select').hide(); return false;">海外にお住まいですか？</a>
	  </span>
	  *}
</div>
<h2 style="margin-top:15px;">メールマガジン</h2>
<div style="margin-left: 10px;">
	<input id="melmaga_0" name="melmaga_system" value="1" checked="checked" type="checkbox" disabled="disabled" />
	<label for="melmaga_0">（不定期）システムメンテナンスや新機能についてなどのお知らせ <span class="red">※必須</span></label>
	<br />
	{tag type="checkbox" name="melmaga_basic" value="1" id="melmaga_basic" checked=$form.melmaga_basic label="（不定期）人気本やおすすめ本の紹介"}
</div>
<div id="btn-area">
<p style="margin-bottom:5px;font-size:16px;"><input id="agree" name="agree" value="1"{if $form.agree=="1"} checked="checked"{/if} style="vertical-align: middle;" type="checkbox" /> <a href="/rule" target="_blank">利用規約</a>に同意する</p>
{$form.errors.agree|@errormsg}
<input class="btn-01" type="submit" value="確認画面に進む" />
</div>
</form>
<!-- #regist --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/regist_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>
