<div id="spotreader-header">
<table>
	<tbody>
		<tr>
			<td id="spotreader-logo">
				<a href="{$smarty.const.app_site_url}" target="_blank" title="スポットワードを開く"><img src="/img/spotword_logo_s.png" width="180" height="40" alt="スポットワード" /></a></td>
			<td id="spotreader-info"><div><p id="spotreader-title">{$form.book.title}</p><p id="spotreader-auther">{$form.user|@username}</p></div></td>
		</tr>
	</tbody>
</table>
<div id="spotreader-navi">
<input id="spotreader-navi-first" value="最初へ" type="button" />
<input id="spotreader-navi-prev" value="前へ" type="button" />
<input id="spotreader-navi-next" value="次へ" type="button" />
<input id="spotreader-navi-last" value="最後へ" type="button" />
</div>
<!-- #spotreader-header --></div>
<div id="epub-reader"></div>
{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('#epub-reader').spotreader({'epub_url':'/reader/{/literal}{$form.id}{literal}/epub'});
});
//]]>
</script>
{/literal}
