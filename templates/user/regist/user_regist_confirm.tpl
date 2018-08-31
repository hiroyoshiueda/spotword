{literal}
<script type="text/javascript">
//<![CDATA[
function doSubmit()
{
	$('#regist-btn').attr("disabled", "disabled");
	$('#mainform').submit();
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="regist">

<form id="mainform" method="post" action="complete">
{include file="_parts/hidden.tpl"}
</form>

<h1>{$form.htitle}</h1>
<p id="step-navi-2">STEP2.登録情報の入力</p>

<div class="infomsg" style="margin-top:20px;"><strong>まだ登録は完了していません。</strong>入力内容を確認し、登録を完了させてください。</div>

<table class="baseTable" style="width:100%;">
	<tbody>
		<tr>
			<th width="140">スポットワードID</th>
			<td>{$form.login}</td>
		</tr>

		<tr>
			<th>パスワード</th>
			<td><span style="color:#666;">{$form.password_text}</span>
			  <span class="notice">
			    （セキュリティ上、伏せて表示しています）
			  </span>
			</td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td><span style="font-weight:bold;">{$form.email}</span></td>
		</tr>
		<tr>
			<th>ペンネーム</th>
			<td>{$form.penname}</td>
		</tr>
		<tr>
			<th>生年月日</th>
			<td>{$form.birthday_y}年{$form.birthday_m}月{$form.birthday_d}日</td>
		</tr>
		<tr>
			<th>性別</th>
			<td>{$AppConst.gender[$form.gender]}</td>
		</tr>
		<tr>
			<th>郵便番号</th>
			<td>〒{$form.zip}</td>
		</tr>
		<tr>
			<th>メールマガジン</th>
			<td>スポットワードからのお知らせ：受け取る<br />
			人気本やおすすめ本の紹介：{if $form.melmaga_basic==1}受け取る{else}受け取らない{/if}</td>
		</tr>
	</tbody>
</table>

<form id="backform" method="post" action="index">
{include file="_parts/hidden.tpl"}
</form>
<div id="btn-area">
<p style="margin-bottom:5px;text-align:right;"><a href="#" onclick="$('#backform').submit();return false;">&laquo; 入力した内容を修正する</a></p>
<input id="regist-btn" class="btn-01" onclick="doSubmit();" type="button" value="登録する" />
</div>
<!-- #regist --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/regist_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>
