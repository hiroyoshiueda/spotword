{literal}
<script type="text/javascript">
//<![CDATA[

//]]>
</script>
{/literal}
<div id="main-contents">
<div id="user-profile">
<h1>登録情報</h1>
<div id="user-profile-img"><img src="{$userInfo|@profileimg:'small'}" width="48" height="48" alt="{$userInfo.penname}" />
<a href="picture" title="プロフィール画像を変更する">変更する</a>
<!-- #user-profile-img --></div>
<div id="user-profile-txt">
<p id="user-profile-penname">{$userInfo.penname}<span>（<a href="/{$userInfo.login}/">自分のマイページを見る</a>）</span></p>
<p style="color:#666;">登録日：{$userInfo.createdate|date_f}　更新日：{$userInfo.lastupdate|date_f}</p>
</div>
<div class="clear"></div>
<h2 style="margin:15px 0 0 0;"><img src="/img/icon_profile_msg.png" width="16" height="16" /> 自己紹介</h2>
<div id="user-profile-msg">
<p id="profile-msg">{if $userInfo.profile_msg==""}<span>クリックして入力</span>{else}{$userInfo.profile_msg|nl2br}{/if}</p>
<p style="text-align:right;"><img src="/img/icon_msg_edit.png" width="16" height="16" alt="変更" align="top" /> <a id="profile-msg-edit-btn">変更する</a></p>
</div>
<div id="profile-msg-edit"><textarea id="edit_profile_msg" name="edit_profile_msg" rows="5" cols="20">{$userInfo.profile_msg}</textarea>
<p style="text-align:right;"><input id="profile-msg-save-btn" value="保存する" type="button" /> <a id="profile-msg-cancel-btn">キャンセル</a></p></div>
<h2 style="margin:10px 0 0 0;"><img src="/img/icon_profile_edit.png" width="16" height="16" /> お客様情報</h2>
<div style="margin-bottom:20px;">
<table class="user-profile-tbl" style="width:100%;">
	<tbody>
		<tr>
			<th width="110">スポットワードID</th>
			<td>{$userInfo.login}</td>
			<td class="hd-line" width="80"><span class="publicmsg">公開</span></td>
		</tr>
{if $userInfo.open_login==0}
		<tr>
			<th>パスワード</th>
			<td><a href="change_password">変更する</a></td>
			<td class="hd-line"><span class="publicmsg">非公開</span></td>
		</tr>
{/if}
		<tr>
			<th>メールアドレス</th>
			<td>{if $userInfo.email!=""}{$userInfo.email}　{/if}<a href="change_email">変更する</a></td>
			<td class="hd-line"><span class="publicmsg">非公開</span></td>
		</tr>
		<tr>
			<th>ペンネーム</th>
			<td><div id="profile-edit-penname-text"><span>{$userInfo.penname}</span>　<a href="#" id="profile-edit-penname-btn">変更する</a></div>
				<div id="profile-edit-penname-edit"><input id="edit_penname" class="ime-on" name="edit_penname" value="{$userInfo.penname}" maxlength="20" size="15" type="text" />
					<p class="notice">※3文字以上20文字以内</p>
					<div><input id="profile-edit-penname-save" value="保存する" type="button" /> <a href="#" id="profile-edit-penname-cancel">キャンセル</a></div>
				</div>
			</td>
			<td class="hd-line"><span class="publicmsg">公開</span></td>
		</tr>
		<tr>
			<th>性別</th>
			<td>{$AppConst.gender[$userInfo.gender]}</td>
			<td class="hd-line">{tag type="select" id="gender_public" name="gender_public" options=$publicOptions selected=$userInfo.gender_public}
				<div id="gender_public-msg" style="color:#f00;font-size:88%;" class="hide">変更しました</div></td>
		</tr>
		<tr>
			<th>生年月日</th>
			<td>{$userInfo.birthday|date_zen_f:false}</td>
			<td class="hd-line">{tag type="select" id="birthday_public" name="birthday_public" options=$publicOptions selected=$userInfo.birthday_public}
				<div id="birthday_public-msg" style="color:#f00;font-size:88%;" class="hide">変更しました</div></td>
		</tr>
{if $userInfo.open_login==0}
		<tr>
			<th>郵便番号</th>
			<td><div id="profile-edit-zip-text">〒<span>{$userInfo.zip}</span>　<a href="#" id="profile-edit-zip-btn">変更する</a></div>
				<div id="profile-edit-zip-edit">〒<input id="edit_zip" class="ime-off" name="edit_zip" value="{$userInfo.zip}" maxlength="8" size="10" type="text" />
					{*<p class="notice">※3文字以上12文字以内</p>*}
					<div><input id="profile-edit-zip-save" value="保存する" type="button" /> <a href="#" id="profile-edit-zip-cancel">キャンセル</a></div>
				</div>
			</td>
			<td class="hd-line"><span class="publicmsg">非公開</span></td>
		</tr>
{/if}
	</tbody>
