{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('#chapter-sort').sortable({
		update: function(event, ui)
		{
			var ids_val = getPageIds();
			var id_val = $('#_id').val();
			var post_data = { ids : ids_val, id : id_val };
			ajaxPost('sort_page_api', post_data, function(data, dataType){
				if (data.status == AJAX_STATUS_SUCCESS) {
					if (data.modify_flag && data.modify_flag>0) {
						jump('/user/mydesk/page?id='+id_val);
					}
				} else {
					if (data.errors) ajaxValidateErrors(data.errors);
					if (data.message && data.message!='') alert(data.message);
				}
			});
		}
	});
});
function getPageIds()
{
	var ids = '';
	$('#chapter-sort li').each(function()
	{
		if (ids!='') ids += ',';
		ids += $(this).attr('id').replace('page-', '');
	});
	return ids;
}
function doDeletePage(id, pageId)
{
	if (confirm('この章を削除しますか？\n\n※削除した章の復旧はできません。\n※一時的に非表示にする場合は編集画面で「下書き」を選択してください。')) {
		jump('delete_page?id='+id+'&page_id='+pageId);
	}
	return false;
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div style="margin-bottom:5px;font-size:88%;"><img src="/img/icon_back.png" widht="16" height="16" alt="戻る" align="top" /> <a href="/user/mydesk/">本の一覧へ</a></div>
<div id="mydesk">
{include file="_parts/mydesk_successmsg.tpl"}
<div id="mydesk-tab">
<div id="mydesk-tab-title"><h2>{$form.htitle}</h2></div>
<div id="mydesk-tab-btn">
<ul>
	<li><a href="edit?id={$form.id}">基本情報</a></li>
	<li class="mydesk-tab-btn-selected">内容</li>
	<li><a href="image?id={$form.id}">画像管理</a></li>
	<li><a href="publish?id={$form.id}">公開設定</a></li>
</ul>
<!-- #mydesk-tab-btn --></div>
<!-- #mydesk-tab --></div>
{if $form.delete}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" style="vertical-align:middle;" /> 章を削除しました。</div>
{/if}
<form id="mainform" method="post" action="created">
{include file="_parts/hidden.tpl"}

<div id="mydesk-page-toolbar">
<img src="/img/icon-word_add.png" width="16" height="16" alt="追加" align="top" /> <a href="write?id={$form.id}">章を追加する</a>
<span style="float:right;font-size:88%;color:#666;"><img src="/img/icon-page_move.png" width="16" height="16" alt="並べ替え" align="top" />をドラッグすることで並べ替え</span>
</div>

<div id=chapter-list>
{if $form.page}
<ul id="chapter-sort">
{foreach item=d from=$form.page}
	<li id="page-{$d.page_id}"{if $d.status==1} class="status-draft"{/if}><span class="page-sort"><img src="/img/icon-page_move.png" width="16" height="16" alt="並べ替え" align="top" /> {$d.page_title}{if $d.status==1}（下書き）{/if}</span>
		<span class="page-tool"><img src="/img/icon-page_edit.png" width="16" height="16" alt="編集" align="top" /> <a href="write?id={$form.id}&page_id={$d.page_id}">編集</a>　<a href="#" onclick="return doDeletePage({$form.id}, {$d.page_id});" title="この章を削除する"><img src="/img/icon-page_delete.png" width="16" height="16" alt="削除" align="top" /></a></span></li>
{/foreach}
</ul>
{else}
	<p class="no-data">まだ内容は書かれていません。</p>
{/if}
</div>

<div id="mydesk-page-toolbar-bottom">
<img src="/img/icon-word_add.png" width="16" height="16" alt="追加" align="top" /> <a href="write?id={$form.id}">章を追加する</a>
</div>

</form>

<!-- #mydesk --></div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/mydesk_side_publish.tpl" publication=$form.publication}

{include file="_parts/mydesk_side.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>

