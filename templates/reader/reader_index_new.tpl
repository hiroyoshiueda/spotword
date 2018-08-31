<div id="spotreader-header">
<table>
	<tbody>
		<tr>
			<td id="spotreader-logo">
				<a href="{$smarty.const.app_site_url}" target="_blank" title="スポットワードを開く"><img src="/img/spotword_logo_s.png" width="180" height="40" alt="スポットワード" /></a></td>
			<td id="spotreader-info">
				<div><p id="spotreader-title">{$form.book.title}</p><p id="spotreader-auther">{$form.user|@username}</p></div></td>
		</tr>
	</tbody>
</table>
<div id="spotreader-navi">
<input id="spotreader-navi-first" value="最初へ" type="button" />
<input id="spotreader-navi-prev" value="前へ" type="button" />
<input id="spotreader-navi-next" value="次へ" type="button" />
<input id="spotreader-navi-last" value="最後へ" type="button" />
<input id="spotreader-navi-big" value="文字サイズを大きく" type="button" />
<input id="spotreader-navi-small" value="文字サイズを小さく" type="button" />
</div>
<!-- #spotreader-header --></div>
<div id="epub-reader"></div>
<div id="book-contents" style="display:none;">
{foreach item=d from=$form.page}
<div id="book-contents-{$d.page_order}">
{$d.page_contents|smarty:nodefaults}
</div>
{/foreach}
<!-- #book-contents --></div>
{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
//	var loading = $('<img />').attr('src', '/js/spotreader/images/loading.gif');
//	var loadingObj = $('<div id="loading"><div>読み込み中...</div></div>');
//	loadingObj.css({'position':'absolute', 'top':($(window).height()-19) / 2, 'left':($(window).width()-220) / 2, 'color':'#666', 'text-align':'center'});
//	loadingObj.prepend(loading);
//	$('#epub-reader').append(loadingObj);
	$('#epub-reader').spotreader({'load':'#book-contents','cover':'{/literal}{if $form.book.cover_path!=""}{$form.cover_path}/{$form.book.cover_path}{/if}{literal}'});
});
//]]>
</script>
{/literal}
