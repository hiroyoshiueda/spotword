{literal}
<script type="text/javascript">
//<![CDATA[
function selectTab(tabName)
{
	for (var i=1; i<=3; i++) {
		$('#tab-0' + i).removeClass('mydesk-tab-btn-selected');
		$('#mydesk-tab-0' + i).css('display', 'none');
	}
	$('#' + tabName).addClass('mydesk-tab-btn-selected');
	$('#mydesk-' + tabName).show();
}
function doDeleteBook(id)
{
	if (confirm('この本を削除しますか？\n※削除後の復旧はできません。')) {
		jump('delete_book?id='+id);
	}
	return false;
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="mydesk">
<div id="mydesk-tab">
<div id="mydesk-tab-title"><h2>{$form.htitle}</h2></div>
<div id="mydesk-tab-btn">
<ul>
	<li id="tab-01"{if !$form.delete} class="mydesk-tab-btn-selected"{/if}><a href="javascript:selectTab('tab-01')">公開中({$form.public_list|@length})</a></li>
	<li id="tab-02"{if $form.delete} class="mydesk-tab-btn-selected"{/if}><a href="javascript:selectTab('tab-02')">非公開({$form.making_list|@length})</a></li>
	{*<li id="tab-03"><a href="javascript:selectTab('tab-03')">作成中(1)</a></li>*}
</ul>
<!-- #mydesk-tab-btn --></div>
<!-- #mydesk-tab --></div>
{if $form.setup}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" style="vertical-align:middle;" /> マイデスクを開設しました。</div>
{/if}
<div id="mybook">
<div id="mydesk-tab-01"{if $form.delete} style="display:none;"{/if}>
{foreach from=$form.public_list item=d}
<div class="mybook-list">
<table>
    <tbody>
        <tr>
            <td rowspan="2" class="mybook-list-img"><img src="{$d.cover_s_path|cover_img_src:$userInfo.id}" width="60" height="80" /></td>
            <td><div class="mybook-list-title"><img src="/img/icon-edit.png" width="16" height="16" /> <a href="edit?id={$d.publication_id}">{$d.title}</a>
            	<div class="mybook-list-right">
	            	<span class="mybook-list-date">{$d.lastupdate|datetime_zen_f}</span>
	            	{*<span class="mybook-list-tool"><a href="#"><img src="/img/icon-trash.gif" width="16" height="16" align="top" /></a></span>*}
            	</div>
            	</div>
            	<div class="mybook-list-desc">{$d.description|show_description|str_cut:'120'}</div>
            </td>
        </tr>
        <tr>
            <td><div class="mybook-list-info"><img src="/img/icon-category-s.gif" width="16" height="16" align="top" /> {$AppConst.book_category[$d.category_id]}　<img src="/img/icon-font-s.gif" width="16" height="16" align="top" /> {$d.char_length|number_format}文字</div></td>
        </tr>
    </tbody>
</table>
</div>
{foreachelse}
<p class="no-data">公開中の本はありません。</p>
{/foreach}
<!-- #mydesk-tab-01 --></div>
<div id="mydesk-tab-02"{if $form.delete} style="display:block;"{/if}>
{if $form.delete}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" style="vertical-align:middle;" /> 本を削除しました。</div>
{/if}
{foreach from=$form.making_list item=d}
<div class="mybook-list">
<table>
    <tbody>
        <tr>
            <td rowspan="2" class="mybook-list-img"><img src="{$d.cover_s_path|cover_img_src:$userInfo.id}" width="60" height="80" /></td>
            <td><div class="mybook-list-title"><img src="/img/icon-edit.png" width="16" height="16" /> <a href="edit?id={$d.publication_id}">{$d.title}</a>
            	<div class="mybook-list-right">
	            	<span class="mybook-list-date">{$d.lastupdate|datetime_zen_f}</span>
	            	<span class="mybook-list-tool"><a href="#" onclick="return doDeleteBook({$d.publication_id});"><img src="/img/icon-trash.gif" width="16" height="16" align="top" /></a></span>
            	</div>
            	</div>
            	<div class="mybook-list-desc">{$d.description|show_description|str_cut:'120'}</div>
            </td>
        </tr>
        <tr>
            <td><div class="mybook-list-info"><img src="/img/icon-category-s.gif" width="16" height="16" align="top" /> {$AppConst.book_category[$d.category_id]}　<img src="/img/icon-font-s.gif" width="16" height="16" align="top" /> {$d.char_length|number_format}文字</div></td>
        </tr>
    </tbody>
</table>
</div>
{foreachelse}
<p class="no-data">非公開の本はありません。</p>
{/foreach}
<!-- #mydesk-tab-02 --></div>

<!-- #mybook --></div>
<!-- #mydesk --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/mydesk_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>

