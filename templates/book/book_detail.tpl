{literal}<script type="text/javascript">
//<![CDATA[
{/literal}
var BASE_FONT_FAMILY = '{$smarty.const.APP_CONST_BASE_FONT_FAMILY|smarty:nodefaults}';
var SERIF_FONT_FAMILY = '{$smarty.const.APP_CONST_SERIF_FONT_FAMILY|smarty:nodefaults}';
var DEFAULT_BOOK_TAB = '{$form.book_tab}';
var CURRENT_BOOK_TAB = '{$form.book_tab}';
var IS_LOGIN = {if $userInfo.id>0}true{else}false{/if};
{literal}
function selectBookTab(tabname)
{
	var newname = tabname || DEFAULT_BOOK_TAB;
	if (newname != CURRENT_BOOK_TAB) {
		$('#book-' + CURRENT_BOOK_TAB).hide();
		$('#book-' + newname).show();
		$('#book-tab-' + CURRENT_BOOK_TAB).removeClass('book-tab-selected');
		$('#book-tab-' + newname).addClass('book-tab-selected');
		CURRENT_BOOK_TAB = newname;
	}
	return true;
}
function doSaveComment()
{
	var book_comment_val = $('#book_comment').val().trim();
	if (book_comment_val == '') {
		ajaxValidateErrors({book_comment:['コメントを入力してください。']});
		return false;
	}
	$('#book-comment-btn').hide();
	$('#book-comment-btn-msg').show();
	var post_data = {};
	post_data.id = $('#_id').val();
	post_data.book_comment = book_comment_val;
	post_data.security_token = $('#_security_token').val();
	var url = '/book/save_comment_api';
	ajaxPost(url, post_data, function(data, dataType) {
		if (data.status == AJAX_STATUS_SUCCESS) {
			//var len = $('#comment-length').html() - 0;
			//$('#comment-length').html(len + 1);
			var len = $('#book-comment-num').html() - 0;
			$('#book-comment-num').html(len + 1);
			$('#book-comment-list p.no-data').remove();
			$('#book-comment-list').prepend(data.html);
			$('#book-comment-list .book-comment-item:first').slideDown('slow');
			//$('#_security_token').val(data.security_token);
		} else {
			ajaxValidateErrors(data.errors);
		}
	});
	return false;
}
function doSaveEvaluate(type)
{
	$('#book-info-evaluate-good').hide();
	$('#book-info-evaluate-bad').hide();
	$('#book-info-evaluate-msg').show();
	var post_data = {};
	post_data.id = $('#_id').val();
	post_data.type = type;
	post_data.security_token = $('#_security_token').val();
	var url = '/book/save_evaluate_api';
	ajaxPost(url, post_data, function(data, dataType) {
		if (data.status == AJAX_STATUS_SUCCESS) {
			$('#book-evaluate-num').html(data.evaluate_total);
			$('#book-info-evaluate-num').html(data.evaluate_total);
			//$('#_security_token').val(data.security_token);
		} else {
			ajaxValidateErrors(data.errors);
		}
	});
	return false;
}
//]]>
</script>{/literal}
{if $is_page_preview}
<div class="infomsg" style="font-size:88%;color:#666;text-align:center;">確認用画面では章の切り替えやコメント操作、ePubファイルのダウンロードはできません。</div>
{/if}
<div id="main-contents">
<div id="book-tab">
<ul>
<li id="book-tab-body" {if $form.book_tab=="body"} class="book-tab-selected"{/if}>
	<img src="/img/icon_book_open.png" width="16" height="16" align="top" /> {if !$is_page_preview}<a href="#body" onclick="return selectBookTab('body');">{else}<a href="#">{/if}本文</a></li>
