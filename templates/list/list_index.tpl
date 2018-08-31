<div id="main-contents">
<div id="book-list-tab">
<ul>
	<li{if $form.pagetype=="popular"} class="book-list-tab-selected"{/if}><a href="/list/" title="人気本">人気本</a></li>
	<li{if $form.pagetype=="newarrivals"} class="book-list-tab-selected"{/if}><a href="/list/newarrivals" title="新着本">新着本</a></li>
	<li{if $form.pagetype=="category"} class="book-list-tab-selected"{/if}><a href="/list/category/" title="ジャンル一覧">ジャンル一覧</a></li>
</ul>
<div class="clear"></div>
<!-- #book-list-tab --></div>
<div id="book-list">
<h1 class="page-title">{$form.htitle}</h1>
{if $form.category_list}
{include file="_parts/top_category_list.tpl" category_list=$form.category_list category_rows=3}
{else}
<table id="book-tbl">
	<tbody>
{foreach item=d from=$form.list}
	{assign var='user' value=$form.user_data[$d.user_id]}
	<tr>
		<td><div class="book-tbl-cover"><a href="/book/{$d.book_id}/" title="{$d.title}"><img class="item-cover" src="{$d.cover_s_path|cover_img_src:$d.user_id}" alt="{$d.title}" width="60" height="80" /></a></div></td>
		<td class="book-tbl-body">
			<h2 class="book-tbl-title"><a href="/book/{$d.book_id}/" title="{$d.title}">{$d.title}</a><span>{$user|@username:true}</span></h2>
			<p class="book-tbl-description">{$d.description|show_description}</p>
			<p class="book-tbl-info">
				<span class="item-price" title="価格">{if $d.charge_flag==0}無料{/if}</span>
				<span class="item-reads" title="閲覧数">{$d.pv_total}</span>
				<span class="item-epubs" title="ePubダウンロード数">{$d.epub_total}</span>
				<span class="item-comments" title="コメント数">{$d.comment_total}</span>
				<span class="item-evaluates" title="評価">{$d.evaluate_total}</span>
				<span class="publish-date">公開日：{$d.publish_date|date_f}</span></p>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td>投稿作品はありません。</td>
	</tr>
{/foreach}
	</tbody>
</table>
{/if}
{if $form.total>-1}
<div id="book-list-pager-bottom">
<table width="100%">
	<tr>
		{*<td width="40%" align="left" valign="bottom">{pageinfo total=$form.total limit=$smarty.const.APP_CONST_PAGE_LIMIT}</td>
		<td width="20%" align="center"></td>
		<td width="40%" align="right">{pager total=$form.total limit=$smarty.const.APP_CONST_PAGE_LIMIT}</td>*}
		<td align="center">{pager total=$form.total limit=$smarty.const.APP_CONST_PAGE_LIMIT}</td>
	</tr>
</table>
<!-- #book-list-pager-bottom --></div>
{/if}
<!-- #book-list --></div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/side_ad.tpl"}

{include file="_parts/side_request_form.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>

{literal}
<script type="text/javascript">
//<![CDATA[
$('span.item-price, span.item-reads, span.item-epubs, span.item-comments, span.item-evaluates').tipsy();
//]]>
</script>
{/literal}
