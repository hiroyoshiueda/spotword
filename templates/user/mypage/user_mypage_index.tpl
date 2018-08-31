{literal}
<script type="text/javascript">

</script>
{/literal}
<div id="main-contents">
<div id="mypage">
<div id="mypage-header" style="margin-bottom:20px;">
<div id="mypage-header-inner"><h1>{$form.user.penname}のマイページ</h1></div>
<!-- #mypage-header --></div>
<table id="mypage-tbl">
	<tbody>
		<tr>
			<td width="500">
				<h2>作品一覧</h2>
				<div style="padding:15px 0;">
				{if $form.book_list}
				{include file="_parts/top_book_list.tpl" book_list=$form.book_list book_rows=3 author_hide=true}
				{else}
				<p>作品はまだありません。</p>
				{/if}
				</div>
			</td>
			<td width="150">
				<div style="text-align:center;"><img src="{$form.user|@profileimg:'bigger'}" width="128" height="128" alt="{$form.user.penname}" /></div>
				<p style="padding:10px 0;">{$form.user.profile_msg|nl2br}</p>
			</td>
		</tr>
	</tbody>
</table>

<!-- #mypage --></div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/mydesk_side.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>