<li id="book-tab-comment" {if $form.book_tab=="comment"} class="book-tab-selected"{/if}>
	<img src="/img/icon_book_comment.png" width="16" height="16" align="top" /> {if !$is_page_preview}<a href="#comment" onclick="return selectBookTab('comment');">{else}<a href="#">{/if}コメント{*(<span id="comment-length">{$form.comment|@length}</span>)*}</a></li>
{*<li id="book-tab-share" {if $form.book_tab=="share"} class="book-tab-selected"{/if}><img src="/img/icon_book_share.png" width="16" height="16" align="top" /> <a href="#share" onclick="return selectBookTab('share');">共有</a></li>*}
</ul>
<div class="clear"></div>
<!-- #user-tab --></div>
<div id="book-detail">

<div id="book-body" style="display:block;">
<div id="book-view-btn">
<span class="book-view-btn-name">
<span>フォント：</span><a href="#" id="serif-font" class="font-type-changer">明朝体</a> / <a href="#" id="sans-serif-font" class="font-type-changer">ゴシック体</a>
</span>
<span class="book-view-btn-name">
<span>サイズ：</span><a href="#" id="up-fontsize" class="font-size-changer">＋</a> / <a href="#" id="down-fontsize" class="font-size-changer">－</a>
</span>
<!-- #book-view-btn --></div>

<div id="book-detail-title">
<h1><a href="/book/{$form.id}/" title="{$form.book.title}">{$form.book.title}</a></h1>
<div id="book-detail-title-subtitle">
{if $form.book.subtitle!=""}<h2>{$form.book.subtitle}</h2>{/if}
<p id="book-detail-title-chapter"><a href="/book/{$form.id}/{if $form.chapter>1}chapter/{$form.chapter}/{/if}" title="{$form.book.page_title}">{$form.book.page_title}</a></p>
</div>
<!-- #book-detail-title --></div>

<div id="book-contents">
{*<h1 class="chapterTitle">{$form.book.page_title}</h1>*}
<p>{$form.book.page_contents|smarty:nodefaults}</p>
<!-- #book-contents --></div>

{if !$is_page_preview}
<div id="book-chapter-pager">
{chapter_pager chapter=$form.chapter total=$form.page|@length id=$form.id}
<!-- #book-chapter-pager --></div>
{/if}
<!-- #book-body --></div>

<div id="book-comment" style="display:none;">
{if $form.book.comment_flag==1}
<div id="book-comment-form">
<form id="commentform" method="post" action="{$base_url}" onsubmit="return doSaveComment();">
{include file="_parts/hidden.tpl"}
<div class="column_wrapper{$form.errors.book_comment|@errorclass}">
	<label for="description">
	 コメント<span class="must">*</span><span class="char-length">あと<span id="book_comment-count">{$form.book_comment|length_check:200}</span>文字</span>
	</label>
	<div id="book-comment-mask"><div>コメントを投稿するには<a href="/login?loc={$base_url}&_hash=comment">ログイン</a>してください。</div></div>
	<textarea{if !$userInfo.id}{/if} class="input-box ime-on" id="book_comment" name="book_comment" onkeyup="lengthCheck('#book_comment-count', 200, this.value.length);" rows="5" cols="40">{$form.book_comment}</textarea>
	{$form.errors.book_comment|@errormsg}
	<p class="notice"></p>
</div>
{if $userInfo.id>0}<div id="book-comment-btn"><input class="btn-02" type="submit" value="投稿する" /></div>{/if}
<div id="book-comment-btn-msg"><p>{$form.book.title}へのコメントありがとう！</p></div>
</form>
</div>
<h3>{$form.book.title}へのコメント</h3>
<div id="book-comment-list">
{foreach item=d from=$form.comment}
	{include file="_parts/book_comment_list.tpl" comment=$d}
{foreachelse}
<p class="no-data">コメントはありません。</p>
{/foreach}
</div>
{else}
<p>{$form.book.title}へのコメントは受け付けておりません。</p>
<form id="commentform" method="post" action="{$base_url}" onsubmit="return doSaveComment();">
{include file="_parts/hidden.tpl"}
</form>
{/if}
<!-- #book-comment --></div>

