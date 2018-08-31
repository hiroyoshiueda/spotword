
<div id="main-contents">
<div id="mypage">
{if $form.user}
<div id="mypage-header">
<div id="mypage-header-inner"><h1>{$form.htitle}</h1></div>
<!-- #mypage-header --></div>
<p style="padding:15px 0;">{$form.user.profile_msg|makelink|nl2br}</p>
<table id="mypage-tbl">
	<tbody>
		<tr>
			<td width="680">
				<h2>作品一覧</h2>
				<div style="padding:15px 0;">
				{if $form.book_list}
				{include file="_parts/top_book_list.tpl" book_list=$form.book_list book_rows=4 author_hide=true}
				{else}
				<p>作品はまだありません。</p>
				{/if}
				</div>
			</td>
{*			<td width="300">

			</td>*}
		</tr>
	</tbody>
</table>
{else}
<div style="text-align:center;margin-bottom:20px;">
<h1 style="font-size:150%;color:#666;margin:20px 0;">マイページが見つかりませんでした</h1>
<div id="btn-area" style="margin:0 20px;"><p>お探しの作家のマイページは削除された可能性がございます。 </p></div>
</div>
{/if}
<!-- #mypage --></div>
<!-- #main-contents --></div>

<div id="main-side">
{if $form.user}
<div style="text-align:center;"><img src="{$form.user|@profileimg:'bigger'}" width="128" height="128" alt="{$form.user.penname}" /></div>
<div id="mypage-profile">
<h2>プロフィール</h2>
<dl>
	<dt>ペンネーム：</dt>
	<dd>{$form.user.penname}</dd>
{if $form.user.gender_public>0}
	<dt>性別：</dt>
	<dd>{$AppConst.gender[$form.user.gender]}</dd>
{/if}
{if $form.user.birthday_public>0}
	<dt>誕生日：</dt>
	<dd>{$form.user.birthday|date_zen_f:false}</dd>
{/if}
</dl>
<!-- #mypage-profile --></div>
<div style="margin-bottom:20px;">

</div>
{/if}
{include file="_parts/side_ad_text.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>

{literal}
<script type="text/javascript">
//<![CDATA[
$('span.item-price, span.item-reads, span.item-epubs, span.item-comments, span.item-evaluates').tipsy();
//]]>
</script>
{/literal}