</table>
</div>
<!-- #user-profile --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/mydesk_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>
{literal}
<script type="text/javascript">
//<![CDATA[
// for profile-msg
$('#profile-msg, #profile-msg-edit-btn').click(function()
{
	var msg_html = $('#profile-msg').html();
	msg_html = msg_html.replace(/<\/?span>/ig, '').replace(/\n+/g, '').replace(/<br ?\/?>/ig, '\n');
	$('#user-profile-msg').hide();
	$('#edit_profile_msg').val((msg_html=='クリックして入力')?'':msg_html);
	$('#profile-msg-edit').show();
	return false;
});
$('#profile-msg-save-btn').click(function()
{
	var msg_text = $('#edit_profile_msg').val();
	var post_data = { 'edit_profile_msg' : msg_text, mode: 'profile_msg' };
	ajaxPost('profile_edit_api', post_data, function(data, dataType)
	{
		if (data.status == AJAX_STATUS_SUCCESS) {
			$('#profile-msg-edit').hide();
			$('#user-profile-msg').show();
			if (msg_text == '') {
				$('#profile-msg').html('<span>クリックして入力</span>');
			} else {
				$('#profile-msg').html(msg_text.nl2br());
			}
		} else {
			if (data.errors) ajaxValidateErrors(data.errors);
			if (data.message && data.message!='') alert(data.message);
		}
	});
	return false;
});
$('#profile-msg-cancel-btn').click(function()
{
	$('#profile-msg-edit').hide();
	$('#user-profile-msg').show();
	return false;
});
// for penname
$('#profile-edit-penname-btn').click(function()
{
	$('#profile-edit-penname-text').hide();
	$('#profile-edit-penname-edit').show();
	return false;
});
$('#profile-edit-penname-save').click(function()
{
	var penname_val = $('#edit_penname').val().trim();
	if (penname_val == '') {
		ajaxValidateErrors({ 'edit_penname' : ['変更するペンネームを入力してください。'] });
		return false;
	}
	var post_data = { 'edit_penname' : penname_val, mode : 'penname' };
	ajaxPost('profile_edit_api', post_data, function(data, dataType)
	{
		if (data.status == AJAX_STATUS_SUCCESS) {
			$('#profile-edit-penname-edit').hide();
			$('#edit_penname').val(data.edit_penname);
			$('#profile-edit-penname-text span').html(data.edit_penname);
			$('#profile-edit-penname-text').show();
			jump('/user/edit/');
		} else {
			if (data.errors) ajaxValidateErrors(data.errors);
			if (data.message && data.message!='') alert(data.message);
		}
	});
	return false;
});
$('#profile-edit-penname-cancel').click(function()
{
	$('#profile-edit-penname-edit').hide();
	$('#profile-edit-penname-text').show();
	return false;
});
// for zip
$('#profile-edit-zip-btn').click(function()
{
	$('#profile-edit-zip-text').hide();
	$('#profile-edit-zip-edit').show();
	return false;
});
$('#profile-edit-zip-save').click(function()
{
	var zip_val = $('#edit_zip').val().trim();
	if (zip_val == '') {
		ajaxValidateErrors({ 'edit_zip' : ['変更する郵便番号を入力してください。'] });
		return false;
	}
	var post_data = { 'edit_zip' : zip_val, mode : 'zip' };
	ajaxPost('profile_edit_api', post_data, function(data, dataType)
	{
		if (data.status == AJAX_STATUS_SUCCESS) {
			$('#profile-edit-zip-edit').hide();
			$('#edit_zip').val(data.edit_zip);
			$('#profile-edit-zip-text span').html(data.edit_zip);
			$('#profile-edit-zip-text').show();
		} else {
			if (data.errors) ajaxValidateErrors(data.errors);
			if (data.message && data.message!='') alert(data.message);
		}
	});
	return false;
});
$('#profile-edit-zip-cancel').click(function()
{
	$('#profile-edit-zip-edit').hide();
	$('#profile-edit-zip-text').show();
	return false;
});
$('#gender_public').change(function()
{
	$('#gender_public-msg').hide();
	var public_val = $(this).val();
	var post_data = { 'edit_gender_public' : public_val, mode : 'gender_public' };
	ajaxPost('profile_edit_api', post_data, function(data, dataType)
	{
		if (data.status == AJAX_STATUS_SUCCESS) {
			$('#gender_public-msg').fadeIn('slow');
		} else {
			if (data.errors) ajaxValidateErrors(data.errors);
			if (data.message && data.message!='') alert(data.message);
		}
	});
	return false;
});
$('#birthday_public').change(function()
{
	$('#birthday_public-msg').hide();
	var public_val = $(this).val();
	var post_data = { 'edit_birthday_public' : public_val, mode : 'birthday_public' };
	ajaxPost('profile_edit_api', post_data, function(data, dataType)
	{
		if (data.status == AJAX_STATUS_SUCCESS) {
			$('#birthday_public-msg').fadeIn('slow');
		} else {
			if (data.errors) ajaxValidateErrors(data.errors);
			if (data.message && data.message!='') alert(data.message);
		}
	});
	return false;
});
//]]>
</script>
{/literal}