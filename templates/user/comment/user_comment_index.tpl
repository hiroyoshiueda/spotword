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
function doDeleteComment(commentId, bookId)
{
	if (confirm('このコメントを削除しますか？')) {
		jump('delete?id='+commentId+'&book_id='+bookId);
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
{*<div id="mydesk-tab-btn">
<ul>
	<li id="tab-01" class="mydesk-tab-btn-selected"><a href="javascript:selectTab('tab-01')">公開中({$form.public_list|@length})</a></li>
	<li id="tab-02"><a href="javascript:selectTab('tab-02')">非公開({$form.making_list|@length})</a></li>
</ul>
<!-- #mydesk-tab-btn --></div>*}
<!-- #mydesk-tab --></div>
{if $form.delete}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" style="vertical-align:middle;" /> 削除しました。</div>
{/if}
<div id="book-comment-list" style="border:0px;">
<div id="mydesk-tab-01">
{foreach from=$form.list item=d}

{include file="_parts/book_comment_list.tpl" comment=$d is_admin=true book=$form.book_data[$d.book_id]}

{foreachelse}
<p class="no-data">コメントはありません。</p>
{/foreach}
<!-- #mydesk-tab-01 --></div>

<!-- #book-comment-list --></div>
<!-- #mydesk --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/mydesk_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>