<div id="book-share">
<!-- #book-share --></div>

<!-- #book-detail --></div>
<!-- #main-contents --></div>

<div id="main-side">

<div id="book-info">
<div id="book-cover">
<a href="{if !$is_page_preview}/book/{$form.id}/{else}#{/if}" title="{$form.book.title}">
{*<a href="{if !$is_page_preview}/reader/{$form.id}/{else}#{/if}" title="{$form.book.title}" target="_blank">*}
<img src="{$form.book.cover_s_path|cover_img_src:$form.book.user_id}" width="90" height="120" alt="{$form.book.title}の表紙" /></a>
<p><a href="{if !$is_page_preview}/book/{$form.id}/{else}#{/if}" title="{$form.book.title}">{$form.book.title}</a></p>
<!-- #book-cover --></div>

{if !$is_page_preview}
<p id="book-author">{$form.user|@username}</p>
{else}
<p id="book-author">{$form.user|@username|nolink}</p>
{/if}
{if $form.book.epub_flag==1}
<div id="book-btn-list">
<ul>
{*
{if $userInfo.id>0}
	<li><img src="/img/icon_bookshelf.png" width="16" height="16" alt=" " align="top" /> <a href="#" onclick="addBookshelf({$form.id});return false;" id="add-bookshelf-btn">マイ本棚に追加する</a></li>
{else}
	<li><img src="/img/icon_bookshelf.png" width="16" height="16" alt=" " align="top" /> <a class="off" id="add-bookshelf-btn">マイ本棚に追加済み</a></li>
{/if}
*}
	<li><img src="/img/icon_epub_download.png" width="16" height="16" alt="　" class="icon-middle" /> <a {if !$is_page_preview}href="/book/{$form.id}/book-{$form.id}.epub" target="_blank"{else}href="#"{/if} title="ePubファイルをダウンロードする">ePubファイル{$form.id|epub_size}</a></li>
</ul>
</div>
{/if}
<div id="book-info-tips">
<span id="tips-price" title="価格"><img src="/img/icon_price_s.png" width="10" height="10" alt="価格" />{if $form.book.charge_flag==0}無料{/if}</span>&nbsp;
<span id="tips-reads" title="閲覧数"><img src="/img/icon_reads_s.png" width="10" height="10" alt="閲覧数" />{$form.book_rank.pv_total|number_format}</span>&nbsp;
<span id="tips-epubs" title="ePubダウンロード数"><img src="/img/icon_epub_download_s.png" width="10" height="10" alt="ePubダウンロード数" />{$form.book_rank.epub_total|number_format}</span>&nbsp;
<span id="tips-comments" title="コメント数"><img src="/img/icon_comment_s.png" width="10" height="10" alt="コメント数" /><span id="book-comment-num">{$form.comment|@length}</span></span>&nbsp;
<span id="tips-evaluates" title="評価"><img src="/img/icon_evaluate_s.png" width="10" height="10" alt="評価" /><span id="book-evaluate-num">{$form.book_rank.evaluate_total|number_format}</span></span>
</div>
<div class="clear"></div>
{if !$is_page_preview}
<div id="book-info-evaluate">
<p><strong>この作品への評価</strong></p>
<ul>
	<li><div id="book-info-evaluate-pt"><img src="/img/icon_evaluate.png" width="24" height="24" alt="評価" align="top" /> <span id="book-info-evaluate-num">{$form.book_rank.evaluate_total}</span> pt</div></li>
{if $form.book_evaluate_status}
	<li id="book-info-evaluate-good"><a id="book-info-evaluate-good-btn" title="いいね！" onclick="doSaveEvaluate(1);">いいね！</a></li>
	<li id="book-info-evaluate-bad"><a id="book-info-evaluate-bad-btn" title="評価しない" onclick="doSaveEvaluate(2);">評価しない</a></li>
	<li id="book-info-evaluate-msg" style="display:none;padding-top:10px;">評価してくれてありがとう！</li>
{else}
	<li style="padding-top:10px;color:#666;">評価済みです。</li>
{/if}
</ul>
<div class="clear"></div>
<div id="book_evaluate"></div>
<!-- #book-info-evaluate --></div>
{/if}
<p>{$form.book.description|nl2br}</p>
{if $form.book.publish_date!=""}<p id="public-date">公開日：{$form.book.publish_date|date_zen_f:false}</p>{/if}
{if !$is_page_preview}
<div id="book-info-share">
<p style="margin-bottom:5px;"><strong>この作品を共有する</strong></p>
<ul>
	<li>
		<a href="http://twitter.com/share" class="twitter-share-button" data-url="{$smarty.const.app_site_url}book/{$form.id}/" data-count="horizontal" data-via="spotwordjp" data-lang="ja">Tweet</a>
		<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	</li>
	<li>
		<iframe src="http://www.facebook.com/plugins/like.php?href={$smarty.const.app_site_url|escape:'url'}{"book/`$form.id`/"|escape:'url'}&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
	</li>
	<li>
		<a href="http://b.hatena.ne.jp/entry/{$smarty.const.app_site_url}book/{$form.id}/" class="hatena-bookmark-button" data-hatena-bookmark-title="{$title}" data-hatena-bookmark-layout="simple" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a>
		<script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
	</li>
{*	<li>
		<a href="http://mixi.jp/share.pl" class="mixi-check-button" data-key="" data-url="{$smarty.const.app_site_url}book/{$form.id}/">Check</a>
		<script type="text/javascript" src="http://static.mixi.jp/js/share.js"></script>
	</li>*}
