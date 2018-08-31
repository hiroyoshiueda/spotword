{if $publication.publication_id>0}
<div class="side-win">
<div class="side-win2-top"><h2>本の公開</h2></div>
<div class="side-win2-inner">
<div id="side-publish">
<div style="margin-bottom:10px;"><p>本の状態：{if $publication.status==0}公開中{else}非公開{/if}</p>
{if $publication.publish_date!=""}
<p>公開日：{$publication.publish_date|date_zen_f} 第{$publication.version}版</p>
{/if}
<p>文字数：{$publication.char_length|number_format} 文字（{$publication.valid_page_count}章）</p>
</div>
{if $publication.status==0}
	{if $publication.publish_modify_flag==1}
	<div class="infomsg" style="margin:0 0 10px 0;">
	<form id="revisionform" method="post" action="gopublic">
	{include file="_parts/hidden.tpl"}
	<input type="hidden" name="loc" value="{$base_url}" />
	<input type="hidden" name="version" value="{$publication.version}" />
	<p>前回公開後に編集した内容はまだ公開されていません。</p>
	<p id="revision-btn-area"><img src="/img/icon_book_edit.png" width="16" height="16" align="top" />&nbsp;<a href="#" id="revision-btn" onclick="revisionInputOn();return false;" title="内容の変更を読者に知ってもらう場合">改訂して編集内容を反映する</a></p>
	<div id="revision-input-area" style="display:none;margin-bottom:5px;">
	<p style="margin-top:10px;font-weight:bold;">改訂内容</p>
	<textarea id="revision_body" name="revision_body" style="width:245px;height:100px;padding:5px;" rows="5" cols="10"></textarea>
	<p class="errormsg-bg" id="revision_body_errmsg"></p>
	<div style="margin-top:5px;"><input value="改訂する" onclick="goRevision();" class="btn-02" type="button" />　<a href="#" onclick="revisionInputOff();return false;">キャンセル</a></div>
	</div>
	</form>
	<form id="updateform" method="post" action="gopublic">
	{include file="_parts/hidden.tpl"}
	<input type="hidden" name="loc" value="{$base_url}" />
	<input type="hidden" name="update" value="1" />
	<p style="padding-top:5px;"><img src="/img/icon_update_book.png" width="16" height="16" align="top" />&nbsp;<a href="#" id="update-btn" onclick="goUpdate();return false;" title="誤字脱字など簡単な変更を反映する場合">そのまま保存して公開する</a></p>
	</form>
	<!-- .infomsg --></div>
	{/if}
	{* 非公開 *}
	<form id="publicform" method="post" action="goclosed">
	{include file="_parts/hidden.tpl"}
	<input type="hidden" name="loc" value="{$base_url}" />
	<div><input id="side-publish-close-btn" onclick="goClosed();" value="公開をやめる" type="button" /></div>
	</form>
{elseif $publication.status==1}
	{* 公開 *}
	{if $publication.char_length<$smarty.const.APP_CONST_PAGE_MAX_WORD_SIZE && $publication.valid_page_count<$smarty.const.APP_CONST_PAGE_MAX_SIZE}
	<div style="padding:5px 0;margin:10px 0 0 0;"><input id="side-publish-publicoff-btn" value="公開する" type="button" /></div>
	<p class="errormsg-bg" >まだ公開に必要な{$smarty.const.APP_CONST_PAGE_MAX_WORD_SIZE}文字か、有効な内容が{$smarty.const.APP_CONST_PAGE_MAX_SIZE}章分に達していないため公開できません。</p>
	{else}
	<form id="publicform" method="post" action="gopublic">
	{include file="_parts/hidden.tpl"}
	<input type="hidden" name="loc" value="{$base_url}" />
	<div class="infomsg" style="margin:10px 0 0 0;">
	<p style="font-weight:bold;">著作権の確認</p>
	<p style="padding:5px 0;">この作品は他人の著作権を侵害していませんか？</p>
	<label style="font-weight:normal;"><input type="checkbox" id="copyright_flag" name="copyright_flag" value="1" /> <u>はい。侵害していません。</u></label>
	<div style="padding:5px 0 0 0;margin:10px 0 0 0;"><input id="side-publish-public-btn" onclick="goPublic();" value="公開する" type="button" /></div>
	<!-- .infomsg --></div>
	</form>
	{/if}
{/if}
<!-- #side-publish --></div>
<!-- #side-win-inner --></div>
<div class="side-win2-bottom"></div>
<!-- #side-win --></div>
{literal}
<script type="text/javascript">
//<![CDATA[
$('a#revision-btn, a#update-btn').tipsy({gravity:'s'});
//]]>
</script>
{/literal}
{/if}
