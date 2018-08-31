
<div id="main-contents">
<div style="margin-bottom:5px;font-size:88%;"><img src="/img/icon_back.png" widht="16" height="16" alt="戻る" align="top" /> <a href="/user/mydesk/">本の一覧へ</a></div>
<div id="mydesk">
{include file="_parts/mydesk_successmsg.tpl"}
<div id="mydesk-tab">
<div id="mydesk-tab-title"><h2>{$form.htitle}</h2></div>
<div id="mydesk-tab-btn">
<ul>
	<li class="mydesk-tab-btn-selected">基本情報</li>
{if $form.id>0}
	<li><a href="page?id={$form.id}">内容</a></li>
	<li><a href="image?id={$form.id}">画像管理</a></li>
	<li><a href="publish?id={$form.id}">公開設定</a></li>
{/if}
</ul>
<!-- #mydesk-tab-btn --></div>
<!-- #mydesk-tab --></div>
<form id="mainform" method="post" action="created">
{include file="_parts/hidden.tpl"}
{if $form.save}
<div class="successmsg" style="margin:20px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" style="vertical-align:middle;" /> 保存しました。</div>
{/if}
{if $form.errors}
<div class="errormsg" style="margin:20px 0px;"><img src="/img/icon-exclamation.png" width="16" height="16" style="vertical-align:middle" /> 入力内容に誤りがあります</div>
{/if}

<p><span class="must">*</span> 項目は必ず入力してください。</p>

<div class="column_wrapper{$form.errors.title|@errorclass}">
	<label for="title">
	 本のタイトル<span class="must">*</span><span class="char-length">あと<span id="title-count">{$form.title|length_check:40}</span>文字</span>
	</label>
	<input size="40" type="text" id="title" class="input-box ime-on" name="title" value="{$form.title}" onkeyup="lengthCheck('#title-count', 40, this.value.length);" />
	{$form.errors.title|@errormsg}
	<p class="notice">
	</p>
</div>
<div class="column_wrapper{$form.errors.subtitle|@errorclass}">
	<label for="subtitle">
	 サブタイトル<span class="char-length">あと<span id="subtitle-count">{$form.subtitle|length_check:40}</span>文字</span>
	</label>
	<input size="40" type="text" class="input-box ime-on" name="subtitle" value="{$form.subtitle}" onkeyup="lengthCheck('#subtitle-count', 40, this.value.length);" />
	{$form.errors.subtitle|@errormsg}
	<p class="notice">
	</p>
</div>
<div class="column_wrapper{$form.errors.category_id|@errorclass}">
	<label for="category_id">
	 ジャンル<span class="must">*</span>
	</label>
	{tag type="select" name="category_id" options=$categoryIdOptions blank="選んでください" selected=$form.category_id}
	{$form.errors.category_id|@errormsg}
	<p class="notice">
	</p>
</div>
<div class="column_wrapper{$form.errors.description|@errorclass}">
	<label for="description">
	 本の概要<span class="must">*</span><span class="char-length">あと<span id="description-count">{$form.description|length_check:400}</span>文字</span>
	</label>
	<textarea rows="5" cols="40" class="input-box ime-on" name="description" onkeyup="lengthCheck('#description-count', 400, this.value.length);" style="height:140px;">{$form.description}</textarea>
	{$form.errors.description|@errormsg}
	<p class="notice">
	</p>
</div>
<div id="btn-area">
{if $form.id>0}
<input class="btn-02" type="submit" value="保存する" />
{else}
<input class="btn-02" type="submit" value="登録する" />
{/if}
</div>
</form>

<!-- #mydesk --></div>
<!-- #main-contents --></div>

<div id="main-side">

{include file="_parts/mydesk_side_publish.tpl" publication=$form.publication}

{include file="_parts/mydesk_side.tpl"}

<!-- #main-side --></div>

<div class="clear"></div>

{literal}
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('#title').focus();
});
//]]>
</script>
{/literal}