</ul>
<div class="clear"></div>
<!-- #book-info-share --></div>
{/if}
<!-- #book-info --></div>

<div id="book-menu">
<h3>目次</h3>
<ul>
{foreach item=d from=$form.page}
	{assign var="chapternum" value=$d.page_order+1}
	{if $chapternum>1}
	<li><a href="{if !$is_page_preview}/book/{$form.id}/chapter/{$chapternum}/{else}#{/if}">{$d.page_title}</a></li>
	{else}
	<li><a href="{if !$is_page_preview}/book/{$form.id}/{else}#{/if}">{$d.page_title}</a></li>
	{/if}
{/foreach}
</ul>
<!-- #book-menu --></div>

<!-- #main-side --></div>

<div class="clear"></div>
{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$.timer(100, function (timer) {
		selectBookTab(window.location.hash.replace('#',''));
	});
	$('a.font-type-changer').click(function(){
		var font = '';
		if (this.id == 'serif-font') {
			font = SERIF_FONT_FAMILY;
		} else {
			font = BASE_FONT_FAMILY;
		}
		$("#book-contents").css("font-family", font);
		return false;
	});
	$('a.font-size-changer').click(function(){
		var size = $("#book-contents").css("font-size");
		size = size.replace('px', '');
		size -= 0;
		if (this.id == 'up-fontsize') {
			if (size<=25) size += 2;
		} else {
			if (size>9) size -= 2;
		}
		$("#book-contents").css("font-size", size+'px');
		return false;
	});
	if (IS_LOGIN == false) {
		var BOOK_COMMENT_MASK_FLAG = false;
		$('#book_comment').mouseover(function(){
			if (BOOK_COMMENT_MASK_FLAG == false) $('#book-comment-mask').fadeIn('fast', function(){ BOOK_COMMENT_MASK_FLAG = true; });
		});
		$('#book-comment-mask').mouseout(function(){
			$('#book-comment-mask').fadeOut('fast', function(){ BOOK_COMMENT_MASK_FLAG = false; });
		});
	}
	$('#tips-reads, #tips-price, #tips-epubs, #tips-comments, #tips-evaluates, #book-info-evaluate-good-btn, #book-info-evaluate-bad-btn').tipsy();
});
//]]>
</script>
{/literal}